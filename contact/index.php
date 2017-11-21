<?php

require("../verify.php");
$ver = new Verify();
if ($ver->ver("../users.txt")) { //  key verified
function getname() {
    $ver = new Verify();
    $key = $ver->vkey();
    $ids = file_get_contents("../users.txt");
    preg_match("/[^\r\n]*". $key ."/", $ids, $matches);
    $matches = implode("", $matches);
    $matches = substr($matches , 0, strpos($matches, "/"));
    return $matches;
}
if (isset($_REQUEST["text"])) {
    $key = $ver->vkey();
    $chat = file_get_contents("chat.txt");
    preg_match("/". $key ."\|[^\r\n]*/", $chat, $matches);
    $matches = implode("", $matches);
    $matches = preg_replace("/".preg_quote($matches, "/")."/", $matches.$_REQUEST["text"]."¤", $chat);
    file_put_contents("chat.txt", $matches);
}
$chat = file_get_contents("chat.txt");
preg_match("/". $key ."\|[^\r\n]*/", $chat, $matches);
$matches = implode("", $matches);
$matches = substr(strrchr($matches, "|"), 1);
$matches = explode("¤", $matches);
echo '
    <fieldset>
    <legend>Chat:</legend>
';
foreach ($matches as $k => $m) {
    if (empty($m)) {
        continue;
    }
    if (!isset($matches[$k+10])) {
    echo '
    <textarea rows="4" disabled style="width: 99.8%; font-weight: bold;">
    ' . $m . '</textarea>
    ';
    }
}
echo '
        <form method="post" style = "margin: 0.5% 0%;">
        <input type="hidden" name="key" value="' . $_REQUEST["key"] . '">
        <input type="text" name="text" required style="width: 20%;">
        <input type="submit" value="Send" style="width: 5%;">
        </form>
    </fieldset>
';
//  load textareas from file  //  look at w3school chat Messages
//  Chat.txt livechat.txt tickets.txt

return; } // ---------------

require("../getkey.php");
$gk = new GetKey("../");