<?php
require("../verify.php");
function mat($file, $needle) {
    $ver = new Verify();
    $key = $ver->vkey();
    $ids = file_get_contents($file);
    preg_match("/" . $key . "\\" . $needle."[^\r\n]*/", $ids, $matches);
    $matches = implode("", $matches);
    $matches = substr(strrchr($matches, $needle), 1);
    unset($ids); return $matches;
}

$mat = mat("../balance.txt", ":");
echo "Balance: " . $mat;

//json_encode($a->services[2])