<?php
require_once '/HTTP/Request2.php';
require_once '/HTTP/OAuth.php';
require_once '/HTTP/OAuth/Consumer.php';
require_once 'HTTP.php';

session_start();

foreach ($_GET as $var)
{
	$consumer2 = $_SESSION['consum'];
	$url = 'http://api.mendeley.com/oapi/library/documents/'.$var.'/file/'.$_SESSION['"'.$var.'"']['files'][0]['file_hash'].'/';
	//Set headers to make php download the file.
	$title = str_replace(" ","_",$_SESSION['"'.$var.'"']['title']);
	header('Content-disposition: attachment; filename='.$title.'.pdf');
	header('Content-type: application/pdf');
	$response2 = $consumer2->sendRequest($url,array(),'GET');
	$file = $response2->getResponse();
	$file = $file->getBody();
	echo $file;
}




?>