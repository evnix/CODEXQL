<?php
	session_start();
	include ("config.php");
	include ("getdir.php");
	require_once("init-codex.php");

	$user=strtolower($_POST["user"]);
	$pword=$_POST["pword"];
	$cdx_pword=md5($pword);

	
	$ray=$codex->select_where("users","\$username=='$user' AND \$password=='$cdx_pword'");
	
	if($ray!=false)
	{
			$sID=date("Bs");
		// User ID
		setcookie ("Alien", $user, time()+$cookietm, '/', '');
		// Session ID
		// (Note, session ID is internet time plus seconds - not 100% secure,
		// we may add md5 of this 1-10,000 number at some point)
		setcookie ("AlienID", $sID, time()+$cookietm, '/', '');
		$_SESSION["user"]["group"]=$ray[0]["user_group"];
		$_SESSION["user"]["skin"]=$ray[0]["skin"];
		$_SESSION["user"]["language"]=$ray[0]["language"];
		$_SESSION["user"]["statement"]=$ray[0]["statement"];
		$_SESSION["user"]["name"]=$ray[0]["username"];
		writeFile("users/".$user.".sid",md5($sID));		
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");

	exit(0);
	}
	else
	{
		$err=50;
		// Redirect to error page
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/error.php?err=" . $err . "&uname=" . $user);
	exit(0);
	}
	/*
	$f="users/" . $user . ".hash";	
	$password=getFile($f);

	// Password is OK
	if ($password==md5($pword)) 
	{
		$sID=date("Bs");
		// User ID
		setcookie ("Alien", $user, time()+$cookietm, '/', '');
		// Session ID
		// (Note, session ID is internet time plus seconds - not 100% secure,
		// we may add md5 of this 1-10,000 number at some point)
		setcookie ("AlienID", $sID, time()+$cookietm, '/', '');
		
		writeFile("users/".$user.".sid",md5($sID));		
		// Redirect to new file
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
	}
	else
	{
		$err=50;
		// Redirect to error page
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/error.php?err=" . $err . "&uname=" . $user);
	}
*/
	function writeFile($filename, $txt)
	{
		$handle = fopen($filename, 'w+');
		fwrite($handle, $txt);
		fclose($handle);	
	}
?>