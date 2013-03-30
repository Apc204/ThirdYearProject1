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
						<li><a href="export.php">Export Library</a></li>
						<li class="active"><a href="library.php">View Library</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<?php

session_start();

if(isset($_GET['clear']) && !empty($_GET['clear']))
{
	if ($_GET['clear'] == 'true')
		$_SESSION['currentDocs'] = array();
}
//$_SESSION['currentDocs'] = array();
if (isset($_SESSION['currentDocs']) && !empty($_SESSION['currentDocs']))
{
	//Print_r($_SESSION['currentDocs']);
	echo '<legend>Documents currently in library:</legend>';
	foreach ($_SESSION['currentDocs'] as $array)
	{
		if (isset($array['title']))
			$title = $array['title'];
		else if (isset($array['casename']))
			$title = $array['caseName'];
		else if (isset($array['nameOfAct']))
			$title = $array['nameOfAct'];
		else if (isset($array['casename']))
			$title = $array['caseName'];
		else
			Print_r($array);
		$url = 'showDetails.php?title='.str_replace(' ','_', $title);
		$url = str_replace('"','',$url);
		//echo $title['title'].'<form action="'.$url.'">
			//					<input type="submit" value="View Details"></form>';
		echo '<b>'.$title.'</b>&nbsp;<a href="'.$url.'"><input type="Button" class="btn" value="Show Details"> </a><br><br>';
	} // echo title and button taking user to "showDetails.php"
}

//Print_r($_SESSION['currentDocs']);
echo '<a href="library.php?clear=true"><input type="Button" class="btn" value="Clear Library"> </a>';
?>