<?php
require("../verify.php");
function getname($key, $path = "../users.txt") {
    $ids = file_get_contents($path);
    preg_match("/[^\r\n]*". preg_quote($key, "/") ."/", $ids, $matches);
    $matches = implode("", $matches);
    $matches = substr($matches , 0, strpos($matches, "/"));
    return $matches;
}
// ----------------------------------------------------------------
$ver = new Verify();
if ($ver->ver("admins.txt")) {
    if (isset($_REQUEST["chatkey"])) {
        var_dump($_REQUEST);
        // getname($_REQUEST["chatkey"]);
        $chat = file_get_contents("../contact/chat.txt");
        preg_match("/" . $_REQUEST["chatkey"] . "\|[^\r\n]*/", $chat, $match);
        $match = implode("", $match);
        $match = substr($match, 0, strlen($match) - 1);
        preg_match("/" . preg_quote($match) . "\|[^\r\n]*/", $chat, $matches);
        $matches = implode("", $matches);
        $matches = substr(strrchr($matches, "|"), 1);
        $matches = preg_split("/\/s'0|\/s\!0/", $matches);
        echo '
        <fieldset>
        <legend>Chat:</legend>
        ';
        foreach ($matches as $k => $m) {
            if (empty($m)) {
                continue;
            }
            if (!isset($matches[$k + 10])) {
                echo '
                <textarea rows="4" disabled style="width: 99.8%;">
                ' . $m . '</textarea>
                ';
            }
        }
        $hl = 0;
        if (isset($_REQUEST["hl"])) {
            $hl = $_REQUEST["hl"];
        }
        //var_dump($hl);
        echo '' . $_REQUEST["chatkey"] . '
        <iframe name="hf" onload="jsf()" width="0" height="0" border="0" style="display: none;"></iframe>
        <form method="post" action="/contact/" target="hf" style = "margin: 0.5% 0%;">
        <input type="hidden" name="key" value="' . $_REQUEST["chatkey"] . '">
        <input type="text" name="text" required style="width: 20%;">
        <input type="hidden" name="from" value="' . getname($_REQUEST["key"], "admins.txt") . '">
        <input type="submit" value="Send" style="width: 5%; ">
        </form>
        </fieldset>
        
        <form name="redirect" method="post">
        <input type="hidden" name="key" value=' . $_REQUEST["key"] . '>
        <input type="hidden" name="chatkey" value=' . $_REQUEST["chatkey"] . '>
        <input type="hidden" name="from" value="' . getname($_REQUEST["key"], "admins.txt") . '">
        <input type="hidden" name="hl" value="1">
        <input type="hidden" type="submit">
        </form>
        
        <script type="text/javascript">
        function jsf() { document.redirect.submit(); }
        /*setTimeout(function(){
        var hl = '.json_encode($hl).";".';
        if (!hl) {
            document.redirect.submit();
        } else {
            history.go(-1);
        }
        }, 2000);
        }
        jsf();*/
        </script> '; //
    }
    // -------------------- Live Chat COPY -------------------------
    if (isset($_REQUEST["chatkey"])) {
    if (isset($_REQUEST["ltext"])) {
        var_dump($_REQUEST);
        $chat = file_get_contents("../contact/lchat.txt");
        //preg_match("/[^\r\n]*/", $chat, $matches); // bad
        //$matches = implode("", $matches);
        $name = getname($_REQUEST["key"], "admins.txt");
        //$matches = $matches.PHP_EOL.$_REQUEST["key"]."|".$_REQUEST["ltext"]." -/".$name."/s'0";
        file_put_contents("../contact/lchat.txt", $chat . PHP_EOL . $_REQUEST["key"] . "|" . $_REQUEST["ltext"] . " -/" . $name . "/s'0");
        echo '
        <form name="redir" method="post">
        <input type="hidden" name="key" value=' . $_REQUEST["key"] . '>
        <input type="hidden" name="chatkey" value=' . $_REQUEST["chatkey"] . '>
        <input type="hidden" type="submit">
        </form>
        <script type="text/javascript"> // javascript to unset POST when reload
        document.redir.submit();
        </script>
        ';
    }
    $chat = file_get_contents("../contact/lchat.txt");
    preg_match_all("/[^\r\n]*/", $chat, $matches);
    echo '
    <fieldset>
    <legend>Live Chat:</legend>
';
    foreach ($matches[0] as $k => $m) {
        $m = substr(strrchr($m, "|"), 1);
        $m = preg_split("/\/s'0|\/s\!0/", $m)[0];
        if (empty($m)) {
            continue;
        }
        if (!isset($matches[$k + 10])) {
            echo '
    <textarea rows="4" disabled style="width: 99.8%;">
    ' . $m . '</textarea>
    ';
        }
    }
    echo '
        <form method="post" style = "margin: 0.5% 0%;">
        <input type="hidden" name="key" value="' . $_REQUEST["key"] . '">
        <input type="hidden" name="chatkey" value=' . $_REQUEST["chatkey"] . '>
        <input type="text" name="ltext" required style="width: 20%;">
        <input type="submit" value="Send" style="width: 5%;">
        </form>
    </fieldset> ';
    return;
    }
login();
}
// ----------------------------------------------------------------

