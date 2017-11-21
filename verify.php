<?php

class Verify {
    public function ver($path = "users.txt") {
        if (isset($_REQUEST['key'])) {
            $key = "";
            foreach(str_split($_REQUEST['key']) as $k) {
                if ($k=="*" or $k=="-" or $k=="@" or $k=="[" or $k=="]") {
                    $key .= "\\" . $k;
                } else {
                    $key .= $k;
                }
                unset($k);
            }
            $content = file_get_contents($path); // maybe match $key + "x"
            if (!preg_match("/\|".$key."/", $content)) {
                echo "{\"error\":\"Wrong key\"}";
                return false;
            }
        return true;
        }
    return false;
    }
    public function vkey() {
        $key = "";
        foreach(str_split($_REQUEST['key']) as $k) {
            if ($k=="*" or $k=="-" or $k=="@" or $k=="[" or $k=="]") {
                $key .= "\\" . $k;
            } else {
                $key .= $k;
            }
            unset($k);
        }
    return $key;
    }
}