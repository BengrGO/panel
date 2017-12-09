<?php

//******************* Class for Login / Registration *********************
class GetKey {
    public function __construct($ext = "", $action = ".") {

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
@media(max-width: 700px) {
div {
padding: 20px;
}
}
</style> 
<div> ';

        $this->login($ext, $action);

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

        if (isset($_POST['newname'])) {
            if ($this->register($ext)) {
                echo "<font color='black'>You have successfully registered! </font><font color='darkgreen'>No email confirmation needed!</font>";
            }
        }

//if (isset($_POST['name'])) {
//    login(); }

        $newname = "";
        $email = "";
        if (isset($_POST['newname'])) {
            $newname = $_POST['newname'];
        }
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        }
        echo '
<form method="post">
  <fieldset>
    <legend>Registration:</legend>
Username:<br>
    <input type="text" name="newname" required value=' . $newname . '><br>
Email:<br>
    <input type="email" name="email" required value=' . $email . '><br>
Password: <div style="padding: 0px 40%; float: right;">Verify password:</div><br>
    <input type="password" name="pass" required style="width: 49.8%; float: left;"><input type="password" name="verifypass" required style="width: 49.8%; float: right;"><br><br>
    <input type="submit" value="Register">
  </fieldset>
</form>
';

//var_dump($_POST);

    } // End of public function

    private function login($ext, $action) {

        if (!isset($_POST['name']) or !isset($_POST['pass'])) {
            return false;
        }
        $content = file_get_contents($ext . "users.txt");
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
        <form name="redirect" action='. $action .' method="post">
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
        return false;
    }

    private function register($ext) {

        if (strlen($_POST['newname']) < 3) {
            echo "Username is too short.";
            return false;
        }
        if (strlen($_POST['email']) < 6 or strpos($_POST['email'], "@") === false or strpos($_POST['email'], ".") === false) {
            echo "Please enter correct email.";
            return false;
        }
        if (strlen($_POST['pass']) < 3) {
            echo "Password is too short.";
            return false;
        }
        if ($_POST['pass'] != $_POST['verifypass']) {
            echo "Passwords do not match.";
            return false;
        }

        $content = file_get_contents($ext . "users.txt");

        if (preg_match("/" . $_POST['newname'] . "\//i", $content)) {
            echo "Username already registered.";
            return false;
        }
        if (preg_match("/" . $_POST['email'] . "\//i", $content)) {
            echo "Email already registered.";
            return false;
        }

        $key = $this->randstr();
        $content .= $_POST['newname'] . "/" . $_POST['email'] . "/" . $_POST['pass'] . "|" . $key . PHP_EOL;
        file_put_contents($ext . "users.txt", $content);
        $content = file_get_contents($ext . "balance.txt");
        file_put_contents($ext . "balance.txt", $content . $key . ":0" . PHP_EOL);
        $content = file_get_contents($ext . "ids.txt");
        file_put_contents($ext . "ids.txt", $content . $key . "/" . PHP_EOL);
        $content = file_get_contents("contact/chat.txt");
        file_put_contents("contact/chat.txt", $content . $key . "|" . PHP_EOL); // someone may use | in chat
        return true;
    }

    private function randstr($length = 30) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz-*[]@';
        $charactersLength = strlen($chars);
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $randstr .= $chars[rand(0, $charactersLength - 1)];
        }
        return $randstr;
    }
}