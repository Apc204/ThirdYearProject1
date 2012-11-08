<?php

function importDocuments()
{
	foreach (array_keys($_GET) as $docID)
	{
		echo $_GET[$docID];
	}
}






?>