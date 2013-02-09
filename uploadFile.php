<?php
session_start();

if (isset($_POST['title']) && !empty($_POST['title']))
{
	//Set type field
	$_POST['type'] = $_GET['type'];
	//Parse fields to get them into correct format.
	parseNames('authors');
	parseNames('editors');
	parseNames('producers');
	parseNames('cast');
	parseArray('keywords');
	parseArray('tags');
	
	//Adds the new document to currentDocs
	$_SESSION['currentDocs'][$_POST['title']] = $_POST;
	$_SESSION['oldPOST'] = $_POST;
	echo 'Document added to library. <br> <legend>Add a file to this document or view library.</legend>';
	
	//Print_r($_SESSION['currentDocs']);
}

//If a file has been uploaded, add it to /uploads/
if (isset($_FILES["file"]) && !empty($_FILES["file"]))
{
	//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	//echo "Type: " . $_FILES["file"]["type"] . "<br>";
	//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	//echo "Stored in: " . $_FILES["file"]["tmp_name"]."<br>";
	$upload = 1; //Boolean set to 0 if conditions are not sufficient for an upload.
	
	$tmp_name = $_FILES["file"]["tmp_name"];
	$uploads_dir = 'uploads/';
	$name = $_FILES["file"]["name"];
	$ext = substr($name, strpos($name,'.'), strlen($name)-1);
	echo $ext;
	echo '<br>'.$name.'<br>';
	//check size and type of file.
	if ($_FILES["file"]["size"]>60000000)
	{
		echo 'File too big';
		$upload = 0;
	}
	if($ext=='.pdf' && $upload==1)
	{
		//Move file, add file location to library array and print out success if the move was successfull.
		if (move_uploaded_file($tmp_name, 'uploads/'.$_FILES['file']['name']))
		{
			Print_r($_SESSION['currentDocs'][$_SESSION['oldPOST']['title']]);
			$_SESSION['currentDocs'][($_SESSION['oldPOST']['title'])]['file'] = $_FILES['file']['name']; // Add filename as 'file' field to document in library.
			echo 'success';
		}
	}
	else
	{
		echo 'Wrong file type.';
	}
}


function parseNames ($listType)
{
	if (isset($_POST[$listType]))
	{
		$authors = array();
		$temp = array();
		$string = $_POST[$listType];
		$temp = explode(',', $string);
		$count=0;
		if(!empty($_POST[$listType]))
		{
			foreach ($temp as $author)
			{
				$authors[$count]['forename'] = explode(' ', trim($author))[0];
				$authors[$count]['surname'] = explode(' ', trim($author))[1];
				$count++;
			}
		}
		$_POST[$listType] = $authors;
	}
}

function parseArray($string)
{
	if (isset($_POST[$string]))
	{
		$array = array();
		$array = explode(",",$_POST[$string]); //explodes on ","
		// Trim spaces off each element in the array
		foreach ($array as $elem)
			$elem = trim($elem);
		$_POST[$string] = $array;	
	}
}



?>

<html>
<body>
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
						<li class><a href="index.php">Home</a></li>
						<li><a href="import.php">Import References</a></li>
						<li class="active"><a href="uploadForm.php">Manually Add Document</a></li>
						<li><a href="export.php">Export Library</a></li>
						<li><a href="library.php">View Library</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

<form action="uploadFile.php" method="post" class="well"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" accept="application/pdf"><br>
<input type="hidden" name="MAX_FILE_SIZE" value="60000000" />
<input type="submit" name="submit" value="Submit">
</form>

<a href="library.php">
<button class="btn" type="button"> View Library </button>
</a>

</body>
</html>