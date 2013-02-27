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

if(!(isset($_GET['oauth_token']) && !empty($_GET['oauth_token'])))
{
	addDocuments();
}
else
{
	$consumer = setUp();
	$response = $consumer->sendRequest('https://api.zotero.org/users/1294934/items?key=1iYXhQ3BaKGOzTly106cLiSx&format=atom&content=json',array(),'GET');
	$docs = $response->getDataFromBody();
	displayCheckboxes($consumer,$docs);
}



function setUp()
{
	$consumer = new HTTP_OAuth_Consumer('f156f6e3f6e5af097cba','7cd46fde66a3443e41f7', $_SESSION['token'], $_SESSION['token_secret']);
	$consumer->getAccessToken('https://www.zotero.org/oauth/access',$_GET['oauth_verifier'],array(),'GET');
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
	$array = array();
	echo '<legend>Choose documents to import:<legend><br>';
	echo '<form action="zoteroImport.php" method="GET" class="well">';
	foreach ($docs as $elem)
	{
		$choice = 'choices'.$count;
		$choice = str_replace("\"","",$choice);
		$elem = substr($elem, strpos($elem, '{'));
		$newStr = getJSON($elem);
		foreach ($newStr as $json)
		{
			if ($json != '')
			{
				//echo '<br> Array:<br>';
				$array = json_decode($json, TRUE);
				//Print_r($array);
				$_SESSION['zotDocs'][str_replace(" ","_",$array['title'])] = $array;
				echo '<label class="checkbox">'.$array['title'].'<input type="checkbox" name='.$choice.' value='.str_replace(" ","_",$array['title']).'></label>';
				$count++;
			}
		}
	}
	echo '<input type="submit" value="Submit" class="btn btn-primary"><br></form>';
}

function getJSON ($string)
{
	$current = 0;
	$open = 0;
	$close = 0;
	$newStr = '';
	while ($current < strlen($string))
	{
		if ($open != $close || ($open == 0 && $close == 0) || $string[$current] == '{')
		{
			$newStr = $newStr.$string[$current];
			$printed = false;
		}
		else
		{
			if ($printed == false)
			{
				$newStr = $newStr.'#';
				$printed = true;
			}
		}		
		if ($string[$current] == '{')
			$open++;
		if ($string[$current] == '}')
			$close++;
		$current++;
	}
	$jsonArray = explode("#", $newStr);
	return $jsonArray;
}

function addDocuments ()
{
	Print_r($_GET);
	foreach ($_GET as $doc)
	{
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'book')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Book';
			addSingleField('title', $doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);	
			addSingleField('url', $doc);
			addSingleField('place', $doc);
			addSingleField('date', $doc);
			addSingleField('edition', $doc);
			addSingleField('publisher', $doc);
			addSingleField('numPages', $doc);
		}
	}
}

function addSingleField ($field, $doc)
{
	$respectiveField = getNewField($field, $doc);
	if(isset($_SESSION['zotDocs'][$doc][$field]) && !empty($_SESSION['zotDocs'][$doc][$field]))
		$_SESSION['currentDocs'][$doc][$respectiveField] = $_SESSION['zotDocs'][$doc][$field];
}

function addNames ($field, $doc)
{
	$respectiveField = getNewField($field, $doc);
	if (isset($_SESSION['zotDocs'][$doc][$field]) && !empty($_SESSION['zotDocs'][$doc][$field]))
	{
		$index = 0;
		foreach ($_SESSION['zotDocs'][$doc][$field] as $author)
		{
			$_SESSION['currentDocs'][$doc][$respectiveField][$index]['forename'] = $_SESSION['zotDocs'][$doc][$field][$index]['firstName'];
			$_SESSION['currentDocs'][$doc][$respectiveField][$index]['surname'] = $_SESSION['zotDocs'][$doc][$field][$index]['lastName'];
			$index++;
		}
	}
}

function addArrayField ($field, $doc)
{
	$respectiveField = getNewField($field);
	if (isset($_SESSION['zotDocs'][$doc][$field]) && !empty($_SESSION['zotDocs'][$doc][$field]))
	{
		$index = 0;
		foreach ($_SESSION['zotDocs'][$doc][$field] as $tag)
		{
			$_SESSION['currentDocs'][$doc][$respectiveField][$index] = $tag['tag'];
			$index++;
		}
	}
}

function getNewField($field)
{
	$newfield = '';
	if ($field == 'creators')
		$newfield = 'authors';
	if ($field == 'tags')
		$newfield = 'tags';
	if ($field == 'url')
		$newfield = 'website';
	if ($field == 'place')
		$newfield = 'city';
	if ($field == 'date')
		$newfield = 'date';
	if ($field == 'title')
		$newfield = 'title';
	if ($field == 'edition')
		$newfield = 'edition';
	if ($field == 'numPages')
		$newfield = 'pages';
	if ($field == 'publisher')
		$newfield = 'publisher';
	return $newfield;
}

?>