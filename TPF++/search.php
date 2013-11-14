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
	$siteName .= " - ".$GLOBALS['l_search'];

	$Results=0;
	$search=$_GET["query"];		// Search term
	//$search=strtoupper($search);
	$searcharray	= str_word_count($search, 1);
	$searchwords = count($searcharray);
	$buffer="";

	// Works, kind of.	No, really, it does...	It's kind of complex...[with TPF++ the search looks just too simple LOL]
	function search($z)
	{
		global $search;
		global $searcharray;
		global $searchwords;
		global $Results;
		global $skin;
		global $codex;
		
		$results=$codex->select_where("posts","strpos(\$description,'$search')!==FALSE");
		if($results==false)
		{
		return false;
		}
		
		foreach($results as $result)
		{
			//$match=false;			
			//if(strpos($result["description"],$search)!==FALSE)
			//{
			//$match=true;	//	Results; //global search count++
			//}
						//if ($match==true)
						
							echo "<tr>\n <td class='filler' colspan='3'></td>\n</tr>\n";

							echo "<tr valign='top'>\n";

							//$file=str_replace(".dat","",$file);
							// This routine extracts numbers between 
							// the '/'s in the path $z
							//$tmp=strrpos($z, "/");
							//$t=substr($z, $tmp+1);
							//$tmp2=substr($z, 0, $tmp);
							//$tmp=strrpos($tmp2, "/");
							//$f=substr($tmp2, $tmp+1);
							//$nm=getFile("forums/" . $f . "/name.txt") . " / " . getFile("forums/" . $f . "/" . $t . "/name.txt");

							echo " <td class='col1bt'>\n";
							echo "  <a class='link' href='index.php?f=" .$result["category"]. "&amp;t=" .$result["uid"]."#x".$result['time_stamp']."'>" .getSummary($result["description"],25). "</a>\n";
							echo " </td>\n";

							echo " <td class='col3bt'>\n";
							//$fi="forums/" . $f . "/" . $t . "/author.txt";
							echo $result["author"]."\n";
							echo " </td>\n";

							echo " <td class='col4bt'>\n";
							//$fi="forums/" . $f . "/" . $t . "/lastpost.txt";
							echo "-"."\n";
							echo " </td>\n";

							echo "</tr>\n";

							echo "<tr valign='top'>\n";
							echo " <td class='coltxt' colspan='3'>\n";
							echo getSummary($result["description"],250) . "<br>\n";
							echo " </td>\n";
							echo "</tr>\n";

							//$results++;
						//}
		}
			$Results=$codex->matches;
	}

	// Just get the first 250 chars of the file
	function getSummary($f,$i)
	{
		
		
			$f = strip_tags($f);
			$f = str_replace("&nbsp;", " ", $f);
			$f = substr($f, 0,$i) . "...";
		
		return $f;
	}
?>
-->

<!-- HTML Page starts here - header.php is always the same for every file -->
<?php include("header.php"); ?>

<!-- This is the page content listing -->
<?php
	// If openForum==false, don't show aything
	if ($openForum===TRUE or $user!=="Guest")
	{
		echo "<div class='barre'>\n <a class='barreLien' href='index.php' title='Index'>". $title . "</a> | ".$GLOBALS['l_searchResults']." '" . $search . "'\n</div>\n";
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr>\n";
		echo " <td class='col1tp' width='70%'>".$GLOBALS['l_searchMatchPost']."</td>\n";
		echo " <td class='col2tp' width='15%'>".$GLOBALS['l_topicCol2']."</td>\n";
		echo " <td class='col3tp' width='15%'>".$GLOBALS['l_searchLastPost']."</td>\n";
		echo "</tr>\n";
		search("forums");
		echo "</table>\n";
		echo "<br>" . $Results . " ".$GLOBALS['l_searchNumber']." '" . $search . "'\n";
	}
	else
	{
		echo "<div class='barre'>\n";
		echo " Please Login\n";
		echo "</div>\n<br>";
		echo "<span class='quote'>".$GLOBALS['l_guestError']."</span>";
	}

?>

<!-- This is the menu bar on the right hand side -->
<?php include("menu.php"); ?>

</table>

</body>
</html>