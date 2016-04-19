<?php
	// TODO: replace "false" with error codes
	include("mysql_settings.php");
	include("utils.php");
	if ($_POST["username"])
	{
		$un = $_POST["username"];
		$pass = $_POST["pass"];
		$con = mysqli_connect($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS, $MYSQL_TABL);
		if (mysqli_connect_error())
		{
			// Failed to connect to MySQL
			die('false');
		}
		
		$query = "SELECT * FROM users WHERE userName=\"". mysqli_real_escape_string($con, $un) . "\";";
		$result = mysqli_query($con,$query);
		$bset=false;
		
		// NOTE: this should only run once in our case (or 0 times)
		while($row = mysqli_fetch_array($result)) {
			if (md5($pass)==$row["password"])
			{
				$bset=true;
				$ukey = md5(generateRandomString(8));
				echo $row["userID"];
				echo " /|\\ ";
				echo $ukey;
				echo " /|\\ ";
				echo $row["XP"];
				$query = "UPDATE `users` SET `ukey` = \"" . mysqli_real_escape_string($con, $ukey) . "\" WHERE userID = ".mysqli_real_escape_string($con, $row["userID"]).";";
				mysqli_query($con, $query);
				break;
			}
		}
		if (!$bset)
		{
			// Passwords didn't match, or user wasn't in database
			die("false");
		}
		
		mysqli_close($con);
	}
	else
	{
		// Username wasn't set
		echo "false";
	}
?>