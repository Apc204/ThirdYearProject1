<?php
session_start();



if (isset($_POST['title']) && !empty($_POST['title']))
{
	//Parse fields to get them into correct format.
	parseAuthors();
	//Adds the new document to currentDocs
	$_SESSION['currentDocs'][$_POST['title']] = $_POST;
	
	echo 'Document added to library <br> Add a file to this document or view library.';
	
	//Print_r($_SESSION['currentDocs']);
}

//If a file has been uploaded, add it to /uploads/
if (isset($_FILES["file"]) && !empty($_FILES["file"]))
{
	echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	echo "Type: " . $_FILES["file"]["type"] . "<br>";
	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	echo "Stored in: " . $_FILES["file"]["tmp_name"];
	
	$tmp_name = $_FILES["file"]["tmp_name"];
	$uploads_dir = 'uploads/';
	$name = $_FILES["file"]["name"];
	echo '<br>'.$name.'<br>';
	if (move_uploaded_file($tmp_name, 'uploads/'.$_FILES['file']['name']))
	{
		$_SESSION['currentDocs'][$_POST['title']]['file'] = $_FILES['file']['name'];
		echo 'success';
	}
}


function parseAuthors ()
{
	if (isset($_POST['authors']) && !empty($_POST['authors']))
	{
		$authors = array();
		$temp = array();
		$string = $_POST['authors'];
		$temp = explode(',', $string);
		$count=0;
		foreach ($temp as $author)
		{
			$authors[$count]['Firstname'] = explode(' ', trim($author))[0];
			$authors[$count]['Surname'] = explode(' ', trim($author))[1];
			$count++;
		}
		$_POST['authors'] = $authors;
	}
}



?>

<html>
<body>

<form action="uploadBibtex.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>

</body>
</html>