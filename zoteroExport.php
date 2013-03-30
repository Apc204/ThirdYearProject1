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


if (isset($_SESSION['set2']) && !empty($_SESSION['set2']))
{
	unset($_SESSION['set2']);
	$consumer = clone $_SESSION['consum'];

	//$json = getTemplate('book', $consumer);
	// Set up a HTTP request ready to add the document.
	$params = array(
				'oauth_consumer_key'     => $consumer->getKey(),
				'oauth_signature_method' => $consumer->getSignatureMethod()
			);
	if ($consumer->getToken()) {
				$params['oauth_token'] = $consumer->getToken();
			}
	//$params = array_merge($additional, $params);

	$req = clone $consumer->getOAuthConsumerRequest();
	$req->setUrl('https://api.zotero.org/users/1294934/items?key=1iYXhQ3BaKGOzTly106cLiSx');
	$req->setMethod('POST');
	$req->setSecrets($req->getSecrets());
	$req->setParameters($params);
	$req->buildRequest();
	$request = $req->getHTTPRequest2();
	//$request->setHeader('content-type', 'application/json');
	$request->setHeader('Zotero-API-Version','2');

	foreach($_GET as $doc) // Loop through the documents currently in the library, set the request body and add the document.
	{
		$document = $_SESSION['currentDocs'][str_replace(' ','_',$doc)];
		$json = getJSON($document, $consumer);
		unset($json['relations']);
		$request->setBody('{ "items" : ['.json_encode($json).']}');
		//$request->setBody($additional['document']);
		//Print_r($request->getBody());
		$response = $request->send();
		//Print_r($response);
		
	}
	echo '<br><br>Documents Added.<br>';
	echo '<a href="index.php"><input type="Button" class="btn" value="Back"> </a>';
}
else
{
	$_SESSION['set2'] = 'set2';
	displayCheckboxes();
	$consumer = setUp();
	$_SESSION['consum'] = clone $consumer;
}

function displayCheckboxes()
{
	$count = 0;
	echo '<form action="zoteroExport.php" method="GET" class="well">';
	foreach($_SESSION['currentDocs'] as $doc)
	{	
		$choice = 'choice'.$count;
		if ($doc['type']!='Generic' && $doc['type']!='Working Paper')
		{
			echo '<label class="checkbox">'.$doc['title'].'<input type="checkbox" name="'.$choice.'" value="'.$doc['title'].'"></label>';
		}
		$count++;
	}
	echo '<input type="submit" value="Submit" class="btn btn-primary"><br></form>';
}

function getJSON($doc, $consumer)
{

	if($doc['type'] == 'Book')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('book', $consumer)),true);
		parseBook($doc);
	}
	else if ($doc['type'] == 'Bill')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('bill', $consumer)),true);		
		parseBill($doc);
	}
	else if ($doc['type'] == 'Book Section')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('bookSection', $consumer)), true);
		Print_r($_SESSION['tempDoc']);
		parseBookSection($doc);
		echo '<br>Parsed:<br>';
		Print_r($_SESSION['tempDoc']);
	}
	else if ($doc['type'] == 'Case')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('case', $consumer)),true);
		parseCase($doc);
	}
	else if ($doc['type'] == 'Computer Program')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('computerProgram', $consumer)),true);
		parseComputerProgram($doc);
	}
	else if ($doc['type'] == 'Conference Proceedings')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('conferencePaper', $consumer)),true);
		parseConferenceProceedings($doc);
	}
	else if($doc['type'] == 'Encyclopedia Article')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('encyclopediaArticle', $consumer)),true);
		parseEncyclopediaArticle($doc);
	}
	else if ($doc['type'] == 'Film')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('film', $consumer)),true);
		parseFilm($doc);
	}
	else if ($doc['type'] == 'Generic')
	{
		echo 'Could not import '.$doc['title'].', Generic type not supported in Zotero.';
	}
	else if ($doc['type'] == 'Hearing')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('hearing', $consumer)),true);
		parseHearing($doc);
	}
	else if ($doc['type'] == 'Journal Article')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('journalArticle', $consumer)),true);
		parseJournalArticle($doc);
	}
	else if ($doc['type'] == 'Magazine Article')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('magazineArticle', $consumer)),true);
		parseMagazineArticle($doc);
	}
	else if ($doc['type'] == 'Newspaper Article')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('newspaperArticle', $consumer)),true);
		parseNewspaperArticle($doc);
	}
	else if ($doc['type'] == 'Patent')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('patent', $consumer)),true);
		parsePatent($doc);
	}
	else if ($doc['type'] == 'Report')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('report', $consumer)),true);
		parseReport($doc);
	}
	else if ($doc['type'] == 'Statute')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('statute', $consumer)),true);
		parseStatute($doc);
	}
	else if ($doc['type'] == 'Television Broadcast')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('tvBroadcast', $consumer)),true);
		parseTelevisionBroadcast($doc);
	}
	else if ($doc['type'] == 'Thesis')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('thesis', $consumer)),true);
		parseThesis($doc);
	}
	else if ($doc['type'] == 'Web Page')
	{
		$_SESSION['tempDoc'] = json_decode(json_encode(getTemplate('webPage', $consumer)),true);
		parseWebPage($doc);
	}
	else if ($doc['type'] == 'Working Paper')
	{
		echo 'Could not import '.$doc.', Working Paper\'s not supported in Zotero.';
	}
	return $_SESSION['tempDoc'];
}

