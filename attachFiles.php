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
</body>
</html>
<?php

session_start();

Print_r($_SESSION['filesUploading']);



?>

