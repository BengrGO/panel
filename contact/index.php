<?php

require("../verify.php");
$ver = new Verify();
if ($ver->ver("../users.txt")) { //  key verified
function getname($path = "../users.txt") {
    $ver = new Verify();
    $key = $ver->vkey();
    $ids = file_get_contents($path);
    preg_match("/[^\r\n]*". $key ."/", $ids, $matches);
    $matches = implode("", $matches);
    $matches = substr($matches , 0, strpos($matches, "/"));
    return $matches;
}
$key = $ver->vkey();
if (isset($_REQUEST["text"])) {
    $chat = file_get_contents("chat.txt");
    preg_match("/". $key ."\|[^\r\n]*/", $chat, $matches);
    $matches = implode("", $matches);
    if (isset($_REQUEST["from"])) {
        $name = $_REQUEST["from"];
    } else {
        $name = getname();
    }
    $matches = preg_replace("/".preg_quote($matches, "/")."/", $matches.$_REQUEST["text"]." -/".$name."/s'0", $chat);
    file_put_contents("chat.txt", $matches);
    echo '
        <form name="redirect" method="post">
        <input type="hidden" name="key" value=' . $_REQUEST["key"] . '>
        <input type="hidden" type="submit">
        </form>
        <script type="text/javascript"> // javascript to unset POST when reload
        document.redirect.submit();
        </script>
        ';
}
$chat = file_get_contents("chat.txt");
preg_match("/". $key ."\|[^\r\n]*/", $chat, $matches);
$matches = implode("", $matches);
$matches = substr(strrchr($matches, "|"), 1);
$matches = preg_split("/\/s'0|\/s\!0/", $matches);
echo '
    <form method="post" style = "margin: 0.5% 0%;">
    <input type="hidden" name="key" value="' . $_REQUEST["key"] . '">
    <input type="submit" value="Reload" style="width: 100%; padding: 12px;">
    </form>
    <fieldset>
    <legend>Contact:</legend>
';
foreach ($matches as $k => $m) {
    if (empty($m)) {
        continue;
    }
    if (!isset($matches[$k+5])) {
    echo '
    <textarea rows="3" disabled style="width: 99.8%;">
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
// --------------------------------- Live Chat ---------------------------------------
if (isset($_REQUEST["ltext"])) {
    $chat = file_get_contents("lchat.txt");
    //preg_match("/[^\r\n]*/", $chat, $matches); // bad
    //$matches = implode("", $matches);
    $name = getname();
    //$matches = $matches.PHP_EOL.$_REQUEST["key"]."|".$_REQUEST["ltext"]." -/".$name."/s'0";
    file_put_contents("lchat.txt", $chat.PHP_EOL.$_REQUEST["key"]."|".$_REQUEST["ltext"]." -/".$name."/s'0");
    echo '
        <form name="redirect" method="post">
        <input type="hidden" name="key" value=' . $_REQUEST["key"] . '>
        <input type="hidden" type="submit">
        </form>
        <script type="text/javascript"> // javascript to unset POST when reload
        document.redirect.submit();
        </script>
        ';
}
$chat = file_get_contents("lchat.txt");
preg_match_all("/[^\r\n]*/", $chat, $matches);
echo '
    <fieldset>
    <legend>Live Chat:</legend>
';
$a = 0;
foreach ($matches[0] as $k => $m) {
    $m = substr(strrchr($m, "|"), 1);
    $m = preg_split("/\/s'0|\/s\!0/", $m)[0];
    if (empty($m)) {
        continue;
    }
    $a += 1;
    if (!isset($matches[0][$k+28])) {
        echo '
    <textarea rows="2" disabled style="width: 99.8%;">
    ' . $m . '</textarea>
    ';
    }
}
echo '
        <form method="post" style = "margin: 0.5% 0%;">
        <input type="hidden" name="key" value="' . $_REQUEST["key"] . '">
        <input type="text" name="ltext" required style="width: 20%;">
        <input type="submit" value="Send" style="width: 5%;">
        </form>
    </fieldset>
';
// look at w3school chat Messages
//  Chat.txt livechat.txt tickets.txt
return; } // ---------------

require("../getkey.php");
$gk = new GetKey("../");