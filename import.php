<?php

if (isset($_GET['software']) && !empty($_GET['software']))
{
	switch ($_GET['software'])
	{
		case 'Zotero':
			$redirectpage = 'zoteroImport.php';
			break;
		case 'Mendeley':
			$redirectpage = 'Auth1.php';
			break;
		case 'EndNote':
			$redirectpage = 'endNoteImport.php';
			break;
		case 'Refworks':
			$redirectpage = 'refworksImport.php';
			break;
	}
	header('Location: '.$redirectpage);
}



?>

<html lang="en">
<head>
</head>
<body>

<h1>Chose software to import from:</h1><br>

<form action="import.php" METHOD="GET">
	<select name="software">
	<option value="Zotero">Zotero</option>
	<option value="Mendeley">Mendeley</option>
	<option value="EndNote">EndNote</option>
	<option value="Refworks">Refworks</option>
	</select>
	
	<input type="submit" value="Submit">
<form>

</body>