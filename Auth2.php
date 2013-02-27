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

$bool = true;
$_SESSION['docs'] = array();
//echo '*******************************<br>';
// removed: cast
$allowedFields = explode(",",'abstract,type,authors,city,country,date,editors,isbn,issn,isRead,isStarred,isAuthor,keywords,notes,pmid,,publisher,revision,tags,title,translators,website,year');
if (!(isset($_GET['oauth_token']) && !empty($_GET['oauth_token'])))
{
	foreach ($_GET as $doc)
	{
		$bool = true;
		foreach ($_SESSION['currentDocs'] as $one)
		{
			if ($one['title'] == $_SESSION['"'.$doc.'"']['title'])
			{
				echo 'Document already in library';
				$bool = false;
			}
		}
		if ($bool == true)
		{
			//Add document to current library.
			echo 'Added '.$_SESSION['"'.$doc.'"']['title'];
			foreach ($_SESSION['"'.$doc.'"'] as $index => $value) 
			{
				// Only add allowed details to be added to library
				if (in_array($index, $allowedFields))
				{
					$_SESSION['currentDocs'][$_SESSION['"'.$doc.'"']['title']][$index] = $value;
				}
				//$_SESSION['currentDocs'][$_SESSION['"'.$doc.'"']['title']] = $_SESSION['"'.$doc.'"'];
				/*parseNames('authors', $doc);
				parseNames('editors', $doc);
				parseNames('producers', $doc);
				parseNames('cast', $doc);
				parseArray('keywords', $doc);
				parseArray('tags', $doc);
				parseNames('producers', $doc);*/
			}	
		}		
	}
	HTTP::redirect('library.php');
}
else
{
	//unset($_SESSION['currentDocs']);
	$consumer = setUp();
	$response = sendRequest($consumer, 'http://api.mendeley.com/oapi/library/');
	$docs = $response->getDataFromBody();
	displayCheckboxes($consumer,$docs);
}


//Set up consumer to authorise user-specific requests.
function setUp()
{
	$consumer = new HTTP_OAuth_Consumer('d8e4a5bdaedbd31f6f322437d0a38c1805060529f','161a05857f0c8293e067644f01f0d12d', $_SESSION['token'], $_SESSION['token_secret']);
	$consumer->getAccessToken('http://api.mendeley.com/oauth/access_token/',$_GET['oauth_verifier'],array(),'GET');
	//$_SESSION['token'] = $consumer->getToken();
	//$_SESSION['token_secret'] = $consumer->getTokenSecret();
	$_SESSION['consum'] = $consumer;
	return $consumer;
}

function parseNames ($string, $document)
{
	if (isset($_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string]) && !empty($_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string]))
	{
		$authors = array();
		$temp = array();
		$string = $_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string];
		if(gettype($string) == 'string') //check that it is in string format and not already an array
		{
			$temp = explode(',', $string);
			$count=0;
			foreach ($temp as $author)
			{
				$authors[$count]['forename'] = explode(' ', trim($author))[0];
				$authors[$count]['surname'] = explode(' ', trim($author))[1];
				$count++;
			}
			$_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string] = $authors;
		}
	}
}

function parseArray($string, $document)
{
	if (isset($_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string]) && !empty($_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string]) && gettype($_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string]) == 'string')
	{
		$array = array();
		$array = explode(",",$_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string]); //explodes on ","
		// Trim spaces off each element in the array
		foreach ($array as $elem)
			$elem = trim($elem);
		$_SESSION['currentDocs'][$_SESSION['"'.$document.'"']['title']][$string] = $array;	
	}
}

//Send the request to specified url
function sendRequest($consumer, $url, $method = 'GET')
{
	$response = $consumer->sendRequest($url,array(),$method);
	//$response = $consumer->sendRequest('http://api.mendeley.com/oapi/library/',array(),'GET');
	return $response;
	//$docs = $response->getDataFromBody();
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
		//echo 'array index '.$ID.' = ';
		//Print_r($_SESSION[$ID]);
		
		
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


function BetweenStr($InputString, $StartStr, $EndStr=0, $StartLoc=0) 
{
	if (($StartLoc = strpos($InputString, $StartStr, $StartLoc)) === false) { return; }
	$StartLoc += strlen($StartStr);
	if (!$EndStr) { $EndStr = $StartStr; }
	if (!$EndLoc = strpos($InputString, $EndStr, $StartLoc)) { return; }
	return substr($InputString, $StartLoc, ($EndLoc-$StartLoc));
} 

?>
