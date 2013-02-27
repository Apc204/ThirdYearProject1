<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';

session_start();
$URLs = getURLs();
Print_r($URLs);
$destination = $_GET['destination'];
$callback = 'http://localhost/3yp/Application/'.$destination.'.php';
//$callback = 'http://localhost/3yp/Application/Auth1.php';
//set up consumer token.
$consumer = new HTTP_OAuth_Consumer($URLs['consumer_key'],$URLs['consumer_secret']);
Print_r($URLs['request']);
echo '<br>';
$consumer->getRequestToken($URLs['request'],$callback,array(),'GET');


//store token and secret in session variables to use on auth2.php
$_SESSION['token'] = $consumer->getToken();
$_SESSION['token_secret'] = $consumer->getTokenSecret();
	
$url = $consumer->getAuthorizeUrl($URLs['authorize']);
	
//echo $_SESSION['token'];
//echo $_SESSION['token_secret'];
echo $url;

		
HTTP::redirect($url);

function getURLs()
{
	$URLs = array();
	
	if ($_GET['destination'] == 'Auth2' || $_GET['destination'] == 'mendeleyExport')
	{
		$URLs['request'] = 'http://api.mendeley.com/oauth/request_token/';
		$URLs['authorize'] = 'http://api.mendeley.com/oauth/authorize/';
		$URLs['consumer_key'] = 'd8e4a5bdaedbd31f6f322437d0a38c1805060529f';
		$URLs['consumer_secret'] = '161a05857f0c8293e067644f01f0d12d';
		echo 'Destination is: '.$_GET['destination'].' setting authorize URL to: mendeley';
		return $URLs;
	}
	else if ($_GET['destination'] == 'zoteroImport' || $_GET['destination'] == 'zoteroExport')
	{
		$URLs['request'] = 'https://www.zotero.org/oauth/request';
		$URLs['authorize'] = 'https://www.zotero.org/oauth/authorize';
		$URLs['consumer_key'] = 'f156f6e3f6e5af097cba';
		$URLs['consumer_secret'] = '7cd46fde66a3443e41f7';
		return $URLs;
	}
	else if ($_GET['destination'] == 'endNoteImport' || $_GET['destination'] == 'endNoteExport')
	{
		return $URLs;
	}
	else
	{
		return $URLs;
	}

}
?>