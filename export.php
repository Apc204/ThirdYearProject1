<?php
session_start();

$_SESSION['currentOp'] = 'export';
unset($_SESSION['set']);
unset($_SESSION['set2']);

if (isset($_GET['software']) && !empty($_GET['software']))
{
	switch ($_GET['software'])
	{
		case 'Zotero':
			$redirectpage = 'Auth1.php?destination=zoteroExport';
			break;
		case 'Mendeley':
			$redirectpage = 'Auth1.php?destination=mendeleyExport';
			break;
		case 'EndNote':
			$redirectpage = 'endNoteExport.php';
			break;
		case 'Refworks':
			$redirectpage = 'refworksExport.php';
			break;
		case 'Bibtex':
			$redirectpage = 'exportBibtex.php';
			break;
	}
	header('Location: '.$redirectpage);
}



?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
	<link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>
<body>
	<style type="text/css">
		body {
			padding-left: 30px;
			padding-right: 30px;
			padding-top: 60px;
			padding-bottom: 40px;
			}
	</style>
	<script src="js/bootstrap.js"></script>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="index.php"> Reference Manager</a>
				<div class="nav-collapse">
					<ul class="nav">
						<li><a href="index.php">Home</a></li>
						<li><a href="import.php">Import References</a></li>
						<li><a href="uploadForm.php">Manually Add Document</a></li>
						<li class="active"><a href="export.php">Export Library</a></li>
						<li><a href="library.php">View Library</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<legend>Choose software to export to:</legend><br>

	<form action="export.php" METHOD="GET" class="well">
		<select name="software">
		<option value="Zotero">Zotero</option>
		<option value="Mendeley">Mendeley</option>
		<option value="Refworks">BibTeX</option>
		</select><br>
		
		<input type="submit" value="Submit" class="btn btn-primary">
	<form>

</body>