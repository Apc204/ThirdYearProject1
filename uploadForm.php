<?php
session_start();

if(isset($_GET['type']) && !empty($_GET['type']))
{
	// Print relevent form depending on the type selected by user.
	echo '<form action="uploadFile.php" method="post"
			enctype="multipart/form-data">
			<h2>Enter Document Details</h2> ( * required field ) <br>
			Title: <input type="text" name="title"><br>
			Authors (Surname followed by initials, seperated by commas): <input type="text" name="authors"><br>
			Keywords: <input type="text" name="keywords"><br>
			Tags: <input type="text" name="tags"><br>
			Website: <input type="text" name="tags"><br>
			Year: <input type="text" name="year"><br>';
	
	switch($_GET['type']) {
		case 'bill':
			echo 'City Published: <input type="text" name="city"><br>
			Code: <input type="text" name="code"><br>
			Code Number <input type="text" name="codeNumber"><br>
			Code Volume <input type="text" name="codeVolume"><br>
			Pages <input type="text" name="pages"><br>
			Publisher <input type="text" name="publisher"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'book':
			echo 'City Published: <input type="text" name="city"><br>
			Edition: <input type="text" name="edition"><br>
			Editors: <input type="text" name="editors"><br>
			Pages: <input type="text" name="pages"><br>
			Publisher: <input type="text" name="publisher"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'bookSection':
			echo 'Chapter: <input type="text" name="chapter"><br>
			City Published: <input type="text" name="city"><br>
			Edition: <input type="text" name="edition"><br>
			Editors: <input type="text" name="editors"><br>
			Publication: <input type="text" name="publication"><br>
			Publisher: <input type="text" name="publisher"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'Case':
			echo 'Counsel: <input type="text" name="counsel"><br>
			Last Update: <input type="text" name="lastUpdate"><br>
			Pages: <input type="text" name="pages"><br>
			Volume: <input type="text" name="volume"<br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'computerProgram':
			echo 'City: <input type="text" name="city"><br>
			Publisher: <input type="text" name="publisher"><br>
			Revision Number: <input type="text" name="revisionNumber"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'conferenceProceedings':
			echo 'City: <input type="text" name="city"><br>
			Editors: <input type="text" name="editors"><br>
			Pages: <input type="text" name="pages"><br>
			Publication: <input type="text" name="publication"><br>
			Publisher: <input type="text" name="publisher"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'encyclopediaArticle':
			echo 'Edition: <input type="text" name="edition"><br>
			Publication: <input type="text" name="publication"><br>
			Publisher: <input type="text" name="publisher"><br>
			Series Number: <input type="text" name="seriesNumber"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'film':
			echo 'Country: <input type="text" name="country"><br>
			Length: <input type="text" name="length"><br>
			Publisher: <input type="text" name="publisher"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'generic':
			echo 'City: <input type="text" name="city"><br>
			Pages: <input type="text" name="pages"><br>
			Publisher: <input type="text" name="Publisher"><br>
			Source Type: <input type="text" name="sourceType"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'hearing':
			echo 'City: <input type="text" name="city"><br>
			Comittee: <input type="text" name="comittee"><br>
			Pages: <input type="text" name="pages"><br>
			Publisher: <input type="text" name="publisher"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'journalArticle':
			echo 'Issue: <input type="text" name="issue"><br>
			Pages: <input type="text" name="pages"><br>
			Publication: <input type="text" name="publication"><br>
			Volume: <input type="text" name="volume"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'newspaperArticle':
			echo 'City: <input type="text" name="city"><br>
			Pages: <input type="text" name="pages"><br>
			Publication: <input type="text" name="publication"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'patent':
			echo 'Country: <input type="text" name="country"><br>
			Institution: <input type="text" name="institution"><br>
			Owner: <input type="text" name="owner"><br>
			Pages: <input type="text" name="pages"><br>
			Publisher: <input type="text" name="publisher"><br>
			Revision Number: <input type="text" name="revisionNumber"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'report':
			echo 'City: <input type="text" name="city"><br>
			Institution: <input type="text" name="institution"><br>
			Pages: <input type="text" name="pages"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'statute':
			echo 'Code: <input type="text" name="code"><br>
			Code Number: <input type="text" name="codeNumber"><br>
			Country: <input type="text" name="country"><br>
			Pages: <input type="text" name="pages"><br>
			Public Law Number: <input type="text" name="publicLawNumber"><br>
			Publisher: <input type="text" name="publisher"><br>
			Revision Number: <input type="text" name="revisionNumber"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'televisionBroadcast':
			echo 'Country: <input type="text" name="country"><br>
			Length: <input type="text" name="length"><br>
			Publisher: <input type="text" name="publisher"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'thesis':
			echo 'Department: <input type="text" name="department"><br>
			Institution: <input type="text" name="institution"><br>
			Pages: <input type="text" name="pages"><br>
			User Type: <input type="text" name="userType"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'webPage':
			echo 'Date Accessed: <input type="text" name="dateAccessed"><br>
			Publication: <input type="text" name="publication"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
		case 'workingPaper':
			echo 'City: <input type="text" name="city"><br>
			Institution: <input type="text" name="institution"><br>
			Revision Number: <input type="text" name="revisionNumber"><br>
			Series: <input type="text" name="series"><br>
			<input type="submit" name="submit" value="Next">
			</form>
			'; break;
	}
}
else 
{
	echo '<h1> Select the type of reference you want to add: </h1><br>
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
	</select>
	<input type="submit" value="Submit">
	</form>
	';
}



?>

<html>
<body>



</body>
</html>