<?php
$filename = 'C:/xampp/htdocs/CertGen/FORM 137-E RECEIVED FROM OTHER SCHOOL 2025-2026.xlsx';
$zip = new ZipArchive;

if ($zip->open($filename) === TRUE) {
    // Get Shared Strings
    $sharedStrings = [];
    $ssData = $zip->getFromName('xl/sharedStrings.xml');
    if ($ssData) {
        $xml = simplexml_load_string($ssData);
        foreach ($xml->si as $si) {
            $sharedStrings[] = (string)$si->t;
        }
    }

    $sheetData = $zip->getFromName('xl/worksheets/sheet1.xml');
    if ($sheetData) {
        $xml = simplexml_load_string($sheetData);

        $limit = 5;
        foreach ($xml->sheetData->row as $row) {
            $rowIndex = (int)$row['r'];
            if ($rowIndex <= 2)
                continue;

            echo "Row $rowIndex\n";
            $rowValues = [];
            foreach ($row->c as $c) {
                $r = (string)$c['r'];
                $col = preg_replace('/[0-9]/', '', $r);

                $v = (string)$c->v;
                if ($c['t'] == 's') {
                    $val = $sharedStrings[$v] ?? "";
                }
                else {
                    $val = $v;
                }
                echo "Col $col: " . trim($val) . "\n";
            }
            $limit--;
            if ($limit <= 0)
                break;
        }
    }
    $zip->close();
}
?>
