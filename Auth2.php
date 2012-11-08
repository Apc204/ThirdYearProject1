<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';
require_once 'mendeleyImport.php';

session_start();
$count = 0;
$_SESSION['docs'] = array();

echo '*******************************<br>';

//Set up consumer to authorise user-specific requests.
$consumer = new HTTP_OAuth_Consumer('d8e4a5bdaedbd31f6f322437d0a38c1805060529f','161a05857f0c8293e067644f01f0d12d', $_SESSION['token'], $_SESSION['token_secret']);
$consumer->getAccessToken('http://api.mendeley.com/oauth/access_token/',$_GET['oauth_verifier'],array(),'GET');
$_SESSION['token'] = $consumer->getToken();
$_SESSION['token_secret'] = $consumer->getTokenSecret();

//Send the request for documents in the user's library.
$response = $consumer->sendRequest('http://api.mendeley.com/oapi/library/',array(),'GET');
$docs = $response->getDataFromBody();

//Pick out a list of document ID's from the response object.
foreach ($docs as $elem)
{
	$_SESSION['docs'] = explode(",",array_keys($elem)[0]);
}

echo '<h1>Chose documents to import:</h1><br>';
echo '<form action="mendeleyImport.php" method="GET">';
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
	
	//Display name of document along with a checkbox and a dropdown box for user to chose whether to download the file or just details.
	$choice = 'choices'.$count;
	$choice = str_replace("\"","",$choice);
	$temp = '<b>'.$_SESSION['jsonArray']['title'].'</b> <input type="checkbox" name='.$choice.' value='.$ID.'>,';
	echo $temp;
	echo '<select name='.$ID.'>
	<option value="Details"> Document Details </option>
	<option value ="Details"> Document Details and Download File </option><br>
	</select>';
	
	$count++;
	echo '<br><br>';
}
echo '<input type="submit" value="Submit"><br></form>';

function BetweenStr($InputString, $StartStr, $EndStr=0, $StartLoc=0) 
{
	if (($StartLoc = strpos($InputString, $StartStr, $StartLoc)) === false) { return; }
	$StartLoc += strlen($StartStr);
	if (!$EndStr) { $EndStr = $StartStr; }
	if (!$EndLoc = strpos($InputString, $EndStr, $StartLoc)) { return; }
	return substr($InputString, $StartLoc, ($EndLoc-$StartLoc));
} 

?>
