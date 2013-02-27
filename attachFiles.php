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
		$id = $_SESSION['filesUploading'][$file]['id']; // finds ID of current file in the 'for loop'
		//Print_r($id);
		$filename = $_SESSION['filesUploading'][$file]['file'];
		$consumer = clone($_SESSION['consum']);
		$additional = array();
		//$additional['id'] = $id;
		$hash = sha1('\''.$filename.'\'');
		$content = "attachment; filename=\"$filename\"";
		
		$params = array(
            'oauth_consumer_key'     => $consumer->getKey(),
            'oauth_signature_method' => $consumer->getSignatureMethod()
        );
		
		if ($consumer->getToken()) {
					$params['oauth_token'] = $consumer->getToken();
				}
		$params['oauth_body_hash'] = $hash;
		$params = array_merge($additional, $params);

		$req = clone $consumer->getOAuthConsumerRequest();

		$req->setUrl('http://www.mendeley.com/oapi/library/documents/'.$id.'/');
		$req->setMethod('PUT');
		$req->setSecrets($req->getSecrets());
		$req->setParameters($params);
		$req->buildRequest();
		$request = $req->getHTTPRequest2();
		$request->setHeader('Content-Disposition', $content);
		$request->setBody("Data: $id");
		$response = $request->send();
		echo'<br>RESPONSE:<br>';
		Print_r($response);
		
		
		//$additional['Content-Disposition'] = $content;
		//$additional['content-type'] = 'application/x-www-form-urlencoded';
		//$response = $consumer->sendRequest('http://www.mendeley.com/oapi/library/documents/'.$id.'/', $additional ,'PUT');
		
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

