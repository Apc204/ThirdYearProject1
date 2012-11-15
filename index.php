<?php

session_start();
if (isset($_SESSION['currentDocs']) && !empty($_SESSION['currentDocs']))
{
	echo '<h2>Documents currently in library:</h2><br>';
	foreach ($_SESSION['currentDocs'] as $title)
	{
		echo $title['title'].'<br>';
	}
}


?>

<html lang="en">
<head>
</head>
<body>

<h2>Import more documents, or export to your chosen software.</h2><br>
<a href="import.php">
	<input type="Button" value="Import">
</a>

<a href="localhost/3yp/Application/export.php">
	<input type="Button" value="Export">
</a>


</body>