function getTemplate($string, $consumer)
{
	// Set up a HTTP request ready to add the document.
	$params = array(
				'oauth_consumer_key'     => $consumer->getKey(),
				'oauth_signature_method' => $consumer->getSignatureMethod()
			);
	if ($consumer->getToken()) {
				$params['oauth_token'] = $consumer->getToken();
			}


	$req = clone $consumer->getOAuthConsumerRequest();

	$req->setUrl('https://api.zotero.org/items/new/?itemType='.$string);
	//$req->setUrl('https://api.zotero.org/itemTypes');
	$req->setMethod('GET');
	$req->setSecrets($req->getSecrets());
	$req->setParameters($params);
	$req->buildRequest();
	$request = $req->getHTTPRequest2();
	$request->setHeader('Zotero-API-Version','2');
	$response = $request->send();
	//Print_r($response->getBody());
	return json_decode($response->getBody());
	//$response = $consumer->sendRequest('https://api.zotero.org/users/1294934/items/new?itemType=book&key=1iYXhQ3BaKGOzTly106cLiSx',array(),'GET');
	//Print_r($response->getBody());
}

function parseWebPage($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('dateAccessed', $doc, 'accessDate');
	convertField('publication', $doc, 'websiteTitle');
}

function parseThesis($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('institution', $doc, 'university');
	convertField('pages', $doc, 'pages');
	convertField('userType', $doc, 'type');
}

function parseTelevisionBroadcast($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'director');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('country', $doc, 'place' );
	convertField('length', $doc, 'runningTime');	
}

function parseStatute($doc)
{
	convertField('title', $doc, 'nameOfAct');
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc, 'dateEnacted');
	convertField('code', $doc);
	convertField('codeNumber', $doc);
	convertField('pages', $doc, 'pages');
	convertField('publicLawNumber', $doc, 'publicLawNumber');
	convertField('revisionNumber', $doc, 'history');
}

function parseReport($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('city', $doc);
	convertField('pages', $doc, 'pages');
	convertField('institution', $doc, 'institution');
}

function parsePatent($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'inventor');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc, 'filingDate');
	convertField('pages', $doc, 'pages');
	convertField('revisionNumber',  $doc, 'patentNumber');
	convertField('institution',$doc,'issuingAuthority');
	convertField('owner',$doc,'assignee');
}

function parseNewspaperArticle($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('city', $doc);
	convertField('pages', $doc, 'pages');
	convertField('original_publication', $doc, 'publicationTitle');
}

function parseMagazineArticle($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('pages',$doc, 'pages');
	convertField('original_publication', $doc, 'publicationTitle');
}

function parseJournalArticle($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'contributor');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('issue', $doc);
	convertField('pages', $doc, 'pages');
	convertField('original_publication', $doc, 'publicationTitle');
	convertField('volume', $doc, 'volume');
}

function parseHearing($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'contributor');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('city', $doc);
	convertField('comitee', $doc);
	convertField('pages', $doc, 'pages');
	convertField('publisher', $doc);
}

function parseFilm($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'director');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('length', $doc);
	convertField('producer', $doc, 'distributor');
}

