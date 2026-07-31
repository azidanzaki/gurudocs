param(
    [Parameter(Mandatory=$true)][string]$templatePath,
    [Parameter(Mandatory=$true)][string]$outputPath,
    [Parameter(Mandatory=$true)][string]$jsonPath,
    [Parameter(Mandatory=$false)][string]$sofficePath = "C:\Program Files\LibreOffice\program\soffice.com"
)

Add-Type -AssemblyName System.IO.Compression.FileSystem

# Load data from JSON
$data = Get-Content -Path $jsonPath -Encoding UTF8 | ConvertFrom-Json

# Create temporary working directory
$guid = [guid]::NewGuid().ToString()
$tempDir = Join-Path $env:TEMP "pkg_$guid"
New-Item -ItemType Directory -Path $tempDir | Out-Null

$docDir  = Join-Path $tempDir "doc"
$zipCopy = Join-Path $tempDir "template.zip"

# Extract DOCX using .NET ZipFile (handles [Content_Types].xml & special filenames correctly)
Copy-Item -Path $templatePath -Destination $zipCopy -Force
[System.IO.Compression.ZipFile]::ExtractToDirectory($zipCopy, $docDir)

# Path to document.xml inside DOCX
$xmlPath    = Join-Path $docDir "word\document.xml"
$xmlContent = [System.IO.File]::ReadAllText($xmlPath, [System.Text.Encoding]::UTF8)

# Placeholder replacement
$placeholders = @{
    "{{guru_name}}"    = [string]$data.guru_name
    "{{guru_nip}}"     = [string]$data.guru_nip
    "{{mapel}}"        = [string]$data.mapel
    "{{kelas}}"        = [string]$data.kelas
    "{{tahun_ajaran}}" = [string]$data.tahun_ajaran
    "{{penilai}}"      = [string]$data.penilai
}
foreach ($ph in $placeholders.Keys) {
    $xmlContent = $xmlContent -replace [regex]::Escape($ph), [regex]::Escape($placeholders[$ph]) -replace [regex]::Escape([regex]::Escape($placeholders[$ph])), $placeholders[$ph]
}

# Save modified document.xml (UTF-8 no BOM)
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllText($xmlPath, $xmlContent, $utf8NoBom)

# Re-pack the DOCX using .NET ZipFile (preserves all entries including [Content_Types].xml)
$filledDocx = Join-Path $tempDir "filled.docx"
[System.IO.Compression.ZipFile]::CreateFromDirectory($docDir, $filledDocx)

# Convert to PDF using LibreOffice
$loProfileDir = "file:///" + ($env:TEMP -replace '\\', '/') + "/lo_pkg_$guid"
& $sofficePath --headless "-env:UserInstallation=$loProfileDir" --convert-to pdf --outdir $tempDir $filledDocx 2>&1 | Out-Null

if ($LASTEXITCODE -ne 0) {
    Write-Error "LibreOffice conversion failed (exit $LASTEXITCODE)"
    Remove-Item -Recurse -Force $tempDir -ErrorAction SilentlyContinue
    exit 1
}

# Move resulting PDF to the requested output location
$generatedPdf = Join-Path $tempDir "filled.pdf"
if (-not (Test-Path $generatedPdf)) {
    Write-Error "PDF file not found after LibreOffice conversion: $generatedPdf"
    Remove-Item -Recurse -Force $tempDir -ErrorAction SilentlyContinue
    exit 1
}

Move-Item -Path $generatedPdf -Destination $outputPath -Force

# Cleanup
Remove-Item -Recurse -Force $tempDir -ErrorAction SilentlyContinue

exit 0
