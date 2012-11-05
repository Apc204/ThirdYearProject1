<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';

	session_start();
	echo $_SESSION['set'];
	echo '<br>';

	echo '*******************************';
	$consumer = new HTTP_OAuth_Consumer('d8e4a5bdaedbd31f6f322437d0a38c1805060529f','161a05857f0c8293e067644f01f0d12d', $_SESSION['token'], $_SESSION['token_secret']);
	$consumer->getAccessToken('http://api.mendeley.com/oauth/access_token/',$_GET['oauth_verifier'],array(),'GET');
			
	$_SESSION['token'] = $consumer->getToken();
	$_SESSION['token_secret'] = $consumer->getTokenSecret();

		
	$response = $consumer->sendRequest('http://api.mendeley.com/oapi/library/',array(),'GET');

	$docs = $response->getDataFromBody();
		
	$_SESSION['docs'] = array();

	foreach ($docs as $elem)
	{
		$_SESSION['docs'] = explode(",",array_keys($elem)[0]);
		//Print_r($_SESSION['docs']);
	}

	
	echo '<form action="mendeleyImport.php" method="GET">';
	
	foreach ($_SESSION['docs'] as $ID)
	{
		echo '<br>';
		$url = "http://api.mendeley.com/oapi/library/documents/$ID/";
		$url2 = str_replace("\"","",$url);
		$count = 0;

		$response = $consumer->sendRequest($url2,array(),'GET');
		$details = $response->getResponse();
		$string = $details->getBody();
		$_SESSION['jsonArray'] = json_decode($string, TRUE);
		
		echo htmlentities($_SESSION['jsonArray']['title'].'<input type="checkbox" name="choices" value="'.$ID.'">');
		$choice = 'choices'.$count;
		$choice = str_replace("\"","",$choice);
		$temp = $_SESSION['jsonArray']['title'].'<input type="checkbox" name='.$choice.' value='.$ID.'>';
		$count++;
		echo $temp;
		echo '<br>*******************************<br>';
		//$string = BetweenStr($string, '[body:protected] => ','[bodyEncoded:protected]');
		//echo '<br> DOCUMENT DETAILS <BR>';
		//Print_r($string);
		//echo $ID.'<input type="checkbox" name="ID" value=\"'.$ID.'\"><br><br><br>';
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
