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

$_SESSION['docs'] = array();
$consumer = setUp();
$additional = array();
//$additional['document'] =  '{"type":"Film","title":"Godfather"}';
//$additional[] = json_encode($_SESSION['currentDocs']['The Machinist']);
//Print_r($additional);

foreach ($_SESSION['currentDocs'] as $doc => $details)
{
	$additional['document'] = $details;
	unset($additional['document']['submit']);
	unset($additional['document']['Submit']);
	unset($additional['document']['file']);
	unset($additional['document']['File']);
	$additional['document'] = /*str_replace(" ","%20",*/json_encode($additional['document']);
	$response = $consumer->sendRequest('http://api.mendeley.com/oapi/library/documents/', $additional , 'POST');
	$docs = $response->getDataFromBody();
	$result = json_decode(array_keys($docs)[0],'true');
	//Copy the current document into 'filesUploading' and add the returned ID as a field.
	$_SESSION['filesUploading'] = array();
	$_SESSION['filesUploading'][$doc] = $details;
	$_SESSION['filesUploading'][$doc]['id'] = $result['document_id'];
	//echo $docs[0]['document_id'];
	Print_r($result);
}

echo '<br><br>Documents Added.<br>';
echo '<a href="index.php"><input type="Button" class="btn" value="Back"> </a>';


//Set up consumer to authorise user-specific requests.
function setUp()
{
	$consumer = new HTTP_OAuth_Consumer('d8e4a5bdaedbd31f6f322437d0a38c1805060529f','161a05857f0c8293e067644f01f0d12d', $_SESSION['token'], $_SESSION['token_secret']);
	$consumer->getAccessToken('http://api.mendeley.com/oauth/access_token/',$_GET['oauth_verifier'],array(),'GET');
	//$_SESSION['token'] = $consumer->getToken();
	//$_SESSION['token_secret'] = $consumer->getTokenSecret();
	$_SESSION['consum'] = clone($consumer); //copies the consumer variable to a session variable.
	return $consumer;
	
}

//Send the request to specified url
//function sendRequest($consumer, $url, $method = 'GET')
//{
	//$response = $consumer->sendRequest($url,array(),$method);
	//$response = $consumer->sendRequest('http://api.mendeley.com/oapi/library/',array(),'GET');
	//return $response;
	
//}


 

?>
