<?php

	$uStat = FALSE;
	$user="Anon";
	$admin=FALSE;
	if (isset($_COOKIE["Alien"]) and isset($_COOKIE["AlienID"]))
	{
		$uStat=TRUE;
		$user=$_COOKIE["Alien"];

		$sID=gf("users/".$user.".sid");
		$sID2=$_COOKIE["AlienID"];
		if ($sID==md5($sID2) && isset($_SESSION["user"]))
		{
			if ($_SESSION["user"]["group"]=="admin"){ $admin=TRUE; }
			
			//$f="users/" . $user . ".lang";
			//if (file_exists($f)) 
			$lang=$_SESSION["user"]["language"]; //gf($f);
			//$f="users/" . $user . ".skin";
			//if (file_exists($f)) 
			$skin=$_SESSION["user"]["skin"]; //gf($f);
			footprint();
		}
		else
		{
			$user="Guest";
			$uStat=FALSE;
		}
	}
	else
	{
		$user="Guest";
	}

	function gf($f)
	{
		$files="";
		if (file_exists($f))
		{
			$handle = fopen($f, "r");
			//$files = file_get_contents($f);
			$files = implode("",file($f));
			fclose($handle);
		}
		return $files;
	}

	// Footprint user as still being on-line
	function footprint()
	{
		global $user;
		$fname="users/".$user.".time";
		$handle = fopen($fname, 'w+');
		fwrite($handle, time());
		fclose($handle);
	}

	// Checking the correct session id
	function checkSession() // 28 Sep 2006
	{
		if (isset($_REQUEST))
		{ // We are receiving information from a form
			if (isset($_POST) and count($_POST) > 1)
			{
				// Other fields than PHPSESSID present
				$_REQUEST['sessionid'] = isset($_REQUEST['sessionid'])
					? $_REQUEST['sessionid'] : '';
				return $_REQUEST['sessionid'] == $_REQUEST['PHPSESSID'];
			}
			else
			{
				return true;
			}
		}
		return true;
	}
?>