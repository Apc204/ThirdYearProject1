<?php

session_start();

//$_SESSION['currentDocs'] = array();
if (isset($_SESSION['currentDocs']) && !empty($_SESSION['currentDocs']))
{
	echo '<h2>Documents currently in library:</h2><br>';
	foreach ($_SESSION['currentDocs'] as $title)
	{
		$url = 'showDetails.php?title='.str_replace(' ','_', $title['title']);
		$url = str_replace('"','',$url);
		//echo $title['title'].'<form action="'.$url.'">
				//				<input type="submit" value="View Details"></form>';
		echo $title['title'].'<a href="'.$url.'"><input type="Button" value="Show Details"> </a><br>';
	} // echo title and button taking user to "showDetails.php"
}

Print_r($_SESSION['currentDocs']);

?>