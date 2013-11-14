<?php
	session_start();
	//error_reporting(E_ALL);
	include ("config.php");
	include ("getdir.php");
	include ("adminchk.php");
	require_once("init-codex.php");
	$uploaddir = "image/avatars/";
	$uploadfile = $uploaddir . time().rand().$_FILES['userfile']['name'];
	$ftype=$_FILES['userfile']['type'];
	$err=$_FILES['userfile']['error'];	// Use this err var for all errors to stop a redirect at the end
	$emsg = "Profile error: <br><br>";

	
	
//	var_dump($_FILES); 
//	if ($err==NULL or $err=='') $err=4;	// ... from PHP 4.2.0, register_globals defaults to off...

	$ulang=$_POST["ulang"];
	$uskin=$_POST["uskin"];
	$email=$_POST["email"];
	$p1=$_POST["p1"];
	$p2=$_POST["p2"];
	$opass = isset($_POST["opass"]) ? $_POST["opass"] : '';
	$type=$_POST["type"];
	$stat=$_POST["stat"];
	$uname=strtolower($_POST["uname"]);
	$makeadmin = isset($_POST["makeadmin"]) ? $_POST["makeadmin"] : '';		// Make this user an admin?
	$deluser=isset($_POST["deluser"]);		// Delete this user?
	$avatar=$_POST["avatar"];
$codex_i=array();
	
$user_exists=false;
$Eval_temp=$codex->select_where("users","\$username=='$uname'");
if($Eval_temp!=false)
{
$user_exists=true;
$R_username=$Eval_temp[0]["username"];
$R_password=$Eval_temp[0]["password"];  
}

	
	
	// Check for valid session id
	$err = checkSession() ? $err : 99;

	// Check for invalid username
	if ($uname==NULL or $uname=='') $err=99;

	// Check for lengthy statement
	if (strlen($stat)>50) $err=98;

	// Update AVATAR via file upload
	if ($err==0)
	{
		if (strpos($ftype, "image")>-1)
		{
			// Check dimensions of image
			$isize=getimagesize($_FILES['userfile']['tmp_name']);
			$iwidth=$isize[0];
			$iheight=$isize[1];

			if ($iwidth<121 and $iheight<121)
			{
				// There is a file, and we've moved it to avatars directory
				if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
				{
					// Need to write user.pic to be this file - GOOD Upload
					$avatar=$uploadfile;
				} 
			}
			else
			{
				$err=9;
			}
		}
		else
		{
			$err=10;
		}
	}
	// If simply no file upload, set error to be zero
	if ($err==4) $err=0;

	if ($type=="new" and $user_exists==true)
	{
		$err=8;
	}

	// Write username .info if new
	if ($err==0)
	{
		if ($type=="new")
		{
			$uname=fixit($uname);				// Remove odd chars etc
			$codex_username=$uname;
			// Write (new) password
			if ($p1==$p2 and $p1!==NULL and $p2!==NULL and $p1!=="" and $p2!=="")
			{
				//writeFile("users/".$uname.".hash", md5($p1));
				$codex_password=md5($p1);
			}
			else 
			{
				$err=7;
			}
			$p1==NULL;
			$p2==NULL;
		}

		// Change existing password
		if ($type!="new" and $p1!=NULL and $p2!=NULL and $p1!="" and $p2!="")
		{
			if ($p1==$p2)
			{
				if (md5($opass)==$R_password)				{
					//writeFile("users/".$uname.".hash", md5($p1));
					$codex_password=md5($p1);
				}
				else
				{
					$err=7;
				}
			}
			else 
			{
				$err=7;
			}

		}

		if ($err==0)
		{
			// Write avatar info
			//writeFile("users/".$uname.".pic", $avatar);
			// Write email address
			$email=fixit($email);
			//writeFile("users/".$uname.".email", $email);
			// Info file
			$thing="";
			//writeFile("users/".$uname.".info", $thing);
			// Stat file
			$stat=fixit($stat);
			//writeFile("users/".$uname.".stat", $stat);
			// Lang file
			//writeFile("users/".$uname.".lang", $ulang);
			// Skin file
			//writeFile("users/".$uname.".skin", $uskin);
			$uid=time();
			
			/*  create a hits file*/
			$handle=fopen("users/".$uname.".hits","a");
			fclose($handle);
			
			if ($makeadmin){ $thing="admin"; }else{ $thing="user"; }

			if($type=='new')
			{
			$_SESSION["user"]["language"]=$ulang;
			$_SESSION["user"]["skin"]=$uskin;
			$codex->insert("users","uid=$uid&username=$codex_username&password=$codex_password&email=$email&avatar=$avatar&statement=$stat&language=$ulang&skin=$uskin&user_group=$thing");
			$codex->commit_changes();
			}
			if($type=="edit" and !$deluser)
			{
			$_SESSION["user"]["language"]=$ulang;
			$_SESSION["user"]["skin"]=$uskin;
			
			
				if(!isset($codex_password))
				{
				 $codex->update_where("users","email=$email&avatar=$avatar&statement=$stat&language=$ulang&skin=$uskin&user_group=$thing","\$username=='$uname'");
				}
				else
				{
				 $codex->update_where("users","password=$codex_password&email=$email&avatar=$avatar&statement=$stat&language=$ulang&skin=$uskin&user_group=$thing","\$username=='$uname'");
				}
				
				
			$codex->commit_changes();
			}
			
		}
	}

	// Delete this user
	if ($err == 0 and $deluser)
	{
		//if (file_exists("users/".$uname.".pic"))	unlink("users/".$uname.".pic");
		//if (file_exists("users/".$uname.".email")) unlink("users/".$uname.".email");
		//if (file_exists("users/".$uname.".info")) unlink("users/".$uname.".info");
		//if (file_exists("users/".$uname.".stat")) unlink("users/".$uname.".stat");
		//if (file_exists("users/".$uname.".hash")) unlink("users/".$uname.".hash");
		//if (file_exists("users/".$uname.".lang")) unlink("users/".$uname.".lang");
		//if (file_exists("users/".$uname.".skin")) unlink("users/".$uname.".skin");
		//if (file_exists("users/".$uname.".hits")) unlink("users/".$uname.".hits");
		//if (file_exists("users/".$uname.".sid"))	unlink("users/".$uname.".sid");
		//if (file_exists("users/".$uname.".time")) unlink("users/".$uname.".time");
		$codex->delete_where("users","\$username=='$uname'");
		$codex->commit_changes();
	}

	// Redirect to main forum page
	if ($err==0)
	{
		// If new, set login cookie to true
		if ($type=="new")
		{
			$sID=date("Bs");
			// User ID
			setcookie ("Alien", $uname, time()+$cookietm, '/', '');
			// Session ID
			setcookie ("AlienID", $sID, time()+$cookietm, '/', '');
			writeFile("users/".$uname.".sid",md5($sID));		
		}
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
	}
	else
	{
		// If we get here, there was an error, so redirect to error page
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/error.php?err=" . $err . "&uname=" . $uname);
	}

	// Write file
	function writeFile($filename, $txt)
	{
		$handle = fopen($filename, 'w+');
		fwrite($handle, $txt);
		fclose($handle);	
	}

	function fixit($txt)
	{
		// Generic slash, newline, tag stripping
		$txt=stripslashes($txt);
		$txt=strip_tags($txt);
		$txt=str_replace(array("<",">"),"",$txt);
		return $txt;
	}

?>