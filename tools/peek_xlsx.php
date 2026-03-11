<?php
$filename = 'C:/xampp/htdocs/CertGen/FORM 137-E RECEIVED FROM OTHER SCHOOL 2025-2026.xlsx';
$zip = new ZipArchive;

if ($zip->open($filename) === TRUE) {
    if ($ssData = $zip->getFromName('xl/sharedStrings.xml')) {
        $xml = simplexml_load_string($ssData);
        $sharedStrings = [];
        foreach ($xml->si as $si) {
            if (isset($si->r)) {
                $text = '';
                foreach ($si->r as $r) {
                    $text .= (string)$r->t;
                }
                $sharedStrings[] = $text;
            }
            else {
                $sharedStrings[] = (string)$si->t;
            }
        }
    }

    for ($sh = 1; $sh <= 8; $sh++) {
        $sheetData = $zip->getFromName("xl/worksheets/sheet$sh.xml");
        if ($sheetData) {
            echo "--- SHEET $sh ---\n";
            $xml = simplexml_load_string($sheetData);
            $limit = 2; // only row 2 and 3
            foreach ($xml->sheetData->row as $row) {
                $rowIndex = (int)$row['r'];
                if ($rowIndex == 2 || $rowIndex == 3) {
                    echo "Row $rowIndex:\n";
                    foreach ($row->c as $c) {
                        $r = (string)$c['r'];
                        $col = preg_replace('/[0-9]/', '', $r);
                        $val = ($c['t'] == 's') ? ($sharedStrings[(string)$c->v] ?? "") : (string)$c->v;
                        echo "  $col -> " . trim($val) . "\n";
                    }
                }
                if ($rowIndex > 3)
                    break;
            }
        }
    }
    $zip->close();
}
