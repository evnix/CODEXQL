<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- PHP routines are here!
<?php
	//error_reporting(E_ALL);
	include ("config.php");
	include ("adminchk.php");	// Check if user is admin
	include ("lang/lang_".$lang.".php");

	function calc_integ($str)
	{
	return (int)$str;
	}
	

	// Returns an array of forums - id, desc, name
	function listAvatars()
	{
		$col=0;
		$flip=0;
		$start="image/avatars/";
		if($dir=opendir($start))
		{
			while (($file=readdir($dir))!=FALSE)
			{
				if ($file!="." and $file!=".." and calc_integ($file)==0 and $file!="unknown")
				{
					if (!strpos($file, "DS_Store") and !strpos($file, ".htm") and !strpos($file, ".php"))
					{
						if ($col==0)
						{
							echo " <tr>\n";
						}
						$url=$start . $file;
						if ($flip==0) 
						{
							echo "  <td class='editionLight'>\n";
							$flip=1;
						}
						else
						{
							echo "  <td class='editionDark'>\n";
							$flip=0;
						}
						echo "   <img src='".$url."' alt='".$file."'>\n";
						echo "   <br>".$file."\n";
						echo "  </td>\n";
						$col++;
						if ($col==3)
						{
							$col=0;
							echo " </tr>\n";
						}
					}
				}
			}
			closedir($dir);
		}
		if ($col <3)
		{
			while ($col < 3)
			{
				echo "  <td></td>\n";
				$col++;
			}
			echo " </tr>\n";
		}
	}
?>
-->
<html lang="en">

<head>
 <link rel="SHORTCUT ICON" href="favicon.ico">
 <title><?php echo $GLOBALS['l_avatarGallery']; ?></title>
 <meta name="description" content="<?php echo $title; ?>">
 <meta name="keywords" content="forum <?php echo $title; ?>">
 <meta http-equiv="content-type" content="text/html; charset=utf-8">
 <!-- CSS Sheet -->
 <link rel="stylesheet" type="text/css" href="image/skin/<?php echo $skin; ?>/style.css">
</head>
	<!-- Body -->
<body>
<div class='barre'><b><?php echo $GLOBALS['l_avatarGallery']; ?></b> | <a class="barreLien" href='javascript:close();'><?php echo $GLOBALS['l_forClose']; ?></a></div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">

<?php listAvatars(); ?>

</table>

<div class='barre'><a class="barreLien" href='javascript:close();'><?php echo $GLOBALS['l_forClose']; ?></a></div>

</body>
</html>