function parseEncyclopediaArticle($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('edition', $doc);
	convertField('originalPublication', $doc, 'encyclopediaTitle');
	convertField('publisher', $doc);
	convertField('seriesNumber', $doc);
}

function parseConferenceProceedings($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('city', $doc);
	convertField('pages', $doc, 'pages');
	convertField('publisher', $doc);
}

function parseComputerProgram($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'programmer');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('city', $doc);
	convertField('revisionNumber', $doc);
}

function parseCase($doc)
{
	convertField('title', $doc, 'caseName');
	convertNames('authors', $doc, 'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc, 'dateDecided');
	convertField('volume', $doc);
}

function parseBill($doc)
{
	convertField('title', $doc);
	convertNames('authors', $doc, 'sponsor');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('code', $doc);
	convertField('codeVolume', $doc);	
}

function parseBook($doc)
{	
	convertField('title', $doc);
	convertNames('authors', $doc, 'author');
	//convertArray('keywords', $doc);
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	convertField('city', $doc);
	convertField('edition', $doc);
	//convertArray('editors', $doc);
	convertField('pages', $doc);
	convertField('publisher', $doc);
}

function parseBookSection($doc)
{
	convertField('title',$doc);
	convertNames('authors', $doc,'author');
	convertArray('tags', $doc);
	convertField('website', $doc);
	convertField('year', $doc);
	//convertField('chapter', $doc);
	convertField('city', $doc);
	convertField('edition', $doc);
	convertField('originalPublication',$doc);
	convertField('publisher', $doc);
}

function convertField($field, $doc, $forcefield='')
{
	$respectiveField = getNewField($field, $forcefield);
	if (isset($doc[$field]) && !empty($doc[$field]))
	{
		$_SESSION['tempDoc'][$respectiveField] = $doc[$field];
	}
}

function convertArray($field, $doc)
{
	$respectiveField = getNewField($field);
	if (isset($doc[$field]) && !empty($doc[$field]))
	{
		$index = 0;
		foreach($doc[$field] as $elem)
		{
			$_SESSION['tempDoc'][$respectiveField][$index]['tag'] = $elem;
			$index++;
		}
	}
}

function convertNames($field, $doc, $type)
{
	$respectiveField = getNewField($field);
	if (isset($doc[$field]) && !empty($doc[$field]))
	{
		$index = 0;
		foreach ($doc[$field] as $author)
		{
			$_SESSION['tempDoc'][$respectiveField][$index]['creatorType'] = $type;
			$_SESSION['tempDoc'][$respectiveField][$index]['firstName'] = $doc[$field][$index]['forename'];
			$_SESSION['tempDoc'][$respectiveField][$index]['lastName'] = $doc[$field][$index]['surname'];
			$index++;
		}
		
	}
}

function getNewField($field, $forcefield = '')
{
	if ($forcefield != '')
		return $forcefield;
	$newfield = '';
	if ($field == 'authors')
		return $newfield = 'creators';
	else if ($field == 'tags')
		return $newfield = 'tags';
	else if ($field == 'website')
		return $newfield = 'url';
	else if ($field == 'city')
		return $newfield = 'place';
	else if ($field == 'year')
		return $newfield = 'date';
	else if ($field == 'title')
		return $newfield = 'title';
	else if ($field == 'edition')
		return $newfield = 'edition';
	else if ($field == 'pages')
		return $newfield = 'numPages';
	else if ($field == 'publisher')
		return $newfield = 'publisher';
	else if ($field == 'originalPublication')
		return $newfield = 'bookTitle';
	else if ($field == 'code')
		return $newfield = 'code';
	else if ($field == 'codeVolume')
		return $newfield = 'codeVolume';
	else if ($field == 'codeNumber')
		return $newfield = 'codeNumber';
	else if ($field == 'volume')
		return $newfield = 'reporterVolume';
	else if ($field == 'revisionNumber')
		return $newfield = 'version';
	else if ($field == 'seriesNumber')
		return $newfield = 'seriesNumber';
	else if ($field == 'length')
		return $newfield = 'runningTime';
	else if ($field == 'comitee')
		return $newfield = 'comitee';
	else if ($field == 'issue')
		return $newfield = 'issue';
	else if ($field == 'publication')
		return $newfield = 'publication';
	return $newfield;
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

