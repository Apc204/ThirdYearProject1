<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';


//if (isset($_POST['key']) && !empty($_POST['key']))
//{

	session_start();
	$_SESSION['set'] = 'no';
	$callback = 'http://localhost/3yp/Application/Auth2.php';
	//$request = new pear\HTTP\Request('http://api.mendeley.com/oauth/request_token/');
	$consumer = new HTTP_OAuth_Consumer('d8e4a5bdaedbd31f6f322437d0a38c1805060529f','161a05857f0c8293e067644f01f0d12d');
	$consumer->getRequestToken('http://api.mendeley.com/oauth/request_token/',$callback,array(),'GET');
	
	$_SESSION['token'] = $consumer->getToken();
	$_SESSION['token_secret'] = $consumer->getTokenSecret();
	
	$url = $consumer->getAuthorizeUrl('http://api.mendeley.com/oauth/authorize/');
	
	echo $_SESSION['token'];
	echo $_SESSION['token_secret'];
	
	HTTP::redirect($url);
	
	
	//$consumer = new HTTP_OAuth_Consumer('','', $_SESSION['token'], $_SESSION['token_secret']);
	//$consumer->getAccessToken('http://api.mendeley.com/oauth/access_token/');
	
	//$_SESSION['token'] = $consumer->getToken();
	//$_SESSION['token_secret'] = $consumer->getTokenSecret();
	
	//$response = $consumer->sendRequest('http://api.mendeley.com/oapi/library/');
//}

?>

<form action="Auth1.php" METHOD="POST">
Enter Key:	<input type="text" name="key"> <br>
	<input type="submit" value="Submit"><br>
</form>

