<?php
session_start();

$_SESSION['currentOp'] = 'import';

if (isset($_GET['software']) && !empty($_GET['software']))
{
	switch ($_GET['software'])
	{
		case 'Zotero':
			$redirectpage = 'Auth1.php?destination=zoteroImport';
			break;
		case 'Mendeley':
			$redirectpage = 'Auth1.php?destination=Auth2';
			break;
		case 'BibTeX':
			$redirectpage = 'parseBibtex.php';
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
						<li class="active"><a href="import.php">Import References</a></li>
						<li><a href="uploadForm.php">Manually Add Document</a></li>
						<li><a href="export.php">Export Library</a></li>
						<li><a href="library.php">View Library</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<legend>Choose software from which to import:</legend><br>

	<form action="import.php" METHOD="GET" class="well">
		<select name="software">
		<option value="Zotero">Zotero</option>
		<option value="Mendeley">Mendeley</option>
		<option value="BibTeX">BibTeX</option>
		</select><br>
		
		<input type="submit" value="Submit" class="btn btn-primary">
	</form>

</body>