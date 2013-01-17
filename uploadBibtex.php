<?php
session_start();

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
		echo 'success';
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