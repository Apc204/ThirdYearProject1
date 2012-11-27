<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';

session_start();

$bool = true;
$_SESSION['docs'] = array();
//echo '*******************************<br>';


if (!(isset($_GET['oauth_token']) && !empty($_GET['oauth_token'])))
{
	Print_r($_SESSION['currentDocs'][]);
	foreach ($_GET as $doc)
	{
		foreach ($_SESSION['currentDocs'][] as $one)
		{
			if ($one['title'] == '"'.$doc.'"')
			{
				$bool=false;
			}
		}
		if ($bool = true)
		{
			echo 'Added '.$_SESSION['"'.$doc.'"'];
			$_SESSION['currentDocs'][] = $_SESSION['"'.$doc.'"'];
		}
		//HTTP::redirect('library.php');
	}
}
else
{
	unset($_SESSION['currentDocs']);
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
	$_SESSION['token'] = $consumer->getToken();
	$_SESSION['token_secret'] = $consumer->getTokenSecret();
	$_SESSION['consum'] = $consumer;
	return $consumer;
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

	echo '<h1>Chose documents to import:</h1><br>';
	echo '<form action="Auth2.php" method="GET">';
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
		$temp = '<b>'.$_SESSION['jsonArray']['title'].'</b> <input type="checkbox" name='.$choice.' value='.$ID.'>,';
		echo $temp;
		//If a file hash exists, print a download button to download the file.
		if(isset($_SESSION['jsonArray']['files'][0]['file_hash']))
		{
			$urlID = 'MDownload.php?ID='.$ID;
			$urlID = str_replace('"', '',$urlID);
			echo htmlentities('<a href="'.$urlID.'">');
			echo '<a href="'.$urlID.'">	<input type="Button" value="Download"> </a><br>';
		}		
		$count++;
		echo '<br><br>';
	}
	echo '<input type="submit" value="Submit"><br></form>';
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
