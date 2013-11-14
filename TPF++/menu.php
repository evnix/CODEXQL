</td>

<!-- This is the bottom bar and menu bar on the right hand side -->

 <td class="menuContainer">
	<div class="barre" style="text-align:center;"><?php echo $GLOBALS['l_menu']; ?></div>
	<div class="menu">

	<form action="search.php" method="GET">
	 <?php echo $GLOBALS['l_menuSearch']; ?>
	 <input class="search" type="text" name="query" size="12" value="<?php echo $GLOBALS['l_menuSearch']; ?>" onfocus="if (value == '<?php echo $GLOBALS['l_menuSearch']; ?>') {value =''}" onblur="if (value == '') {value = '<?php echo $GLOBALS['l_menuSearch']; ?>'}">
	</form>
	<?php
	// If user is logged in, show name and option to logout
	if (isset($uStat) and $uStat===TRUE)
	{
		echo $GLOBALS['l_menuWelcome'] . " " . $user."\n";
		if ($admin===TRUE) echo "*";
		echo "<div class='quote'>\n";
		echo "<a class='link' href='logout.php'>".$GLOBALS['l_menuLogout']."</a><br>\n";
		echo "<a class='link' href='profile.php?action=edit&amp;uname=" . $user . "'>".$GLOBALS['l_menuProfil']."</a>\n";
		echo "</div>\n";
	}
	// So, maybe we want to login?
	else
	{
		?>
		<form action="login.php" method="POST">
		 <?php echo $GLOBALS['l_menuLogin']; ?><br>
		 <input class="username" type="text" name="user" size="12" value="<?php echo $GLOBALS['l_menuUsername']; ?>" onfocus="if (value == '<?php echo $GLOBALS['l_menuUsername']; ?>') {value =''}" onblur="if (value == '') {value = '<?php echo $GLOBALS['l_menuUsername']; ?>'}">
		 <input class="password" type="password" name="pword" size="12" value="Password" onfocus="if (value == 'Password') {value =''}" onblur="if (value == '') {value = 'Password'}">
		 <input type="submit" value="OK">
		</form>
		<!-- New user? -->
		<div class='quote'>
		 <a class='link' href='profile.php?action=new'><?php echo $GLOBALS['l_menuNewUser']; ?></a>
		</div>
		<?php
	}
	// Show list of forums on right
	echo $GLOBALS['l_menuQuick']."<br>\n";
	echo "<div class='quote'>\n";
	showMiniForums();
	echo "</div>\n";
	// Membership list
	echo "<div class='quote'>\n";
	echo "<a class='link' href='profile.php?action=all'>".$GLOBALS['l_menuMembers']."</a>\n";
	echo "</div>\n";

	// !!!Chat MOD!!!
	//echo "<div class='quote'>\n";
	//echo "<a class='link' href=\"javascript:popup('chat.php')\">Chat!</a>\n";
	//echo "</div>\n";

	// Members on-line
	$mems=getUsersOnLine();
	if (!empty($mems))
	{
		echo $GLOBALS['l_menuOnLine']."<br>\n";
		echo "<div class='quote'>\n";
		for ($i=0; $i<count($mems); $i++)
		{
			echo "<a class='link' href='profile.php?action=view&amp;uname=".$mems[$i]["user"]."'>".$mems[$i]["user"]."</a>";
			echo "<br>\n";
		}
		echo "</div>\n";
	}
	?>

	</div>
 </td>
</tr>




 <!-- Please leave this in place, thanks! -->
<tr>
 <td colspan="2">
	<div class='barre'>
	 <?php echo isset($buffer) ? $buffer : ''; ?>
	 <a class='barreLien' href="#top"><?php echo $GLOBALS['l_top']; ?></a>
	</div>
	<div class='footer'>
	 Powered by <a class='minilink' href="http://www.ralpharama.co.uk/tpf/">TinyPHPForum++</a> v<?php echo $version; ?> &copy;2009<br />
	 <a class='minilink' href="mailto:<?php echo $email; ?>">email</a> | <a class='minilink' href="http://jigsaw.w3.org/css-validator/check/referer">CSS Valid</a> | <a class='minilink' href="http://validator.w3.org/check?uri=referer">HTML 4.01 Valid</a>
	 <?php if ($hitcounter) echo " | ".$GLOBALS['l_topicCol4'].": ".getFile("forums/indexhits.txt"); ?>
	</div>
 </td>
</tr>

<tr><td><a id="bot"></a></td></tr>