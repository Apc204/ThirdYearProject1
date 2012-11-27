<?php

session_start();

echo 'Current documents in library:';
Print_r($_SESSION['currentDocs']);

foreach ($_SESSION['currentDocs'] as $doc)
{
	//Print_r($doc);
}


?>