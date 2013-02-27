<?php
session_start();

if (isset($_GET['title']) && !empty($_GET['title']))
{
	// Loop through documents in library to find the one matching GET[title] then print all details.
	foreach ($_SESSION['currentDocs'] as $current)
	{
		if (str_replace(' ','_',$current['title']) == $_GET['title'])
		{
			Print_r($current);
			//Print_r(str_replace(',',',<br>',json_encode($current)));
		}
	}
}



?>