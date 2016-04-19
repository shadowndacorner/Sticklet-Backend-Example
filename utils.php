<?php
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	function myCheckDNSRR($hostName, $recType = '')
	{
		if(!empty($hostName)) {
			if( $recType == '' )
			$recType = "MX";
			exec("nslookup -type=$recType $hostName", $result);
			// check each line to find the one that starts with the host
			// name. If it exists then the function succeeded.
			foreach ($result as $line)
			{
				if(eregi("^$hostName",$line))
				{
					return true;
				}
			}
			return false;
		}
		return false;
	}
	
	function checkEmail($email){
		$exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
		if(eregi($exp,$email))
		{
			if(checkdnsrr(array_pop(explode("@",$email)),"MX"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
?>