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
}
</style>
<body>
<div style="margin: 4% auto; width: 90%;">
  <fieldset>
    <legend>Add order:</legend>
    <form action="../api/rest/">
        API key:<br>
        <input type="text" name="key"><input type="text" value="Your api key" disabled style="width:35%;"><br>
        url:<br>
        <input type="text" name="url"><input type="text" value="URL to send order to." disabled style="width:35%;"><br>
        quantity:<br>
        <input type="text" name="quantity"><input type="text" value="Quantity that you want" disabled style="width:35%;"><br><br>
        <input type="submit" value="Submit">
    </form>
  </fieldset>

  <fieldset>
    <legend>Services:</legend>
    <table>
    <thead style="font-weight: bold;">
        <th>
            <td>ID</td><td>Service</td><td>Price per 1000</td><td>Min order</td><td>Max order</td>
        </th>
    </thead>
    <tbody>
    <tr>
        <td>1</td><td></td><td></td><td></td><td></td>
    </tr>
    </tbody>
    </table>
  </fieldset>
  </div>
</body>
';
return;
}

require("getkey.php");
$gk = new GetKey(); // Login / Register