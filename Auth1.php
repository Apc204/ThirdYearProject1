<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';

session_start();

$URLs = getURLs();
$destination = $_GET['destination'];
$callback = 'http://localhost/3yp/Application/'.$destination.'.php';

//set up consumer token.
$consumer = new HTTP_OAuth_Consumer('d8e4a5bdaedbd31f6f322437d0a38c1805060529f','161a05857f0c8293e067644f01f0d12d');
$consumer->getRequestToken($URLs['request'],$callback,array(),'GET');

//store token and secret in session variables to use on auth2.php
$_SESSION['token'] = $consumer->getToken();
$_SESSION['token_secret'] = $consumer->getTokenSecret();
	
$url = $consumer->getAuthorizeUrl($URLs['authorize']);
	
echo $_SESSION['token'];
echo $_SESSION['token_secret'];
			
HTTP::redirect($url);

function getURLs()
{
	$URLs = array();
	switch ($_GET['destination'])
	{
		case 'Auth2' :
		{
			$URLs['request'] = 'http://api.mendeley.com/oauth/request_token/';
			$URLs['authorize'] = 'http://api.mendeley.com/oauth/authorize/';
			return $URLs; break;
		}
		case 'zoteroImport' :
		{
			$URLs['request'] = 'https://www.zotero.org/oauth/request';
			$URLs['authorize'] = 'https://www.zotero.org/oauth/authorize';
			return $URLs; break;
		}
		case 'endNoteImport':
		{
			return $URLs; break;
		}
		case 'refworksImport':
		{
			return $URLs; break;
		}
		
	}
}
?>