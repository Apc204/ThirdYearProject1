<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';

session_start();
//Check session variables to determine whether too redirect to import or export mendeley page.
if ($_SESSION['currentOp'] == 'import')
{
	$destination = 'Auth2';
}
else
{	
	$destination = 'mendeleyExport';
}

$callback = 'http://localhost/3yp/Application/'.$destination.'.php';

//set up consumer token.
$consumer = new HTTP_OAuth_Consumer('d8e4a5bdaedbd31f6f322437d0a38c1805060529f','161a05857f0c8293e067644f01f0d12d');
$consumer->getRequestToken('http://api.mendeley.com/oauth/request_token/',$callback,array(),'GET');

//store token and secret in session variables to use on auth2.php
$_SESSION['token'] = $consumer->getToken();
$_SESSION['token_secret'] = $consumer->getTokenSecret();
	
$url = $consumer->getAuthorizeUrl('http://api.mendeley.com/oauth/authorize/');
	
echo $_SESSION['token'];
echo $_SESSION['token_secret'];
	
HTTP::redirect($url);

?>

