<html>
	<!-- Head -->
<head>
 <link rel="SHORTCUT ICON" href="favicon.ico">
 <title><?php echo $siteName; ?></title>
 <meta name="description" content="<?php echo $title; ?>">
 <meta name="keywords" content="forum <?php echo $title; ?>">
 <meta http-equiv="content-type" content="text/html; charset=utf-8">
 <!-- CSS Sheet -->
 <link rel="stylesheet" type="text/css" href="image/skin/<?php echo $skin; ?>/style.css">
 <script type="text/javascript" language="javascript" charset="utf-8">
 <!--
 function popup(page) 
 {
	var titre="popup";
	var top=(screen.height-350)/2;
	var left=(screen.width-350)/2;
	window.open(page, titre, "top="+top+",left="+left+",width=420,height=420,menubar=no,scrollbars=yes,statusbar=no");
 }
 -->
</script> 
</head>
	<!-- Body -->
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr valign="top">

<!-- Top of page - Logo.gif and link to $siteURL here -->

  <td class="mainlogo" colspan="2">
   <a href="<?php echo $siteURL; ?>">
    <?php 
	if (file_exists("image/skin/".$skin."/logo.gif"))
	{
		echo "<img src='image/skin/".$skin."/logo.gif' alt='Home'>";
	}
	else
	{
		echo $siteName;
	}
    ?>
   </a> 
  </td>
 </tr>
 <tr valign="top">
  <td class="tp">
