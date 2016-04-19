 <?php
	include("mysql_settings.php");
	include("utils.php");
	if ($_POST["username"])
	{
		$con = mysqli_connect($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS, $MYSQL_TABL);
		if (mysqli_connect_error())
		{
			die('-1');	// generic failure
		}
		$un = $_POST["username"];
		
		if ($un!=mysqli_real_escape_string($con, $un) || strlen($un)<5)
		{
			die("1");	// Invalid username
		}
		$pass = $_POST["pass"];
		$email = $_POST["em"];
		if (!checkEmail($email))
		{
			die('2');	// Invalid email
		}
		
		if ($email!=mysqli_real_escape_string($con, $email))
		{
			die("2");	// Invalid email
		}
		$query = "SELECT * FROM users;";
		$result = mysqli_query($con,$query);
		$bset=false;
		$maxInt=0;
		while($row = mysqli_fetch_array($result)) {
			if ($maxInt<intval($row['userID']))
			{
				$maxInt = intval($row['userID']);
			}
			if ($row["email"]==mysqli_real_escape_string($con, $email))
			{
				die("4"); // Email already in db
				$bset=true;
				break;
			}
			if ($row["userName"]==mysqli_real_escape_string($con, $un))
			{
				die("3"); // Username already in db
				$bset=true;
				break;
			}
		}
		if (!$bset)
		{
			// If everything's AOK, go ahead and register the user
			$maxInt+=1;
			$query = "INSERT INTO `users` (`userID`, `ukey`, `userName`, `password`, `email`, `XP`) VALUES (\"".$maxInt."\", \"\", \"".mysqli_real_escape_string($con, $un)."\", \"".md5($pass)."\", \"".mysqli_real_escape_string($con, $email)."\", 0);";
			$result = mysqli_query($con,$query);
//			echo $query;
			die("0");
		}
		
		mysqli_close($con);
	}
	// Username not set
	die("-1");
?>