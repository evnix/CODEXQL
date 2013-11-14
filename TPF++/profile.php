<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- PHP routines are here!
<?php
	//error_reporting(E_ALL);
	require_once("init-codex.php");
	include ("config.php");
	include ("adminchk.php");	// Check if user is admin
	include ("getdir.php");
	include ("lang/lang_".$lang.".php");

	$title = $siteName;
	$siteName .= " - ".$GLOBALS['l_prof'];

	$action = $_GET["action"];	// Get action
	// Username for this profile - edit or view it
	$uname = isset($_GET["uname"]) ? str_replace(
		array('.', '/', '\\', '?', '*', '&', '<', '>'), '',	$_GET["uname"]) : '';
	if ($uname=="Guest")
	{
		header("Location: http://" . $_SERVER['HTTP_HOST']
			. dirname($_SERVER['PHP_SELF']) . "/error.php?err=99");
	}
	$buffer="";

	if ($user=="Guest") $user="NULL";

	// List all avatars available in avatar folder
	function calc_integ($str)
	{
	return (int)$str;
	}
	
	function buildAvatarList()
	{
		global $uname;
		echo "<select name='avatar'>\n";
		$start="image/avatars/";
		if($dir=opendir($start))
		{
			while (($file=readdir($dir))!=FALSE)
			{
				if ($file!="." and $file!=".." and calc_integ($file)==0 and $file!="unknown")
				{
					if (!strpos($file, "DS_Store") and !strpos($file, ".htm") and !strpos($file, ".php"))
					{
						$url=$start . $file;
						echo "<option value='" . $url . "' ";
						if (strpos(getFile("users/" . $uname . ".pic"), $file)>-1) echo "Selected";
						echo "> " . $file . "\n";
					}
				}
			}
			closedir($dir);
		}
		echo "</select>\n";
	}

	// List all installed languages
	function buildLangList($langx=null)
	{
		global $uname;
		echo "<select name='ulang'>\n";
		$start="lang/";
		if($dir=opendir($start))
		{
			while (($file=readdir($dir))!=FALSE)
			{
				if ($file!="." and $file!="..")
				{
					if (strpos($file, "lang_")>-1)
					{
						$file=str_replace("lang_","",$file);
						$file=str_replace(".php","",$file);
						echo "<option value='" . $file . "' ";
						if ($langx==$file) echo "Selected";
						echo "> " . $file . "\n";
					}
				}
			}
			closedir($dir);
		}
		echo "</select>\n";
	}

	// List all installed languages
	function buildSkinList()
	{
		global $uname;
		echo "<select name='uskin'>\n";
		$start="image/skin/";
		if($dir=opendir($start))
		{
			while (($file=readdir($dir))==TRUE)
			{
				if ($file!="." and $file!="..")
				{
					echo "<option value='" . $file . "' ";
					if (getFile("users/" . $uname . ".skin")==$file) echo "Selected";
					echo "> " . $file . "\n";
				}
			}
			closedir($dir);
		}
		echo "</select>\n";
	}


	// Show all members, in a list
	function showMemberList()
	{
		global $skin;
		global $title;
		global $codex;
		$count=0;
		$start="users/";

		echo "<div class='barre'>";
		echo " <a class='barreLien' href='index.php' title='Index'>". $title . "</a> | ".$GLOBALS['l_menuMembers'];
		echo "</div>";

		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr>\n";
		echo " <td class='col1tp' width='55%'>".$GLOBALS['l_profUsername']."</td>\n";
		echo " <td class='col2tp' width='15%'>".$GLOBALS['l_profLang']."</td>\n";
		echo " <td class='col3tp' width='15%'>".$GLOBALS['l_profSkin']."</td>\n";
		echo " <td class='col4tp' width='15%'>".$GLOBALS['l_getPosts']."</td>\n";
		echo "</tr>\n";
		$members=$codex->select_where("users");
		
		
		foreach($members as $member)
		{	

						echo "<tr>\n <td class='filler' colspan='4'>\n</td>\n</tr>\n";
						echo "<tr valign='top'>";

						// Username
						echo "<td class='col1bt'>\n";
						echo " <a class='topicLink' href='profile.php?action=view&amp;uname=" .$member["username"]. "'>" .$member["username"]. "</a>\n";
						echo "</td>\n";

						// Lang
						echo "<td class='col2bt'>\n";
						echo $member["language"]."\n";
						echo "</td>\n";

						// Skin
						echo "<td class='col3bt'>\n";
						echo $member["skin"]."\n";
						echo "</td>\n";

						// Num posts
						echo "<td class='col4bt'>\n";
						if (file_exists("users/".$member["username"].".hits")) echo getFile("users/".$member["username"].".hits") . "\n";
						else echo "  -";
						echo "</td>\n";

						echo "</tr>\n";
						echo "<tr valign='top'>\n";

						// Statement
						echo "<td class='coltxt' colspan='4'>\n";
						echo " <em>".$member["statement"]."&nbsp;</em>\n";
						echo "</td>\n";
						
		}		
			echo "<tr>\n <td class='filler' colspan='4'>\n</td>\n</tr>\n";
		
		
		echo "</table>";
	}

?>
-->

<!-- HTML Page starts here - header.php is always the same for every file -->
<?php include("header.php"); ?>

