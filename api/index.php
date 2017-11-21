<?php

echo '
<H2>API Documentation</H2>
<table>
<tr>
<td>HTTP Method</td><td>POST, GET</td>
</tr>
<tr>
<td>API url</td><td>https://...</td>
</tr>
</table>



<H3>Add order</H3>
<table>
<tr>
<td>key</td><td>-Your API key</td>
</tr>
<tr>
<td>url</td><td>-URL of your targeted page</td>
</tr>
<tr>
<td>quantity</td><td>-Quantity that you want</td>
</tr>
</table>
<H3>Order status</H3>
<table>
<tr>
<td>key</td><td>-Your API key</td>
</tr>
<tr>
<td>status</td><td>Order id</td>
</tr>
</table>
<H3>Your balance</H3>
<table>
<tr>
<td>key</td><td>-Your API key</td>
</tr>
<tr>
<td>balance</td><td>-1</td>
</tr>
</table>
<H3>Last X order IDs</H3>
<table>
<tr>
<td>key</td><td>-Your API key</td>
</tr>
<tr>
<td>ids</td><td>-Number of order IDs</td>
</tr>
</table>
<H5>You can add req=1 to var_dump($_REQUEST)</H5>
';


// Debug - echo post