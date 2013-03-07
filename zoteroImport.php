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
						<li class="active"><a href="import.php">Import References</a></li>
						<li><a href="uploadForm.php">Manually Add Document</a></li>
						<li><a href="export.php">Export Library</a></li>
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

if(!(isset($_GET['oauth_token']) && !empty($_GET['oauth_token'])))
{
	addDocuments();
}
else
{
	$consumer = setUp();
	$response = $consumer->sendRequest('https://api.zotero.org/users/1294934/items?key=1iYXhQ3BaKGOzTly106cLiSx&format=atom&content=json',array(),'GET');
	$docs = $response->getDataFromBody();
	displayCheckboxes($consumer,$docs);
}



function setUp()
{
	$consumer = new HTTP_OAuth_Consumer('f156f6e3f6e5af097cba','7cd46fde66a3443e41f7', $_SESSION['token'], $_SESSION['token_secret']);
	$consumer->getAccessToken('https://www.zotero.org/oauth/access',$_GET['oauth_verifier'],array(),'GET');
	//$_SESSION['token'] = $consumer->getToken();
	//$_SESSION['token_secret'] = $consumer->getTokenSecret();
	$_SESSION['consum'] = $consumer;
	return $consumer;
}

function sendRequest($consumer, $url, $method = 'GET')
{
	$response = $consumer->sendRequest($url,array(),$method);
	return $response;
}

function displayCheckboxes($consumer, $docs)
{
	$count = 0;
	$array = array();
	echo '<legend>Choose documents to import:<legend><br>';
	echo '<form action="zoteroImport.php" method="GET" class="well">';
	foreach ($docs as $elem)
	{
		
		$elem = substr($elem, strpos($elem, '{'));
		$newStr = getJSON($elem);
		foreach ($newStr as $json)
		{
			if ($json != '')
			{
				$choice = 'choices'.$count;
				$choice = str_replace("\"","",$choice);
				//echo '<br> Array:<br>';
				$array = json_decode($json, TRUE);
				//Print_r($array);
				
				if (isset($array['title']))
					$title = $array['title'];
				else if (isset($array['casename']))
					$title = $array['caseName'];
				else if (isset($array['nameOfAct']))
					$title = $array['nameOfAct'];
				else if (isset($array['casename']))
					$title = $array['caseName'];
				$_SESSION['zotDocs'][str_replace(" ","_",$title)] = $array;
				echo '<label class="checkbox">'.$title.'<input type="checkbox" name='.$choice.' value='.str_replace(" ","_",$title).'></label>';
				$count++;
			}
		}
	}
	echo '<input type="submit" value="Submit" class="btn btn-primary"><br></form>';
}

function getJSON ($string)
{
	$current = 0;
	$open = 0;
	$close = 0;
	$newStr = '';
	while ($current < strlen($string))
	{
		if ($open != $close || ($open == 0 && $close == 0) || $string[$current] == '{')
		{
			$newStr = $newStr.$string[$current];
			$printed = false;
		}
		else
		{
			if ($printed == false)
			{
				$newStr = $newStr.'#';
				$printed = true;
			}
		}		
		if ($string[$current] == '{')
			$open++;
		if ($string[$current] == '}')
			$close++;
		$current++;
	}
	$jsonArray = explode("#", $newStr);
	return $jsonArray;
}

