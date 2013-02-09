<?php

session_start();
$_SESSION['currentDocs'] = array();
/*foreach ($_SESSION['currentDocs'] as $doc)
{
	$_SESSION['currentDocs'][$doc['title']]['type'] = 'Film';
	echo 'added type<br>';
}*/



?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
	<link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>
<body>
	<script src="js/bootstrap.js"></script>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="index.php"> Reference Manager</a>
				<div class="nav-collapse">
					<ul class="nav">
						<li class="active"><a href="index.php">Home</a></li>
						<li><a href="import.php">Import References</a></li>
						<li><a href="uploadForm.php">Manually Add Document</a></li>
						<li><a href="export.php">Export Library</a></li>
						<li><a href="library.php">View Library</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="hero-unit">
		<h1> Welcome! </h1><br>
		<p> This web application is designed to help you keep your references organised. Here's some information to get you started:<br><br>
			<b>Import References:</b> Clicking "Import References" on the navigation bar above will allow you to import references into your library from several well-known
			referencing software packages.<br><br>
			<b>Manually Add Document:</b> Another way to populate your library is to add a document manually. You will be able to add a document's details to your library
			and will have the option of attaching a file to your new document.<br><br>
			<b>Export Library:</b> At any time you have the option of exporting the contents of your library to any of the supported software packages, making 
			it easy to keep all of your references in one place!
</body>
</html>