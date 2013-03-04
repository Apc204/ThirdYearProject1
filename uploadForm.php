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
	echo '<form action="uploadFile.php?type='.$_GET['type'].'" method="post" class="well"
			enctype="multipart/form-data">
			<legend> Enter Document Details ( * required field )</legend> <br>
			<label> Title*: </label> <input type="text" name="title"><br>
			<label>Authors* (Firstname/Initials followed by surname seperated by commas. e.g "Quentin Tarentino, JRR Tolkein"):</label> <input type="text" name="authors"><br>
			<label>Keywords:</label> <input type="text" name="keywords"><br>
			<label>Tags: </label><input type="text" name="tags"><br>
			<label>Website: </label><input type="text" name="website"><br>
			<label>Year:</label> <input type="text" name="year"><br>';
	
	switch($_GET['type']) {
		case 'Bill':
			echo '<label>City Published:</label> <input type="text" name="city"><br>
			<label>	Code:</label> <input type="text" name="code"><br>
			<label>	Code Number: </label> <input type="text" name="codeNumber"><br>
			<label>	Code Volume: </label> <input type="text" name="codeVolume"><br>
			<label>	Pages: </label> <input type="text" name="pages"><br>
			<label>	Publisher: </label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Book':
			echo '<label>City Published:</label> <input type="text" name="city"><br>
			<label>Edition:</label> <input type="text" name="edition"><br>
			<label>Editors: </label><input type="text" name="editors"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Book Section':
			echo '<label>Chapter: </label><input type="text" name="chapter"><br>
			<label>City Published:</label> <input type="text" name="city"><br>
			<label>Edition:</label> <input type="text" name="edition"><br>
			<label>Editors:</label> <input type="text" name="editors"><br>
			<label>Original Publication:</label> <input type="text" name="originalPublicaion"><br>
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
		case 'Computer Program':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<label>Revision Number:</label> <input type="text" name="revision"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Conference Proceedings':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Editors:</label> <input type="text" name="editors"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Original Publication:</label> <input type="text" name="original publication"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Encyclopedia Article':
			echo '<label>Edition:</label> <input type="text" name="edition"><br>
			<label>Original Publication:</label> <input type="text" name="original publication"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<label>Series Number:</label> <input type="text" name="seriesNumber"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Film':
			echo '<label>Country:</label> <input type="text" name="country"><br>
			<label>Length:</label> <input type="text" name="length"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Generic':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publisher:</label> <input type="text" name="Publisher"><br>
			<label>Source Type:</label> <input type="text" name="sourceType"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Hearing':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Comittee:</label> <input type="text" name="comittee"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Journal Article':
			echo '<label>Issue:</label> <input type="text" name="issue"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publication:</label> <input type="text" name="publication"><br>
			<label>Volume:</label> <input type="text" name="volume"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Newspaper Article':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Original Publication:</label><input type="text" name="original publication"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Magazine Article':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Original Publication:</label><input type="text" name="original publication"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>'; break;
		case 'Patent':
			echo '<label>Country:</label> <input type="text" name="country"><br>
			<label>Institution:</label> <input type="text" name="institution"><br>
			<label>Owner:</label> <input type="text" name="owner"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<label>Revision Number:</label> <input type="text" name="revision"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Report':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Institution:</label> <input type="text" name="institution"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Statute':
			echo '<label>Code:</label> <input type="text" name="code"><br>
			<label>Code Number:</label> <input type="text" name="codeNumber"><br>
			<label>Country:</label> <input type="text" name="country"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>Public Law Number:</label> <input type="text" name="publicLawNumber"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<label>Revision Number:</label> <input type="text" name="revision"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Television Broadcast':
			echo '<label>Country:</label> <input type="text" name="country"><br>
			<label>Length:</label> <input type="text" name="length"><br>
			<label>Publisher:</label> <input type="text" name="publisher"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Thesis':
			echo '<label>Department:</label> <input type="text" name="department"><br>
			<label>Institution:</label> <input type="text" name="institution"><br>
			<label>Pages:</label> <input type="text" name="pages"><br>
			<label>User Type:</label> <input type="text" name="userType"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Web Page':
			echo '<label>Date Accessed:</label> <input type="text" name="dateAccessed"><br>
			<label>Original Publication:</label> <input type="text" name="original publication"><br>
			<input type="submit" name="submit" class="btn btn-primary" value="Next">
			</form>
			'; break;
		case 'Working Paper':
			echo '<label>City:</label> <input type="text" name="city"><br>
			<label>Institution:</label> <input type="text" name="institution"><br>
			<label>Revision Number:</label> <input type="text" name="revision"><br>
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
	<option value="Bill">Bill</option>
	<option value="Book">Book</option>
	<option value="Book Section">Book Section</option>
	<option value="Case">Case</option>
	<option value="Computer Program">Computer Program</option>
	<option value="Conference Proceedings">Conference Proceedings</option>
	<option value="Encyclopedia Article">Encyclopedia Article</option>
	<option value="Film">Film</option>
	<option value="Generic">Generic</option>
	<option value="Hearing">Hearing</option>
	<option value="Journal Article">Journal Article</option>
	<option value="Magazine Article">Magazine Article</option>
	<option value="Newspaper Article">Newspaper Article</option>
	<option value="Patent">Patent</option>
	<option value="Report">Report</option>
	<option value="Statute">Statute</option>
	<option value="Television_Broadcast">Television Broadcast</option>
	<option value="Thesis">Thesis</option>
	<option value="Web_Page">Web Page</option>
	<option value="Working_Paper">Working Paper</option>
	</select><br>
	<input type="submit" value="Submit" class="btn btn-primary">
	</form>
	';
}



?>
