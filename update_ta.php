<?php
$tas = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
foreach($tas as $k => $ta) {
    $ta->is_active = ($k === 0);
    $ta->save();
}
echo "Tahun Ajaran updated.\n";
