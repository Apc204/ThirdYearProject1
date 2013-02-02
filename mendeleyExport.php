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
$additional[] =  '{"type":"Film","title":"The Machinist","authors":"","keywords":""}';
//$additional[] = json_encode($_SESSION['currentDocs']['The Machinist']);
Print_r($additional);
echo'<br>';
$response = $consumer->sendRequest('http://api.mendeley.com/oapi/library/documents/', $additional , 'POST');
$docs = $response->getDataFromBody();
Print_r($docs);



//Set up consumer to authorise user-specific requests.
function setUp()
{
	echo 'Set up correctly1';
	$consumer = new HTTP_OAuth_Consumer('d8e4a5bdaedbd31f6f322437d0a38c1805060529f','161a05857f0c8293e067644f01f0d12d', $_SESSION['token'], $_SESSION['token_secret']);
	$consumer->getAccessToken('http://api.mendeley.com/oauth/access_token/',$_GET['oauth_verifier'],array(),'GET');
	$_SESSION['token'] = $consumer->getToken();
	$_SESSION['token_secret'] = $consumer->getTokenSecret();
	$_SESSION['consum'] = $consumer; //copies the consumer variable to a session variable.
	echo 'Set up correctly2';
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