<!-- This is the page content listing -->
<?php
	// Edit this profile
	if ($action=="edit" or $uname==$user or ($admin==TRUE and $action!=="all"))
	{
		echo "<div class='barre'>\n<a class='barreLien' href='index.php' title='Index'>". $title . "</a> | ".$GLOBALS['l_profEditProf']." " . $uname . "\n</div>\n";
		$ray=showUser($uname);
		echo "<form enctype='multipart/form-data' action='updatepf.php' method='POST'>\n";
		echo "<input type=\"hidden\" name=\"sessionid\" value=\"{$_REQUEST['PHPSESSID']}\">\n";
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profUsername']."</td>\n <td class='editionLight'>$uname<input type='hidden' name='uname' size='16' value='" . $uname . "'>\n";
		if ($admin) 
		{
			echo " ... <input type='checkbox' name='makeadmin' ";
			// Line checks for already an admin?
			if ($ray[0]["user_group"]=="admin") echo "checked";
			echo "> ".$GLOBALS['l_profAdmin']." | ".$GLOBALS['l_profDel'];
			echo "<input type='checkbox' name='deluser'>\n";
		}
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'>".$GLOBALS['l_profLang']."</td>\n <td class='editionDark'>\n";
		buildLangList($ray[0]["language"]);
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profSkin']."</td>\n <td class='editionLight'>\n";
		buildSkinList();
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'>".$GLOBALS['l_profEmail']."</td>\n <td class='editionDark'><input type='text' name='email' size='16' value='" .$ray[0]["email"]. "'> ".$GLOBALS['l_profEmailMsg']."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profPassOld']."</td>\n <td class='editionLight'><input type='password' name='opass' size='16' value=''> </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'>".$GLOBALS['l_profPassNew']."</td>\n <td class='editionDark'><input type='password' name='p1' size='16' value=''> </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profPassConf']."</td>\n <td class='editionLight'><input type='password' name='p2' size='16' value=''> </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'>".$GLOBALS['l_profStatement2']." </td>\n <td class='editionDark'><input type='text' name='stat' size='50' value='" . str_replace("'","&#39;",$ray[0]["statement"]) . "'> </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profAvatar']."</td>\n <td class='editionLight'>\n";
		buildAvatarList();
		echo " | <a class='link' href=\"javascript:popup('avatargallery.php')\">".$GLOBALS['l_avatarGallery']."</a>\n";		
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'><input type='hidden' name='MAX_FILE_SIZE' value='1020040'> ".$GLOBALS['l_profUpload']."</td>\n";
		echo " <td class='editionDark'><input name='userfile' type='file' size='16'> ".$GLOBALS['l_profMax']."</td>\n";
		echo "</tr> \n";
		echo "</table>\n";
		echo "<input type='hidden' name='type' value='edit'>\n";
		echo "<input type='submit' value='OK'>\n";
		echo "</form>\n";
	}
	// New user?
	if ($action=="new")
	{
		echo "<div class='barre'>\n<a class='barreLien' href='index.php' title='Index'>". $title . "</a> | ".$GLOBALS['l_profNewAcc']."\n</div>\n";
		//showUser($uname);
		echo "<form enctype='multipart/form-data' action='updatepf.php' method='POST'>\n";
		echo "<input type=\"hidden\" name=\"sessionid\" value=\"{$_REQUEST['PHPSESSID']}\">\n";
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profUsername']."</td>\n <td class='editionLight'>$uname<input type='text' name='uname' size='16' value='" . $uname . "'> (A-Z, 0-9, '-', '_')</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'>".$GLOBALS['l_profLang']."</td>\n <td class='editionDark'>\n";
		buildLangList("en");
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profSkin']."</td>\n <td class='editionLight'>\n";
		buildSkinList();
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'>".$GLOBALS['l_profEmail']."</td>\n <td class='editionDark'><input type='text' name='email' size='16' value=''> ".$GLOBALS['l_profEmailMsg']."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profPassNew']."</td>\n <td class='editionLight'><input type='password' name='p1' size='16' value=''> </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'>".$GLOBALS['l_profPassConf']."</td>\n <td class='editionDark'><input type='password' name='p2' size='16' value=''> </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'>".$GLOBALS['l_profStatement2']."</td>\n <td class='editionLight'><input type='text' name='stat' size='50' value=''> </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionDark'>".$GLOBALS['l_profAvatar']."</td>\n <td class='editionDark'>\n";
		buildAvatarList();
		echo " | <a class='link' href=\"javascript:popup('avatargallery.php')\">".$GLOBALS['l_avatarGallery']."</a>\n";		
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo " <td class='editionLight'><input type='hidden' name='MAX_FILE_SIZE' value='100020040'> ".$GLOBALS['l_profUpload']."</td>\n";
		echo " <td class='editionLight'><input name='userfile' type='file' size='16'> ".$GLOBALS['l_profMax']."</td>\n";
		echo "</tr> \n";
		echo "</table>\n";
		echo "<input type='hidden' name='type' value='new'>\n";
		echo "<input type='submit' value='OK'>\n";
		echo "</form>\n";
	}
	// Just view it
	if ($action=="view" and $admin==FALSE and $uname!==$user)
	{
		echo "<div class='barre'>";
		echo " <a class='barreLien' href='index.php' title='Index'>".$title."</a> | ".$GLOBALS['l_profViewProf']." " . $uname . "\n</div>\n";
		showUser($uname);
	}
	// Show a list of all members
	if ($action=="all")
	{
		showMemberList();
	}
?>

<!-- This is the menu bar on the right hand side -->
<?php include("menu.php"); ?>

</table>

</body>
</html>