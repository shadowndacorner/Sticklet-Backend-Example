<?php
	// This page is useless and exists for testing purposes.  Here there be no monsters.
	include("mysql_settings.php");
	//die("You shouldn't be here.");
	$con = mysqli_connect($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS, $MYSQL_TABL);
	if (mysqli_connect_error())
	{
		die('Could not connect to MySQL: ' . mysql_error());
	}
	echo 'Connection OK</br>';
	mysqli_close($con);
?>