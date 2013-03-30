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
	
	<form action="parseBibtex.php" method="post" class="well"
	enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file"><br>
	<input type="hidden" name="MAX_FILE_SIZE" value="60000000" />
	<input type="submit" name="submit" value="Submit">
	</form>

	<a href="library.php">
	<button class="btn" type="button"> View Library </button>
	</a>
</body>
</html>
<?php
require_once('PEAR.php');
require_once('Structures/BibTex.php');
session_start();
set_include_path('.;c:\xampp\htdocs\3yp\Application\Uploads');

if (isset($_FILES["file"]) && !empty($_FILES["file"]))
{
	//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	//echo "Type: " . $_FILES["file"]["type"] . "<br>";
	//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	//echo "Stored in: " . $_FILES["file"]["tmp_name"];
	
	$tmp_name = $_FILES["file"]["tmp_name"];
	$uploads_dir = 'uploads/';
	$name = $_FILES["file"]["name"];
	echo '<br>'.$name.'<br>';
	if (move_uploaded_file($tmp_name, 'uploads/'.$_FILES['file']['name']))
	{
		echo 'Successfully Added.';
	}
	
	/*$filename = 'Uploads/'.$name;
	$handle = fopen($filename, 'r');
	$bibtex = fread($handle, filesize($filename));
	echo $bibtex;*/
	
	$bibtex = new Structures_Bibtex();
	$ret=$bibtex->loadFile('uploads/'.$name);

	/*if(PEAR::isError($ret)) {

		print $ret->getMessage();

		die();

	}*/

	$bibtex->parse();
	addtoLibrary($bibtex);
	//Print_r($bibtex->data);
}

function addtoLibrary($bibtex)
{
	$index = 0;
	foreach($bibtex->data as $bibDoc)
	{
		
			//Print_r($bibDoc);
			//echo'<br>NEXT<Br>';
			
			if ($bibDoc['entryType'] == 'article')
			{
				$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])]['type'] = 'Journal Article';
				parseArticle($bibDoc);
			}
			else if ($bibDoc['entryType'] == 'book')
			{
				$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])]['type'] = 'Book';
				parseBook($bibDoc);
			}
			else if ($bibDoc['entryType'] == 'booklet')
			{
				echo 'booklet type not currently supported.';
			}
			else if ($bibDoc['entryType'] == 'conference')
			{
				$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])]['type'] = 'Conference Proceedings';
				parseConferenceProceedings($bibDoc);
			}
			else if ($bibDoc['entryType'] == 'inbook')
			{
				$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])]['type'] = 'Book Section';
				parseInBook($bibDoc);
			}
			else if ($bibDoc['entryType'] == 'incollection')
			{
				echo '"incollection" type not currently supported. Consider using "inbook".';
			}
			else if ($bibDoc['entryType'] == 'inproceedings')
			{
				$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])]['type'] = 'Conference Proceedings';
				parseConferenceProceedings($bibDoc);
			}
			else if ($bibDoc['entryType'] == 'mastersthesis')
			{
				$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])]['type'] = 'Thesis';
				parseThesis($bibDoc);
			}
			else if ($bibDoc['entryType'] == 'misc')
			{
				echo '"misc" type not currently supported.';
			}
			else if ($bibDoc['entryType'] == 'phdthesis')
			{
				$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])]['type'] = 'Thesis';
				parseThesis($bibDoc);
			}
			else if ($bibDoc['entryType'] == 'proceedings')
			{
				echo 'Invalid type "proceedings", please use "inProceedings" type.';
			}
			else if ($bibDoc['entryType'] == 'techreport')
			{
				$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])]['type'] = 'Report';
				parseReport($bibDoc);
			}
			else if ($bibDoc['entryType'] == 'unpublished')
			{
				echo '"unpublished" not currently supported.';
			}
	}
}

function parseArticle($bibDoc)
{
	parseNames($bibDoc,'author','authors');
	parseField($bibDoc,'title','title');
	parseField($bibDoc,'journal','original_publication');
	parseField($bibDoc,'year','year');
	parseField($bibDoc,'volume', 'volume');
	parseField($bibDoc,'pages','pages');
}

function parseBook($bibDoc)
{
	parseNames($bibDoc,'author','authors');
	parseField($bibDoc,'title','title');
	parseField($bibDoc,'publisher','publisher');
	parseField($bibDoc,'year','year');
	parseField($bibDoc,'edition','edition');
	parseField($bibDoc,'address','city');
}

function parseConferenceProceedings($bibDoc)
{
	parseNames($bibDoc,'author','authors');
	parseField($bibDoc,'title','title');
	parseField($bibDoc,'booktitle','original_publication');
	parseField($bibDoc,'year','year');
	parseField($bibDoc,'pages','pages');
	parseField($bibDoc,'address','city');
	parseField($bibDoc,'publisher','publisher');
}

function parseInBook($bibDoc)
{
	parseNames($bibDoc,'author','authors');
	parseField($bibDoc,'title','title');
	parseField($bibDoc,'chapter','chapter');
	parseField($bibDoc,'publisher','publisher');
	parseField($bibDoc,'year','year');
	parseField($bibDoc,'edition','edition');
}

function parseThesis($bibDoc)
{
	parseNames($bibDoc,'author','authors');
	parseField($bibDoc,'title','title');
	parseField($bibDoc,'school','institution');
	parseField($bibDoc,'year','year');
	parseField($bibDoc,'type','userType');
}

function parseReport($bibDoc)
{
	parseNames($bibDoc,'author','authors');
	parseField($bibDoc,'title','title');
	parseField($bibDoc,'institution','institution');
	parseField($bibDoc,'year','year');
	parseField($bibDoc,'address','city');
}

function parseField($bibDoc, $field, $forcefield)
{
	if(isset($bibDoc[$field]) && !empty($bibDoc[$field]))
	{
		$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])][$forcefield] = $bibDoc[$field];
	}
}

function parseNames($bibDoc, $field, $forcefield)
{
	$index = 0;
	if(isset($bibDoc[$field]) && !empty($bibDoc[$field]))
	{
		foreach ($bibDoc[$field] as $author)
		{
			$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])][$forcefield][$index]['forename'] = $bibDoc[$field][$index]['first'];
			$_SESSION['currentDocs'][str_replace(' ','_',$bibDoc['title'])][$forcefield][$index]['surname'] = $bibDoc[$field][$index]['last'];
		}
	}
}



?>