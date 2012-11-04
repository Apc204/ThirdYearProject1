<?php

session_start();

//echo '<form action="mendeleyImport.php" method="POST>';
//Print_r($_SESSION['docs']);
echo gettype($_SESSION['docs']);

foreach ($_SESSION['docs'] as $ID)
{
	$
	echo $ID.'<input type="checkbox" name="ID" value=\"'.$ID.'\"><br><br><br>';
}














?>