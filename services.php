<?php

/*class A {
    public $s;
    function __construct() {
    return $this->s = json_decode(file_get_contents("services"));
    }
}*/
class Services {
    public $services = array
    (
        1 => array(112, 1, 0.84, 0.90, 1000, 60000, 1000),
        2 => array(110, 1, 0.12, 0.15, 1000, 400000, 1000),
        3 => array(70, 1, 5.60, 6, 100, 10000, 100)
    );
    public $info = array
    (
        1 => array("[Real] YouTube views", "3-4 minutes retention(watch time). Non Drop views and Instant start. Good for video ranking."),
        2 => array("[Real] Facebook Video views", "Instant start and Natural speed."),
        3 => array("[Real] LinkedIn Followers(USA)", "High Quality, Picture and Bio.")
    );
    public $custom = array
    (
        1 => array("7zm@icaon36n@l5i8rg-]e7h[@10xn" => 0.82),
        2 => array("7zm@icaon36n@l5i8rg-]e7h[@10xn" => 0.11),
        3 => array("7zm@icaon36n@l5i8rg-]e7h[@10xn" => 5.52)
    );
/*public $a = array(
    array
    (
        1 => array(112, 1, 0.84, 0.90, 1000, 60000, 1000),
        2 => array(110, 1, 0.12, 0.15, 1000, 400000, 1000),
        3 => array(70, 1, 5.60, 6, 100, 10000, 100)
    ),
    array
    (
        1 => array("[Real] YouTube views", "3-4 minutes retention(watch time). Non Drop views and Instant start. Good for video ranking."),
        2 => array("[Real] Facebook Video views", "Instant start and Natural speed."),
        3 => array("[Real] LinkedIn Followers(USA)", "High Quality, Picture and Bio.")
    ),
    array
    (
        1 => array("7zm@icaon36n@l5i8rg-]e7h[@10xn" => 0.82),
        2 => array("7zm@icaon36n@l5i8rg-]e7h[@10xn" => 0.11),
        3 => array("7zm@icaon36n@l5i8rg-]e7h[@10xn" => 5.52)
    )
);*/
}
//$a = new Services();
//$a = $a->s;
//file_put_contents("services",json_encode($a));