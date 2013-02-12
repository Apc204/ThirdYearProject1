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
</body>
</html>
<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';
session_start();

$consumer = setUp();
$response = sendRequest($consumer, 'https://api.zotero.org/users/1294934/items/');
$docs = $response->getDataFromBody();
Print_r($docs);
//displayCheckboxes($consumer,$docs);



function setUp()
{
	$consumer = new HTTP_OAuth_Consumer('f156f6e3f6e5af097cba','7cd46fde66a3443e41f7', $_SESSION['token'], $_SESSION['token_secret']);
	$consumer->getAccessToken('http://www.zotero.org/support/dev/server_api/read_api',$_GET['oauth_verifier'],array(),'GET');
	//$_SESSION['token'] = $consumer->getToken();
	//$_SESSION['token_secret'] = $consumer->getTokenSecret();
	$_SESSION['consum'] = $consumer;
	return $consumer;
}

function sendRequest($consumer, $url, $method = 'GET')
{
	$response = $consumer->sendRequest($url,array(),$method);
	return $response;
}

function displayCheckboxes($consumer, $docs)
{
	$count = 0;
	//Pick out a list of document ID's from the response object.
	foreach ($docs as $elem)
	{
		$_SESSION['docs'] = explode(",",array_keys($elem)[0]);
	}

	echo '<legend>Choose documents to import:<legend><br>';
	echo '<form action="Auth2.php" method="GET" class="well">';
	//Find all details for each document.
	foreach ($_SESSION['docs'] as $ID)
	{
		echo '<br>';
		$url = "http://api.mendeley.com/oapi/library/documents/$ID/";
		$url2 = str_replace("\"","",$url);

		$response = $consumer->sendRequest($url2,array(),'GET');
		$details = $response->getResponse();
		$string = $details->getBody();
		$_SESSION['jsonArray'] = json_decode($string, TRUE);
		$_SESSION[$ID] = json_decode($string, TRUE);

		
		
		//Display name of document along with a download button, if a file exists to download.
		$choice = 'choices'.$count;
		$choice = str_replace("\"","",$choice);
		$temp = '<label class="checkbox">'.$_SESSION['jsonArray']['title'].'<input type="checkbox" name='.$choice.' value='.$ID.'></label>';
		echo $temp;
		//If a file hash exists, print a download button to download the file.
		if(isset($_SESSION['jsonArray']['files'][0]['file_hash']))
		{
			$urlID = 'MDownload.php?ID='.$ID;
			$urlID = str_replace('"', '',$urlID);
			//echo htmlentities('<a href="'.$urlID.'">');
			echo '<a href="'.$urlID.'">	<input type="Button" value="Download"> </a><br>';
		}		
		$count++;
		echo '';
	}
	echo '<input type="submit" value="Submit" class="btn btn-primary"><br></form>';
}

?>