<?php
     
     include_once("header.php");
?>

<html>
<head>
<title>Data Collection Dashboard</title>
</head>
<body>
<meta http-equiv="refresh" content="60">
<table style="border:8px solid #7851a9;margin-left:auto;margin-right:auto;">
<tr><td colspan="3">
<h1 align="center" color="#800080">Data Collection Dashboard</h1>
</td></tr>
<tr><td>
<iframe width="450" height="460" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1421598/charts/1?api_key=9LNDC0AW53QBGGI9"></iframe>
</td>
<td><iframe width="450" height="460" style="border: 1px solid #cccccc;" src="https://thingspeak.com/apps/matlab_visualizations/422160"></iframe>
</td>
<td><iframe width="450" height="460" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1421598/widgets/350464"></iframe>
</td></tr>
</html>
