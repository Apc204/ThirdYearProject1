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


//$additional['document'] =  '{"type":"Film","title":"Godfather"}';
//$additional[] = json_encode($_SESSION['currentDocs']['The Machinist']);
//Print_r($additional);

if (isset($_SESSION['set']) && !empty($_SESSION['set']))
{
	$_SESSION['docs'] = array();
	$consumer = clone $_SESSION['consum'];
	$additional = array();
	unset($_SESSION['set']);
	foreach ($_GET as $doc)
	{
		$additional['document'] = $_SESSION['currentDocs'][str_replace(' ','_',$doc)];
		unset($additional['document']['submit']);
		unset($additional['document']['Submit']);
		unset($additional['document']['file']);
		unset($additional['document']['File']);
		$additional['document'] = /*str_replace(" ","%20",*/json_encode($additional['document']);
		$response = $consumer->sendRequest('http://api.mendeley.com/oapi/library/documents/', $additional , 'POST');
		$docs = $response->getDataFromBody();
		$result = json_decode(array_keys($docs)[0],'true');
		//Copy the current document into 'filesUploading' and add the returned ID as a field.
		//$_SESSION['filesUploading'] = array();
		//$_SESSION['filesUploading'][$doc] = $_SESSION['currentDocs'][$doc];
		//$_SESSION['filesUploading'][$doc]['id'] = $result['document_id'];
		//echo $docs[0]['document_id'];
		Print_r($result);
	}
	echo '<br><br>Documents Added.<br>';
	echo '<a href="index.php"><input type="Button" class="btn" value="Back"> </a>';
}
else
{
	$consumer = setUp();
	$_SESSION['consum'] = clone $consumer;
	echo '<legend> Chose documents to upload to Mendley:</legend>';
	displayCheckboxes();
	$_SESSION['set'] = 'set';
}

function displayCheckboxes()
{
	$count = 0;
	echo '<form action="mendeleyExport.php" method="GET" class="well">';
	foreach($_SESSION['currentDocs'] as $doc)
	{	
		$choice = 'choice'.$count;
		echo '<label class="checkbox">'.$doc['title'].'<input type="checkbox" name="'.$choice.'" value="'.$doc['title'].'"></label>';
		$count++;
	}
	echo '<input type="submit" value="Submit" class="btn btn-primary"><br></form>';
}

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
