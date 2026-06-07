const fs = require('fs');
let html = fs.readFileSync('d:/PA/gurudocsAG/resources/contoh_template/cp_extracted.html', 'utf8');

// remove the title parts since they are already dynamic in our header
html = html.replace('<p><strong>CAPAIAN PEMBELAJARAN</strong></p><p><strong>MATA PELAJARAN AL-QUR\'AN HADIS</strong></p>', '');

// add border to tables so they look nice
html = html.replace(/<table/g, '<table style=\"border-collapse: collapse; width: 100%; margin-top: 10px;\" border=\"1\" cellpadding=\"8\"');

// Read the blade template
let blade = fs.readFileSync('d:/PA/gurudocsAG/resources/views/guru/perangkat/templates/cp_default.blade.php', 'utf8');

// Use regex to replace content inside <div class="mceEditable">
blade = blade.replace(/<div class=\"mceEditable\"[^>]*>([\s\S]*?)<\/div>\s*\{\{-- TANDA TANGAN/m, 
    '<div class=\"mceEditable\" style=\"min-height: 200px; padding: 10px; border: 1px dashed #ccc;\">\n' + html + '\n</div>\n\n    {{-- TANDA TANGAN');

fs.writeFileSync('d:/PA/gurudocsAG/resources/views/guru/perangkat/templates/cp_default.blade.php', blade);
