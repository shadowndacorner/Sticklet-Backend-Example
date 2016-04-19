<?php
	include("mysql_settings.php");
	include("utils.php");
	if ($_POST["userid"]!=nil)
	{
		$un = $_POST["userid"];
		// Not password, but rather session ID
		$pass = $_POST["key"];
		$con = mysqli_connect($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS, $MYSQL_TABL);
		if (mysqli_connect_error())
		{
			// Couldn't connect to MySQL
			die('-2');
		}
		
		$query = "SELECT * FROM users WHERE `userID`=\"". mysqli_real_escape_string($con, $un) . "\";";
		$result = mysqli_query($con,$query);
		while($row = mysqli_fetch_array($result)) {
			if ($pass==$row["ukey"])
			{
				// Correct user session
				die("0");
				break;
			}
		}
		// Incorrect user session
		die("1");
		mysqli_close($con);
	}
	// User ID doesn't exist
	die("-1");
?>