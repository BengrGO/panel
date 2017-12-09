<?php

require("verify.php");
$ver = new Verify();
if ($ver->ver()) {
echo '
<style>
<body> {
}
form {
padding: 3% 0%;
margin: 0% 5%;
margin-right: 1%;
}
input {
border-radius: 4px;
border: 1px solid #ccc;
padding: 6px 10px;
width: 55%;
margin-right: 4%;
}
input[type=submit] {
background-color: #4CAF50;
color: white;
font-weight: bold;
width: 94%;
padding: 12px;
}
input[type=submit]:hover {
background-color: #45a049;
}
fieldset {
font-weight: bold;
background-color: #f2f2f2;
border-radius: 6px;
border: 2px solid #ccc;
margin-bottom: 2%;
}
td {
padding: 6px;
border-bottom: 1px solid black;
}
</style>
<body>
<div style="margin: 4% auto; width: 90%;">
  <fieldset>
    <legend>Add order:</legend>
    <form action="../api/rest/">
        API key:<br>
        <input type="text" name="key" value='.$_REQUEST["key"].'><input type="text" value="Your api key" disabled style="width:35%;"><br>
        url:<br>
        <input type="text" name="url"><input type="text" value="URL to send order to - Make sure it\'s correct!" disabled style="width:35%;"><br>
        quantity:<br>
        <input type="text" name="quantity"><input type="text" value="Quantity that you want" disabled style="width:35%;"><br>
        ID:<br>
        <input type="text" name="id"><input type="text" value="ID of service that you want" disabled style="width:35%;"><br><br>
        <input type="submit" value="Submit">
    </form>
  </fieldset>

  <fieldset>
    <legend>Services:</legend>
    <table style="border-collapse:collapse;">
    <thead style="font-weight: bold;">
        <td>ID</td><td>Service</td><td>Price per 1000</td><td>Min order</td><td>Max order</td><td>Description</td>
    </thead>
    <tbody>';
    require("services.php");
    $serv = new Services();
    $sv = $serv->services;
    foreach ($sv as $k => $s) {
        echo '<tr></tr>';
        echo '<tr style="text-align: center;">';
        echo '<td> '.$k.'</td ><td >'.$serv->info[$k][0].'</td ><td >'.number_format($s[3], 2, '.', ' ').'</td ><td >'.$s[4].'</td ><td >'.number_format($s[5], 0, '.', ' ').'</td ><td >'.$serv->info[$k][1].'</td >';
        echo '</tr>';
        }
echo'
    </tbody>
    </table>
  </fieldset>
<form method="post" style="display: inline; margin: 0px; padding: 0px; width: 0px;" action="api/">
<input class="mg" type="submit" style="padding: 10px; width: 80px; margin-left: 34%;" value="API"/>
</form>
<form method="post" style="display: inline; margin: 0px; padding: 0px; width: 0px; " action="contact/">
<input type="hidden" name="key" value=' . $_REQUEST["key"] . '>
<input type="submit" style="padding: 10px; width: 80px; margin-left: 2%;" value="Contact";/>
</form>
<form method="post" style="display: inline; margin: 0px; padding: 0px; width: 0px; " action="balance/">
<input type="hidden" name="key" value=' . $_REQUEST["key"] . '>
<input type="submit" style="padding: 10px; width: 80px; margin-left: 2%;" value="Balance";/>
</form>
  </div>
</body>

<style>
@media(max-width: 600px) {
input[disabled] {
display: none;
}
input {
width: 100%;
}
}
</style>
';
return;
}

require("getkey.php");
$gk = new GetKey(); // Login / Register