function addDocuments ()
{
	Print_r($_GET);
	foreach ($_GET as $doc)
	{
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'book')
		{
			Print_r($_SESSION['zotDocs'][$doc]);
			$_SESSION['currentDocs'][$doc]['type'] = 'Book';
			addSingleField('title', $doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);	
			addSingleField('url', $doc);
			addSingleField('place', $doc);
			addSingleField('date', $doc);
			addSingleField('edition', $doc);
			addSingleField('publisher', $doc);
			addSingleField('numPages', $doc);
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'bill')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Bill';
			addSingleField('title', $doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('code', $doc);
			addSingleField('codeVolume', $doc);
			addSingleField('pages', $doc);
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'bookSection')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Book Section';
			addSingleField('title', $doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date', $doc, 'year');
			addSingleField('place',$doc,'city');
			addSingleField('edition',$doc);
			addSingleField('bookTitle',$doc, 'originalPublication');
			addSingleField('publisher',$doc);
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'case')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Case';
			addSingleField('title', $doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date', $doc, 'year');
			addSingleField('court',$doc, 'counsel');
			addSingleField('reporterVolume',$doc, 'volume');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'computerProgram')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Computer Program';
			addSingleField('title', $doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date', $doc, 'year');
			addSingleField('place', $doc, 'city');
			addSingleField('company', $doc, 'publisher');
			addSingleField('version', $doc, 'revisionNumber');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'conferenceProceedings')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Conference Proceedings';
			addSingleField('title', $doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date', $doc, 'year');
			addSingleField('place', $doc, 'city');
			addSingleField('pages', $doc, 'city');
			addSingleField('conferenceName', $doc, 'originalPublication');
			addSingleField('publisher', $doc, 'publisher');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'encyclopediaArticle')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Encyclopedia Article';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date', $doc, 'year');
			addSingleField('edition',$doc);
			addSingleField('encyclopediaTitle',$doc,'originalPublication');
			addSingleField('publisher', $doc, 'publisher');
			addSingleField('seriesNumber',$doc,'seriesNumber');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'film')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Film';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date', $doc, 'year');
			addSingleField('distributor',$doc, 'publisher');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'hearing')
		{
			Print_r($_SESSION['zotDocs'][$doc]);
			$_SESSION['currentDocs'][$doc]['type'] = 'Hearing';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date',$doc,'year');
			addSingleField('place',$doc,'city');
			addSingleField('committee',$doc,'committee');
			addSingleField('pages',$doc,'pages');
			addSingleField('publisher',$doc,'publisher');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'journalArticle')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Journal Article';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date',$doc,'year');
			addSingleField('issue',$doc,'issue');
			addSingleField('pages',$doc,'pages');
			addSingleField('publication',$doc,'publication');
			addSingleField('volume',$doc,'volume');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'magazineArticle')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Magazine Article';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date',$doc,'year');
			addSingleField('pages',$doc,'pages');
			addSingleField('publication',$doc,'originalPublication');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'newspaperArticle')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Newspaper Article';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date',$doc,'year');
			addSingleField('place', $doc,'city');
			addSingleField('pages',$doc,'pages');
			addSingleField('publication',$doc,'originalPublication');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'patent')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Patent';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('issueDate',$doc,'year');
			addSingleField('place', $doc,'country');
			addSingleField('issuingAuthority',$doc,'institution');
			addSingleField('pages',$doc,'pages');
			addSingleField('assignee', $doc,'owner');
			addSingleField('patentNumber',$doc,'revisionNumber');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'report')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Report';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date',$doc,'year');
			addSingleField('place',$doc,'city');
			addSingleField('institution',$doc,'institution');
			addSingleField('pages',$doc,'pages');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'statute')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Statute';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('dateEnacted',$doc,'year');
			addSingleField('code',$doc, 'code');
			addSingleField('codeNumber', $doc, 'codeNumber');
			addSingleField('pages',$doc,'pages');
			addSingleField('publicLawNumber', $doc, 'publicLawNumber');
			addSingleField('history',$doc,'history');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'tvBroadcast')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Television Broadcast';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date',$doc,'year');
			addSingleField('place',$doc,'country');
			addSingleField('length',$doc,'runningTime');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'thesis')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Thesis';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date',$doc,'year');
			addSingleField('university',$doc,'institution');
			addSingleField('pages',$doc,'pages');
			addSingleField('type',$doc,'userType');
		}
		if($_SESSION['zotDocs'][$doc]['itemType'] == 'webPage')
		{
			$_SESSION['currentDocs'][$doc]['type'] = 'Web Page';
			addSingleField('title',$doc);
			addNames('creators', $doc);
			addArrayField('tags', $doc);
			addSingleField('url', $doc);
			addSingleField('date',$doc,'year');
			addSingleField('accessDate',$doc,'dateAccessed');
		}
	}
}

function addSingleField ($field, $doc, $forcefield='')
{
	$respectiveField = getNewField($field, $forcefield);
	if(isset($_SESSION['zotDocs'][$doc][$field]) && !empty($_SESSION['zotDocs'][$doc][$field]))
		$_SESSION['currentDocs'][$doc][$respectiveField] = $_SESSION['zotDocs'][$doc][$field];
	else
		echo 'The variable '.$field.' was probably named wrong.';
}

function addNames ($field, $doc, $forcefield='')
{
	$respectiveField = getNewField($field, $forcefield);
	if (isset($_SESSION['zotDocs'][$doc][$field]) && !empty($_SESSION['zotDocs'][$doc][$field]))
	{
		$index = 0;
		foreach ($_SESSION['zotDocs'][$doc][$field] as $author)
		{
			echo 'Doc name: '.$doc.', respective field: '.$respectiveField;
			$_SESSION['currentDocs'][$doc][$respectiveField][$index]['forename'] = $_SESSION['zotDocs'][$doc][$field][$index]['firstName'];
			$_SESSION['currentDocs'][$doc][$respectiveField][$index]['surname'] = $_SESSION['zotDocs'][$doc][$field][$index]['lastName'];
			$index++;
		}
	}
}

function addArrayField ($field, $doc, $forcefield='')
{
	$respectiveField = getNewField($field, $forcefield);
	if (isset($_SESSION['zotDocs'][$doc][$field]) && !empty($_SESSION['zotDocs'][$doc][$field]))
	{
		$index = 0;
		foreach ($_SESSION['zotDocs'][$doc][$field] as $tag)
		{
			$_SESSION['currentDocs'][$doc][$respectiveField][$index] = $tag['tag'];
			$index++;
		}
	}
}

function getNewField($field, $forcefield='')
{
	if ($forcefield !='')
	{
		return $newfield = $forcefield;
	}
	$newfield = '';
	if ($field == 'creators')
		$newfield = 'authors';
	if ($field == 'tags')
		$newfield = 'tags';
	if ($field == 'url')
		$newfield = 'website';
	if ($field == 'place')
		$newfield = 'city';
	if ($field == 'date')
		$newfield = 'year';
	if ($field == 'title')
		$newfield = 'title';
	if ($field == 'edition')
		$newfield = 'edition';
	if ($field == 'numPages')
		$newfield = 'pages';
	if ($field == 'publisher')
		$newfield = 'publisher';
	if ($field == 'code')
		$newfield == 'code';
	if ($field == 'codeVolume')
		$newfield == 'codeVolume';
	if ($field == 'pages')
		$newfield == 'pages';
	return $newfield;
}

?>