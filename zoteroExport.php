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
						<li><a href="import.php">Import References</a></li>
						<li><a href="uploadForm.php">Manually Add Document</a></li>
						<li class="active"><a href="export.php">Export Library</a></li>
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

$consumer = setUp();
//$additional['content-type'] = 'application/json';
//$additional['Zotero-API-Version'] = 2;

// Set up a HTTP request ready to add the document.
$params = array(
            'oauth_consumer_key'     => $consumer->getKey(),
            'oauth_signature_method' => $consumer->getSignatureMethod()
        );
if ($consumer->getToken()) {
            $params['oauth_token'] = $consumer->getToken();
        }
$params = array_merge($additional, $params);

$req = clone $consumer->getOAuthConsumerRequest();

$req->setUrl('https://api.zotero.org/users/1294934/items?key=1iYXhQ3BaKGOzTly106cLiSx');
$req->setMethod('POST');
$req->setSecrets($req->getSecrets());
$req->setParameters($params);
$req->buildRequest();
$request = $req->getHTTPRequest2();
$request->setHeader('content-type', 'application/json');
$request->setHeader('Zotero-API-Version','2');

foreach($_SESSION['currentDocs'] as $doc) // Loop through the documents currently in the library, set the request body and add the document.
{
	$json = getJSON($doc);
	$request->setBody($additional['document']);
	Print_r($request->getBody());
	$response = $request->send();
	Print_r($response);
}

$additional['document'] = '{
  "items" : [
    {
      "itemType" : "book",
      "title" : "My Book",
      "creators" : [
        {
          "creatorType":"author",
          "firstName" : "Sam",
          "lastName" : "McAuthor"
        },
        {
          "creatorType":"editor",
          "name" : "John T. Singlefield"
        }
      ],
      "tags" : [
        { "tag" : "awesome" },
        { "tag" : "rad", "type" : 1 }
      ],
      "collections" : [
       
      ],
      "relations" : {
        "owl:sameAs" : "http://zotero.org/groups/1/items/JKLM6543",
        "dc:relation" : "http://zotero.org/groups/1/items/PQRS6789",
        "dc:replaces" : "http://zotero.org/users/1/items/BCDE5432"
      }
    }
  ]
}';

/*$request = new HTTPRequest2('https://api.zotero.org/users/1294934/items?key=1iYXhQ3BaKGOzTly106cLiSx');
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setHeader('content-type', 'application/json');
$request->setHeader('');*/



//$response = $consumer->sendRequest('https://api.zotero.org/users/1294934/items?key=1iYXhQ3BaKGOzTly106cLiSx',$additional,'POST');
//Print_r($response);


function getJSON($doc)
{
	if($doc['type'] == 'Book')
		return parseBook($doc);
	else if ($doc['type'] == 'Bill')
		return parseBill($doc);
	else if ($doc['type'] == 'Book Section')
		return parseBookSection($doc);
	else if ($doc['type'] == 'Case')
		return parseCase($doc);
	else if ($doc['type'] == 'Computer Program')
		return parseComputerProgram($doc);
	else if ($doc['type'] == 'Conference Proceedings')
		return parseConferenceProceedings($doc);
	else if($doc['type'] == 'Encyclopedia Article')
		return parseEncyclopediaArticle($doc);
	else if ($doc['type'] == 'Film')
		return parseFilm($doc);
	else if ($doc['type'] == 'Generic')
		return parseGeneric($doc);
	else if ($doc['type'] == 'Hearing')
		return parseHearing($doc);
	else if ($doc['type'] == 'Journal Article')
		return parseJournalArticle($doc);
	else if ($doc['type'] == 'Magazine Article')
		return parseMagazineArticle($doc);
	else if ($doc['type'] == 'Newspaper Article')
		return parseNewspaperArticle($doc);
	else if ($doc['type'] == 'Patent')
		return parsePatent($doc);
	else if ($doc['type'] == 'Report')
		return parseReport($doc);
	else if ($doc['type'] == 'Statute')
		return parseStatute($doc);
	else if ($doc['type'] == 'Television Broadcast')
		return parseTelevisionBroadcast($doc);
	else if ($doc['type'] == 'Thesis')
		return parseThesis($doc);
	else if ($doc['type'] == 'Web Page')
		return parseWebPage($doc);
	else if ($doc['type'] == 'Working Paper')
		return parseWorkingPaper($doc);
}

function parseBook($doc)
{	
	
}



function setUp()
{
	$consumer = new HTTP_OAuth_Consumer('f156f6e3f6e5af097cba','7cd46fde66a3443e41f7', $_SESSION['token'], $_SESSION['token_secret']);
	$consumer->getAccessToken('https://www.zotero.org/oauth/access',$_GET['oauth_verifier'],array(),'GET');
	//$_SESSION['token'] = $consumer->getToken();
	//$_SESSION['token_secret'] = $consumer->getTokenSecret();
	$_SESSION['consum'] = clone $consumer;
	return $consumer;
}

?>

