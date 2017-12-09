<?php

class Api
{
    public $api_url = array('http://smmlite.com/api/v2','https://smmglobe.com/api/v2','https://justanotherpanel.com/api/v2'); // API URL

    public $api_key = array('ad6c12efa86e5787691aafb3abebd9e6','e3d08e861c4e6c0e084ff8cb26f9eb0e','ea224e88cd5b3a3d18c9ce6b5dbde376'); // Your API key

    public function order($data, $prov = 0)   // provider 0 - smmlite.com
    { // add order
        $post = array_merge(array('key' => $this->api_key[$prov], 'action' => 'add'), $data);
        return json_decode($this->connect($post, $prov));
    }

    public function status($order_id, $prov = 0)
    { // get order status
        return json_decode($this->connect(array(
            'key' => $this->api_key[$prov],
            'action' => 'status',
            'order' => $order_id
        ), $prov));
    }

    public function services($prov = 0)
    { // get services
        return json_decode($this->connect(array(
            'key' => $this->api_key[$prov],
            'action' => 'services',
        ), $prov));
    }

    public function balance($prov = 0)
    { // get balance
        return json_decode($this->connect(array(
            'key' => $this->api_key[$prov],
            'action' => 'balance',
        ), $prov));
    }


    private function connect($post, $prov = 0)
    {
        $_post = Array();
        if (is_array($post)) {
            foreach ($post as $name => $value) {
                $_post[] = $name . '=' . urlencode($value);
            }
        }

        $ch = curl_init($this->api_url[$prov]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (is_array($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        curl_close($ch);
        return $result;
    }
}

function mat($file, $needle) {
    $ver = new Verify();
    $key = $ver->vkey();
    $ids = file_get_contents($file);
    preg_match("/" . $key . "\\" . $needle."[^\r\n]*/", $ids, $matches);
    $matches = implode("", $matches);
    $matches = substr(strrchr($matches, $needle), 1);
    unset($ids); return $matches;
}

if (isset($_REQUEST["req"])) {
    echo "var_dump($_REQUEST): " . var_dump($_REQUEST) . "<br>";
}

if (!isset($_REQUEST["key"])) {
    echo "{\"error\":\"No key specified\"}";
    return;
}
require("../../verify.php");
$ver = new Verify();
$key = $ver->vkey();
if (!$ver->ver("../../users.txt")) {
    return;
}
if (isset($_REQUEST["status"])) { // status of order
    if (!ctype_digit($_REQUEST["status"])) {
        echo "{\"error\":\"order ID must be natural number, so 0 or more\"}";
        return;
    }
    $mat = mat("../../ids.txt", "/");
    if (!preg_match("/" . $_REQUEST["status"] . "/", $mat)) {
        echo "{\"error\":\"Order ID not found\"}";
        return;
    }
    preg_match("/" . $_REQUEST["status"] . "/", $mat, $matches, PREG_OFFSET_CAPTURE); // not working if wrong id
    preg_match("(\d+)", $mat, $matches, PREG_OFFSET_CAPTURE, $matches[0][1] + strlen($matches[0][0]));
    $api = new Api();
    $status = $api->status($_REQUEST["status"], $matches[0][0]);
    if (is_array($status)) {
        array_slice($status, 1);
    }
    echo json_encode($status) . "<br>";
    return;
    // order status
}
if (isset($_REQUEST["balance"])) {
    $mat = mat("../../balance.txt", ":");
    echo "{\"Balance\":\"" . $mat . "\"}";
    return;
    // balance.txt balance
}
if (isset($_REQUEST["ids"])) { // display order ids
    if (!ctype_digit($_REQUEST["ids"])) {
        echo "{\"error\":\"Number of order IDs to display must be natural number, so 0 or more\"}";
        return;
    }
    //echo "{\"<br>" . implode("<br>", array_slice(explode(PHP_EOL, file_get_contents("../../ids.txt")), -($_REQUEST["ids"] + 1))) . "\"}";
    $ids = file_get_contents("../../ids.txt");
    $mat = mat("../../ids.txt", "/");
    $ids = preg_replace("(\|\d+)", "", $ids);
    if ($ids == "") {
        echo "{\"Orders\":\"No orders found\"}";
        return;
    }
    echo "{\"Orders\":\"" . json_encode(array_slice(explode(",", $ids), -($_REQUEST["ids"]))) . "\"}";
    return;
}
if (!isset($_REQUEST["id"])) {
    echo "Order ID not specified";
    return;
}
if (!ctype_digit($_REQUEST["id"])) {
    echo "{\"error\":\"order id must be natural number, so 0 or more\"}";
    return;
}
require("../../services.php");
$serv = new Services();
$id = $serv->services;

//$api = new Api();
//$order = $api->order(array('service' => $id[$_REQUEST[0]][0], 'link' => "youtube.com", 'quantity' => "1000"));

if (empty($id[$_REQUEST["id"]])) {
    echo "Unknown ID";
    return;
}
if (!isset($_REQUEST["url"])) {
    echo "{\"error\":\"url not specified\"}";
    return;
}
if (!isset($_REQUEST["quantity"])) {
    echo "{\"error\":\"quantity not specified\"}";
    return;
}
if (!ctype_digit($_REQUEST["quantity"])) {
    echo "{\"error\":\"wrong quantity: use natural number, so 0 or more\"}";
    return;
}
if (intval($_REQUEST["quantity"]) < $id[$_REQUEST["id"]][4]) {
    echo "{\"error\":\"Minimum quantity is ". $id[$_REQUEST["id"]][4] ."\"}";
    return;
}
if (intval($_REQUEST["quantity"]) > $id[$_REQUEST["id"]][5]) {
    echo "{\"error\":\"Maximum quantity is ". $id[$_REQUEST["id"]][5] ."\"}";
    return;
}
if (intval($_REQUEST["quantity"]) % $id[$_REQUEST["id"]][6] != 0) {
    echo "{\"error\":\"This service quantity must be multiple of ". $id[$_REQUEST["id"]][6] ."\"}";
    return;
}


/*if (strpos(json_encode($order), "error") != false) {
    echo "error please contuct us<br>-email: jendasvec7@gmail.com<br>-WhatsApp/phone: +420806011051";
    return;
}*/

// ************ balance **************
$price = $id[$_REQUEST["id"]][3];
if (strpos(file_get_contents("../../resellers.txt"), $_REQUEST["key"]) !== false) {
    $price = $id[$_REQUEST["id"]][2];
}
$c = $serv->custom;
if (in_array($_REQUEST["key"], array_keys($c[$_REQUEST["id"]]))) {
    $price = $c[$_REQUEST["id"]][$_REQUEST["key"]];
}

$balance = file_get_contents("../../balance.txt");
$mat = mat("../../balance.txt", ":");
$bal = strval(floatval($mat) - $price / 1000 * intval($_REQUEST["quantity"]));
if ($bal < 0) {
    echo "{\"error\":\"not enough balance\"";
    echo ",\"quantity\":\"" . htmlspecialchars($_REQUEST["quantity"]) . "\"";
    echo nl2br(",\"price\":\"") . strval($price / 1000 * intval($_REQUEST["quantity"]) . "\"");
    echo ",\"Balance\":\"" . floatval($mat) . "\"}";
    return;
}
$balance = preg_replace("/" . $key . ":[^\r\n]*/", $_REQUEST['key'].":".$bal, $balance);
file_put_contents("../../balance.txt", $balance);
//echo $balance;
echo "{\"quantity\":\"" . htmlspecialchars($_REQUEST["quantity"]) . "\"";
echo nl2br(",\"price\":\"") . strval($price / 1000 * intval($_REQUEST["quantity"]) . "\"");
echo ",\"Balance\":\"" . $bal . "\"}";
unset($balance);

$api = new Api();
$order = $api->order(array('service' => $id[$_REQUEST["id"]][0], 'link' => $_REQUEST["url"], 'quantity' => $_REQUEST["quantity"]), $id[$_REQUEST["id"]][1]);

// ************ IDs **************

$current = file_get_contents("../../ids.txt");
$mat = mat("../../ids.txt", "/");
echo json_encode($mat);
if ($mat != "") {
    $mat .= "," . json_encode($order) . "|" . $id[$_REQUEST["id"]][1];
} else {
    $mat .= json_encode($order) . "|" . $id[$_REQUEST["id"]][1];
}
$current = preg_replace("/" . $key . "\/[^\r\n]*/", $_REQUEST['key']."/".$mat, $current);
file_put_contents("../../ids.txt", $current);
unset($current); unset($mat);








