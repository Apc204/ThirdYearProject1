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
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';
session_start();

$consumer = $_SESSION['consum'];
if (isset($_GET['filesToUpload']) && !empty($_GET['filesToUpload']))
{
	foreach ($_GET as $file)
	{
		//Print_r($_SESSION['currentDocs']);
		$title = '\''.$file.'\'';
		$id = $_SESSION['filesUploading'][$file]['id'];
		Print_r($id);
		$filename = $_SESSION['filesUploading'][$file]['file'];
		$consumer = clone($_SESSION['consum']);
		$additional = array();
		//$additional['id'] = $id;
		$hash = sha1('\''.$filename.'\'');
		//$additional['Content-Disposition'] = 'attachment; filename="'.$filename.'"';
		$additional['body_hash'] = $hash;
		$response = $consumer->sendRequest('http://www.mendeley.com/oapi/library/documents/'.$id.'/', $additional ,'PUT');
		//$consumReq = clone $consumer->getOAuthConsumerRequest();
		//$authType = $consumReq->getAuthForHeader();
		//Print_r($authType);
		//$token = $consumer->getToken();
		//$authString = 'OAuth realm:"",oauth_body_hash="'.$hash.'", oauth_version="1.0", oauth_token="'.$token.'", oauth_nonce=""';
		//echo 'test';
		/*
		$http_options = array('timeout' => '10');
		$http_request = new HTTP_Request2('http://www.mendeley.com/oapi/library/documents/'.$id.'/', HTTP_Request2::METHOD_PUT, $http_options);
		$http_request->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"');
		$http_request->setHeader('Authorization', $authorization_header);
		$http_request->setBody($user_message);*/

		//echo $httpReq->getHeaders();
		$output = $response->getDataFromBody();
		Print_r($output); 
	}
}
else
{
	// for each file currently being exported.
	foreach ($_SESSION['filesUploading'] as $file)
	{
		// if the file has a file attached to it in the library.
		if(isset($file['file']) && !empty($file['file']))
		{
			// add a text box to tick if you want that file uploaded to mendeley.
			echo '<form action="attachFiles.php" class="well" method="GET">';
			echo '<label class="checkbox">'.$file['title'].'<input type="checkbox" name="filesToUpload" value="'.str_replace(" ","_",$file['title']).'"></label>';
		}
	}
	echo '<input type="submit" value="Submit" class="btn btn-primary"></form>';
}


?>

