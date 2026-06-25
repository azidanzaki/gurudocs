param(
    [Parameter(Mandatory=$true)][string]$templatePath,
    [Parameter(Mandatory=$true)][string]$outputPath,
    [Parameter(Mandatory=$true)][string]$jsonPath,
    [Parameter(Mandatory=$false)][string]$sofficePath = "soffice"
)

# Load data from JSON
$data = Get-Content -Path $jsonPath -Encoding UTF8 | ConvertFrom-Json

# Create temporary working directory
$guid = [guid]::NewGuid().ToString()
$tempDir = Join-Path $env:TEMP "pkg_$guid"
New-Item -ItemType Directory -Path $tempDir | Out-Null

# Copy template DOCX to temp folder
Copy-Item -Path $templatePath -Destination (Join-Path $tempDir "template.docx") -Force

# Extract DOCX (which is a zip archive)
Expand-Archive -Path (Join-Path $tempDir "template.docx") -DestinationPath (Join-Path $tempDir "doc") -Force

# Path to document.xml inside DOCX
$xmlPath = Join-Path $tempDir "doc\word\document.xml"
$xmlContent = Get-Content -Path $xmlPath -Raw -Encoding UTF8

# Simple placeholder replacement – extend as needed
$placeholders = @{
    "{{guru_name}}" = $data.guru_name
    "{{guru_nip}}"  = $data.guru_nip
    "{{mapel}}"     = $data.mapel
    "{{kelas}}"     = $data.kelas
    "{{tahun_ajaran}}" = $data.tahun_ajar
    "{{penilai}}" = $data.penilai
}
foreach ($ph in $placeholders.Keys) {
    $value = $placeholders[$ph]
    $xmlContent = $xmlContent -replace [regex]::Escape($ph), [regex]::Escape($value)
}
# Additional logic could replace aspect tables here.

# Save modified document.xml
Set-Content -Path $xmlPath -Value $xmlContent -Encoding UTF8

# Re-pack the DOCX
$filledDocx = Join-Path $tempDir "filled.docx"
Compress-Archive -Path (Join-Path $tempDir "doc\*") -DestinationPath $filledDocx -Force

# Convert to PDF using LibreOffice (assumes soffice is in PATH or specify full path)
& $sofficePath --headless --convert-to pdf --outdir $tempDir $filledDocx
if ($LASTEXITCODE -ne 0) {
    Write-Error "LibreOffice conversion failed"
    exit 1
}

# Move resulting PDF to the requested output location
$generatedPdf = Join-Path $tempDir ([io.path]::GetFileNameWithoutExtension($filledDocx) + ".pdf")
Move-Item -Path $generatedPdf -Destination $outputPath -Force

# Clean up temporary files (optional, comment out for debugging)
Remove-Item -Recurse -Force $tempDir

exit 0
