<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- PHP routines are here!
<?php
	include ("config.php");
	include ("adminchk.php");	// Check if user is admin
	include ("lang/lang_".$lang.".php");
?>
-->
<html lang="en">

<head>
 <link rel="SHORTCUT ICON" href="favicon.ico">
 <title><?php echo $GLOBALS['l_forHelp']; ?></title>
 <meta name="description" content="<?php echo $title; ?>">
 <meta name="keywords" content="forum <?php echo $title; ?>">
 <meta http-equiv="content-type" content="text/html; charset=utf-8">
 <!-- CSS Sheet -->
 <link rel="stylesheet" type="text/css" href="image/skin/<?php echo $skin; ?>/style.css">
</head>

<body>
<div class='barre'><b><?php echo $GLOBALS['l_forHelp']; ?></b> | <a class="barreLien" href='javascript:close();'><?php echo $GLOBALS['l_forClose']; ?></a></div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
	<td class="editionDark"><pre><?php echo $GLOBALS['l_forText']; ?></pre></td>
	<td class="editionDark">[pre]<?php echo $GLOBALS['l_forText']; ?>[/pre]</td>
 </tr>
 <tr>
	<td class="editionLight"><b><?php echo $GLOBALS['l_forBold']; ?></b></td>
	<td class="editionLight">[b]<?php echo $GLOBALS['l_forText']; ?>[/b]</td>
 </tr>
 <tr>
	<td class="editionDark"><em><?php echo $GLOBALS['l_forItalic']; ?></em> </td>
	<td class="editionDark">[i]<?php echo $GLOBALS['l_forText']; ?>[/i]</td>
 </tr>
 <tr>
	<td class="editionLight"><div class='quote'><?php echo $GLOBALS['l_forQuote']; ?></div></td>
	<td class="editionLight">[q]<?php echo $GLOBALS['l_forText']; ?>[/q] </td> 
 </tr>
 <tr>
	<td class="editionDark"><a href="index.php"><?php echo $GLOBALS['l_forLink']; ?></a> </td>	
	<td class="editionDark">[a]http://www.address.com[/a] </td> 
 </tr>
 <tr>
	<td class="editionLight"><img src="image/skin/<?php echo $skin; ?>/pic.gif"> </td>
	<td class="editionLight">[img]http://www.address.com/pic.jpg[/img] </td> 
 </tr>
 <tr>
	<td class="editionDark"><img src="emoticons/smile.gif"</td> 
	<td class="editionDark"><?php echo $GLOBALS['l_forSmile']; ?> :) :-)</td>
 </tr>
 <tr>
	<td class="editionLight"><img src="emoticons/wink.gif"</td>
	<td class="editionLight"><?php echo $GLOBALS['l_forWink']; ?> ;) ;-) ,) ,-) </td>
 </tr>
 <tr>
	<td class="editionDark"><img src="emoticons/laugh.gif"</td> 
	<td class="editionDark"><?php echo $GLOBALS['l_forLaugh']; ?> :D :-D </td>
 </tr>
 <tr>
	<td class="editionLight"><img src="emoticons/indifferent.gif"</td>	
	<td class="editionLight"><?php echo $GLOBALS['l_forIndifferent']; ?> :| :I :-| :-I </td>
 </tr>
 <tr>
	<td class="editionDark"><img src="emoticons/sad.gif"</td>
	<td class="editionDark"><?php echo $GLOBALS['l_forSad']; ?> :( :-( </td>
 </tr>
 <tr>
	<td class="editionLight"><img src="emoticons/wry.gif"</td>
	<td class="editionLight"><?php echo $GLOBALS['l_forWry']; ?> :\\</td>
 </tr>
</table>
</body>
</html>