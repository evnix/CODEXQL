<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- PHP routines are here!
<?php
	//error_reporting(E_ALL);
	include ("config.php");
	include ("adminchk.php");	// Check if user is admin
	include ("getdir.php");
	include ("lang/lang_".$lang.".php");

	$err = $_GET["err"];		// Error ID
	$uname = $_GET["uname"];	// Username if required

	$title = $siteName;
	$siteName .= " - ".$GLOBALS['l_err']." " . $err;
	$search = isset($search) ? $search : '';

	// Write file, used to reset password files
	function writeFile($filename, $txt)
	{
		$handle = fopen($filename, 'w+');
		fwrite($handle, $txt);
		fclose($handle);
	}
?>
-->

<!-- HTML Page starts here - header.php is always the same for every file -->

<?php include("header.php"); ?>

<!-- This is the page content listing -->
<?php
	echo "<div class='barre'>\n";
	echo " <a class='barreLien' href='index.php' title='Index'>". $title . "</a> | ".$GLOBALS['l_errAnError']." " . $search."\n";
	echo "</div>\n<br>\n";
	echo "<div class='quote'>\n";

	if ($err==1) echo $GLOBALS['l_errAvatar'].": ".$GLOBALS['l_errSize'];
	if ($err==2) echo $GLOBALS['l_errAvatar'].": ".$GLOBALS['l_errSize2'];
	if ($err==3) echo $GLOBALS['l_errAvatar'].": ".$GLOBALS['l_errPartial'];
	if ($err==4) echo $GLOBALS['l_errAvatar'].": ".$GLOBALS['l_errNoFile'];
	if ($err==7) echo $GLOBALS['l_errPassMatch'];
	if ($err==8) echo $GLOBALS['l_errUser'];
	if ($err==9) echo $GLOBALS['l_errPixels'];
	if ($err==50)
	{
		echo $GLOBALS['l_errPassErr']." <b>" . $uname . "</b><br><br>\n";
		echo $GLOBALS['l_errPassTry']."<br><br>\n";
		echo $GLOBALS['l_errPassMail']."<br><br>\n";
		echo $GLOBALS['l_errPassMail2']." <a href='error.php?err=200&uname=" . $uname . "'>".$GLOBALS['l_submit']."</a>.\n";
	}
	if ($err==10) echo $GLOBALS['l_errAvatarType'];
	if ($err==20) echo $GLOBALS['l_errTopicNotFound'];
	if ($err==98) echo $GLOBALS['l_errStat'];
	if ($err==99) echo $GLOBALS['l_errUsername'];

	// Email lost password to user
	if ($err==200)
	{
		echo $GLOBALS['l_errSending']."<br><br>\n";
		$f="users/" . $uname . ".hash";
		if (file_exists($f)):
			$password = getFile($f);
			writeFile($f, md5($password));

			$message = $siteName . " - ".$GLOBALS['l_errEmail1']."\r\n\r\n";
			$message .= $GLOBALS['l_errEmail2']."\r\n\r\n";
			$message .= $GLOBALS['l_errEmail3']. $uname . "\r\n\r\n";
			$message .= $GLOBALS['l_errEmail4']. $password . "\r\n\r\n";
			$message .= $GLOBALS['l_errEmail5']."\r\n\r\n";
			$message .= $GLOBALS['l_errEmail6']." ". $email . "\r\n\r\n";

			if (!mail(getFile('users/' . $uname . '.email'),
			$siteName . " - ".$GLOBALS['l_errPassLost'], $message,
			"From: " . $email . "\r\n" .
			"Reply-To: " . $email . "\r\n" .
			"X-Mailer: PHP/" . phpversion())):
				echo $GLOBALS['l_errAnError'] ."<br>\n";
			else:
				echo $GLOBALS['l_errMainSent']."<br><br>\n";
			endif;
		else:
			echo $GLOBALS['l_errUsername']."<br><br>\n";
		endif;
	}
	echo "</div>\n";
?>

<!-- This is the bottom bar and menu bar on the right hand side -->
<?php include("menu.php"); ?>

</table>

</body>
</html>