if ($ver->ver("admins.txt")) {
    //echo "°";
    $key = $ver->vkey("../users.txt");
    $usr = file_get_contents("../users.txt");
    if (isset($_REQUEST["text"])) { // wont happen
        preg_match("/". $key ."\|[^\r\n]*/", $usr, $matches); ///
        $matches = implode("", $matches);
        $matches = preg_replace("/".preg_quote($matches, "/")."/", $matches.$_REQUEST["text"]."\s!0", $usr);
        file_put_contents("chat.txt", $matches);
    }
    preg_match_all("/[^\r\n]+/", $usr, $matches);
    $chat = file_get_contents("../contact/chat.txt");
    foreach ($matches[0] as $m) {
        preg_match("/[^|]*|/", $m, $match);
        $match = getname($match[0]);
        preg_match("/\|[^\r\n]*/", $m, $chk);
        $chk = substr(strrchr($chk[0], "|"), 1);;
        $b = "";
        if (mak($chat, $chk) == 2) {
            $b = 'font-weight: bold;';
        } else if (mak($chat, $chk) == 0) {
            $b = 'color: gray;';
        }
        echo '
        <form method = "post">
        <input type="submit" name="submit" value='.$match.' style="'.$b.'"/>
        <input type="hidden" name="chatkey" value='.$chk.' />
        <input type="hidden" name="key" value='.$_REQUEST["key"].' />
        </form>
        ';
    }

    echo '
    <fieldset>
    <legend>Chat:</legend>
';
    foreach ($matches as $k => $m) { // admin chat >>>
        if (isset($matches[$k - 10])) {
            continue;
        }
        if (empty($m)) {
            continue;
        }
        echo '<textarea rows="4" disabled style="width: 99.8%; font-weight: bold;">';

        $match1 = explode("/big", $m);
        // $match2 = explode("</big>", $m);
        foreach ($match1 as $mat1) {
            echo '</teaxtarea><font size="6">' . $mat1 . '</font>';
        }
        echo '</textarea>';
    }
    echo '
        <form method="post" style = "margin: 0.5% 0%;">
        <input type="hidden" name="key" value="' . $_REQUEST["key"] . '">
        <input type="text" name="text" required style="width: 20%;">
        <input type="submit" value="Send" style="width: 5%;">
        </form>
    </fieldset>
';
    return;
}
function mak($chat, $key) {
    preg_match("/". $key ."\|[^\r\n]*/", $chat, $match);
    $match = implode("", $match);
    $match = substr($match, 0, strlen($match) - 1);
    preg_match("/". preg_quote($match) ."\|[^\r\n]*/", $chat, $matches);
    $matches = implode("", $matches);
    $matches = substr(strrchr($matches, "|"), 1);
    preg_match_all("/(\/s'0*)|(\/s!0*)/", $matches, $ms);
    if (!empty($ms[0])) {
        if (preg_match("/\/s\!0/", $ms[0][count($ms[0]) - 1])) { // no value = undefined offset -1
            return 2;
        }
        return 1;
    }
return 0;
}
// --------------------------------- Login ---------------------------------------
echo '
<style>
input[type=text], input[type=password], input[type=email], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

input[type=submit]:hover {
    background-color: #45a049;
}
form {
    padding-bottom: 30px;
}
div {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding-left: 100px;
    padding-right: 100px;
    font-weight: bold;
    padding-top: 30px;
}
</style> 
<div> ';

login();

echo '
<form method="post">
  <fieldset>
    <legend>Login:</legend>
Username or email:<br>
    <input type="text" required name="name"><br>
Password:<br>
    <input type="password" required name="pass"><br><br>
    <input type="submit" value="Login">
  </fieldset>
</form>
';

function login() {

    if (!isset($_POST['name']) or !isset($_POST['pass'])) {
        return false;
    }
    $content = file_get_contents("admins.txt");
    if (!preg_match("/" . $_POST['name'] . "\//i", $content)) {
        echo "Unknown Username";
        return false;
    }
    if (preg_match("/" . $_POST['name'] . "\/[^|]*/i", $content, $matches)) {
        $matches = implode("", $matches);
        $matches = substr(strrchr($matches, "/"), 1);
        if ($_POST['pass'] != $matches) {
            echo "Wrong password";
            return false;
        }
        preg_match("/" . $_POST['name'] . "\/.*/i", $content, $matches);
        $matches = implode("", $matches);
        $matches = substr(strrchr($matches, "|"), 1);
        echo '
        <form name="redirect" method="post">
        <input type="hidden" name="key" value=' . $matches . '>
        <input type="hidden" type="submit">
        </form>
        <script type="text/javascript">
        document.redirect.submit();
        </script>
        ';
        //header('Location: api/');
        return true;
    }
}