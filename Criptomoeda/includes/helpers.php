<?php
function getNextId($filename) {
    $maxId = 0;
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ((int)$data[0] > $maxId) {
                $maxId = (int)$data[0];
            }
        }
        fclose($handle);
    }
    return $maxId + 1;
}
?>