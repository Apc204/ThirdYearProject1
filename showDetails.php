<?php
session_start();

if (isset($_GET['title']) && !empty($_GET['title']))
{
	// Loop through documents in library to find the one matching GET[title] then print all details.
	foreach ($_SESSION['currentDocs'] as $current)
	{
		if (isset($current['title']))
			$title = $current['title'];
		else if (isset($current['casename']))
			$title = $current['caseName'];
		else if (isset($current['nameOfAct']))
			$title = $current['nameOfAct'];
		else if (isset($current['casename']))
			$title = $current['caseName'];
		if (str_replace(' ','_',$title) == $_GET['title'])
		{
			//Print_r($current);
			Print_r(str_replace(',',',<br>',json_encode($current)));
		}
	}
}



?>