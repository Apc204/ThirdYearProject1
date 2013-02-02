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
						<li class><a href="index.php">Home</a></li>
						<li><a href="import.php">Import References</a></li>
						<li class="active"><a href="uploadForm.php">Manually Add Document</a></li>
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
session_start();

if(isset($_GET['type']) && !empty($_GET['type']))
{
	// Print relevent form depending on the type selected by user.
	echo '<form action="uploadFile.php" method="post" class="well"
			enctype="multipart/form-data">
			<legend> Enter Document Details ( * required field )</legend> <br>
			<label> Title*: </label> <input type="text" name="title"><br>
			<label>Authors* (Firstname/Initials followed by surname seperated by commas. e.g "Quentin Tarentino, JRR Tolkein"):</label> <input type="text" name="authors"><br>
			<label>Keywords:</label> <input type="text" name="keywords"><br>
			<label>Tags: </label><input type="text" name="tags"><br>
			<label>Website: </label><input type="text" name="website"><br>
			<label>Year:</label> <input type="text" name="year"><br>';
	
	switch($_GET['type']) {
		case 'bill':
			echo '<label>City Published:</label> <input type="text" name="city"><br>
			<label>	Code:</label> <input type="text" name="code"><br>
			<label>	Code Number: </label> <input type="text" name="codeNumber"><br>
			<label>	Code Volume: </label> <input type="text" name="codeVolume"><br>
			<label>	Pages: </label> <input type="text" name="pages"><br>
			<label>	Publisher: </label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'book':
			echo '<label>City Published:</label> <input type="text" name="city"><br>
			<label>Edition:</label> <input type="text" name="edition"><br>
			<label>Editors: </label><input type="text" name="editors"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'bookSection':
			echo '<label>Chapter: </label><input type="text" name="chapter"><br>
			<label>City Published:</label> <input type="text" name="city"><br>
			<label>Edition:</label> <input type="text" name="edition"><br>
			<label>Editors:</label> <input type="text" name="editors"><br>
			<label>Publication:</label> <input type="text" name="publication"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Case':
			echo '<label>Counsel:</label> <input type="text" name="counsel"><br>
			<label>Last Update:</label> <input type="text" name="lastUpdate"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Volume:</label> <input type="text" name="volume"<br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'computerProgram':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<label>Revision Number:</label> <input type="text" name="revisionNumber"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'conferenceProceedings':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Editors:</label> <input type="text" name="editors"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publication:</label> <input type="text" name="publication"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'encyclopediaArticle':
			echo '<label>Edition:</label> <input type="text" name="edition"><br>
			<label>Publication:</label> <input type="text" name="publication"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<label>Series Number:</label> <input type="text" name="seriesNumber"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'film':
			echo '<label>Country:</label> <input type="text" name="country"><br>
			<label>Length:</label> <input type="text" name="length"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'generic':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publisher:</label> <input type="text" name="Publisher"><br>
			<label>Source Type:</label> <input type="text" name="sourceType"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'hearing':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Comittee:</label> <input type="text" name="comittee"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'journalArticle':
			echo '<label>Issue:</label> <input type="text" name="issue"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publication:</label> <input type="text" name="publication"><br>
			<label>Volume:</label> <input type="text" name="volume"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'newspaperArticle':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publication:</label><input type="text" name="publication"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'patent':
			echo '<label>Country:</label> <input type="text" name="country"><br>
			<label>Institution:</label> <input type="text" name="institution"><br>
			<label>Owner:</label> <input type="text" name="owner"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<label>Revision Number:</label> <input type="text" name="revisionNumber"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'report':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Institution:</label> <input type="text" name="institution"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'statute':
			echo '<label>Code:</label> <input type="text" name="code"><br>
			<label>Code Number:</label> <input type="text" name="codeNumber"><br>
			<label>Country:</label> <input type="text" name="country"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Public Law Number:</label> <input type="text" name="publicLawNumber"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<label>Revision Number:</label> <input type="text" name="revisionNumber"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'televisionBroadcast':
			echo '<label>Country:</label> <input type="text" name="country"><br>
			<label>Length:</label> <input type="text" name="length"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'thesis':
			echo '<label>Department:</label> <input type="text" name="department"><br>
			<label>Institution:</label> <input type="text" name="institution"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>User Type:</label> <input type="text" name="userType"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'webPage':
			echo '<label>Date Accessed:</label> <input type="text" name="dateAccessed"><br>
			<label>Publication:</label> <input type="text" name="publication"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'workingPaper':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Institution:</label> <input type="text" name="institution"><br>
			<label>Revision Number:</label> <input type="text" name="revisionNumber"><br>
			<label>Series:</label> <input type="text" name="series"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
	}
}
else 
{
	echo '<legend> Select the type of reference you want to add: </legend><br>
	<form action="uploadForm.php" method="GET">
	<select name="type">
	<option value="bill">Bill</option>
	<option value="book">Book</option>
	<option value="bookSection">Book Section</option>
	<option value="case">Case</option>
	<option value="computerProgram">Computer Program</option>
	<option value="conferenceProceedings">Conference Proceedings</option>
	<option value="encyclopediaArticle">Encyclopedia Article</option>
	<option value="film">Film</option>
	<option value="generic">Generic</option>
	<option value="hearing">Hearing</option>
	<option value="journalArticle">Journal Article</option>
	<option value="magazineArticle">Magazine Article</option>
	<option value="newspaperArticle">Newspaper Article</option>
	<option value="patent">Patent</option>
	<option value="report">Report</option>
	<option value="statute">Statute</option>
	<option value="televisionBroadcast">Television Broadcast</option>
	<option value="thesis">Thesis</option>
	<option value="webPage">Web Page</option>
	<option value="workingPaper">Working Paper</option>
	</select><br>
	<input type="submit" value="Submit" class="btn btn-primary">
	</form>
	';
}



?>
