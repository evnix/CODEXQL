<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- Forum (c)2004, Ralph Capper.  stuff@ralpharama.co.uk           -->
<!-- Design also by Laurent Baumann - www.design-graphique.net      -->
<!-- GNU GPL Open Source, if you retain links to ralpharama.co.uk   -->

<!-- PHP routines are here!-->
<?php
	//error_reporting(E_ALL);
	//error_reporting(E_ALL);
	include ("config.php");

	require_once("init-codex.php");
	//$codex->add_column("users","user_group");
	include ("adminchk.php");	// Check if user is admin
	include ("getdir.php");
	include ("lang/lang_".$lang.".php");

	
	$CDX_FORUMS=array(); 
	/*This Holds forum results which will be called in index and mini forums 
	try'n save some resources*/
	
	$CDX_FORUMS_EXISTS=FALSE; //Check if forums table is queried or not
	
	
	// Format of URL is f=1&t=1&p=1
	// Format of filename is f / t / p.txt
	if(isset($_GET["f"])) { $f = $_GET["f"]; } else { $f = 0; }							// Get forum
	if(isset($_GET["t"])) { $t = $_GET["t"]; } else { $t = 0; }							// Get topic
	if(isset($_GET["p"])) { $p = $_GET["p"]; } else { $p = 0; }							// Which post?  0=none, or new post
	if(isset($_GET["action"])) { $action = $_GET["action"]; } else { $action = ""; }	// Action
	if(isset($_GET["page"])){ $CDX_page=$_GET["page"]; }else{ $CDX_page=1; }				//pagination of topics
	$buffer="";

	// Show a list of forums
	function showForums()
	{
		global $lang;
		global $admin;
		global $user;
		global $f;
		global $t;
		global $skin;
		global $title;
		global $buffer;
		global $codex;
		global $CDX_FORUMS;
		global $CDX_FORUMS_EXISTS;
		
		
		echo "<div class='barre'>\n ". $title . "\n</div>\n";

		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr>\n";
		echo " <td class='col1tp' width='55%'>".$GLOBALS['l_forumCol1']."</td>\n";
		echo " <td class='col2tp' width='15%'>".$GLOBALS['l_forumCol2']."</td>\n";
		echo " <td class='col3tp' width='15%'>".$GLOBALS['l_forumCol3']."</td>\n";
		echo " <td class='col4tp' width='15%'>".$GLOBALS['l_forumCol4']."</td>\n";
		echo "</tr>\n";
		
		$CDX_FORUMS=getForums();
		$ourDir = $CDX_FORUMS;
		$CDX_FORUMS_EXISTS=TRUE;
		
		
		for ($i=0; $i<count($ourDir); $i++)
		{
			echo "<tr><td class='filler' colspan='4'></td></tr>\n";
			echo "<tr valign='top'>\n";

			// Name
			echo " <td class='col1bt'>\n";
			echo "  <a class='topicLink' href='index.php?f=" .  $ourDir[$i]["id"] . "&page=1'>" . $ourDir[$i]["name"] . "</a>\n";
			echo " </td>\n";

			// Num Topics
			echo " <td class='col2bt'>\n";
			$f_id=$ourDir[$i]["id"];
			$codex->select_where("topics","\$category==$f_id");
			echo "  ".$codex->matches."\n";
			echo " </td>\n";

			// Last Posts
			$fi="forums/" . $ourDir[$i]["id"] . "/lastpost.txt";
			echo " <td class='col3bt'>\n";
			if($ourDir[$i]["last_post_time"]=="" || $ourDir[$i]["last_post_time"]==null)
			{
			$ourDir[$i]["last_post_time"]="1243501360"; //dummy time when new forum is created
			}
			echo "  ".date("d.M.y",$ourDir[$i]["last_post_time"])." <span class='little'>".date("g:ia",$ourDir[$i]["last_post_time"])."</span>\n";
			echo "</td>\n";

			// By Poster
			echo " <td class='col4bt'>\n";
			echo "  ".showLUser($ourDir[$i]["last_post_by"]);
			// Link to last post
			$fi="forums/" . $ourDir[$i]["id"] . "/lastlink.txt";
			echo "<a href='index.php?f=".$ourDir[$i]["id"]."&t=".$ourDir[$i]["last_post_topic_id"]. "'><img src='image/skin/" . $skin . "/lastpost.gif' alt='Go'></a>\n";
			echo " </td>\n";

			echo "</tr>\n";

			// Description
			echo "<tr valign='top'>\n";
			echo " <td class='coltxt' colspan='4'>\n";
			if ($admin===TRUE)
			  echo "<a class='optionLink' href='index.php?action=edit&amp;f="
			    . $ourDir[$i]["id"] . "&amp;t=-1'>{$GLOBALS['l_edit']} &gt;</a>\n";
			echo $ourDir[$i]["desc"];
//			if ($admin===TRUE) echo " <a href='index.php?action=edit&amp;f=". $ourDir[$i]->id . "&amp;t=-1'><img src='image/skin/" . $skin . "/edit.gif' alt='Edit' title='Edit' align='right'></a>\n";
			echo " </td>\n";
			echo "</tr>\n";
		}
		echo "<tr><td class='filler' colspan='4'></td></tr>\n";
		echo "</table>";
		// Show new forum option for admin users
		if ($admin===TRUE) $buffer="<a class='barreLien' href='index.php?action=edit&amp;f=-1'>".$GLOBALS['l_forumNew']."</a> | ";
	}

	// Show all topics in this forum
	function showTopics($f)
	{
		global $admin;
		global $user;
		global $f;
		global $t;
		global $skin;
		global $title;
		global $buffer;
		global $siteName;
		global $codex;
		global $CDX_page; //topic pagination!!!
		global $topics_per_page; //topic pagination defined in config.php

		echo "<div class='barre'>\n";
		echo " <a class='barreLien' href='index.php'>".$siteName."</a> | " . getFile("forums/" . $f . "/name.txt") . "\n";
		echo "</div>\n";

		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr>\n";
		echo " <td class='col1tp' width='45%'>".$GLOBALS['l_topicCol1']."</td>\n";
		echo " <td class='col3tp' width='11%'>".$GLOBALS['l_topicCol2']."</td>\n";
		echo " <td class='col2tp' width='11%'>".$GLOBALS['l_topicCol3']."</td>\n";
		echo " <td class='col2tp' width='11%'>".$GLOBALS['l_topicCol4']."</td>\n";
		echo " <td class='col3tp' width='11%'>".$GLOBALS['l_topicCol5']."</td>\n";
		echo " <td class='col4tp' width='11%'>".$GLOBALS['l_topicCol6']."</td>\n";
		echo "</tr>\n";
		$L_point=(($CDX_page*$topics_per_page)-$topics_per_page)-1;
		
		if($L_point<1){$L_point=-1;}
		$ourDir = getTopics($f,$L_point,$topics_per_page);
		$num_forums=count($ourDir);
		$i=0;
		for ($i=0; $i<$num_forums; $i++)
		{
			echo "<tr><td class='filler' colspan='6'></td></tr>\n";
			echo "<tr valign='top'>\n";

			// Name
			echo " <td class='col1bt'>\n";
			echo "  <a class='topicLink' href='index.php?f=" . $f . "&amp;t=" .  $ourDir[$i]["id"] . "'>" . $ourDir[$i]["name"] . "</a>\n";
			echo " </td>\n";

			// Author
			echo " <td class='col3bt'>\n";
			/* $fi="forums/" . $f . "/" . $ourDir[$i]["id"] . "/author.txt";
			if (file_exists($fi)) echo "  ".showLUser(getFile($fi)). "\n";
			else echo "  -\n"; */
			echo showLuser($ourDir[$i]["author"])."\n";
			echo " </td>\n";

			// Num Posts
			
			echo " <td class='col2bt'>\n";
			$idx=$ourDir[$i]["id"];
			$x=$codex->select_where("posts","\$uid==$idx and \$category==$f");
			$num=$codex->matches;
			echo "  ".$num."\n";

			//echo "  ".countPosts($f, $ourDir[$i]["id"])."\n";
			echo " </td>\n";

			// Post view hits
			echo " <td class='col3bt'>\n";
			$fi="forums/" . $f . "/" . $ourDir[$i]["id"] . "/hits.txt";		// Show view counter
			if (file_exists($fi)) echo "  ".getFile($fi) . "\n";
			echo " </td>\n";

			// Last Posts
			$fi="forums/" . $f . "/" . $ourDir[$i]["id"] . "/lastpost.txt";
			echo " <td class='col4bt'>\n";
			echo "  ".date("d.M.y",$ourDir[$i]["last_post_time"])." <span class='little'>".date("g:ia",$ourDir[$i]["last_post_time"])."</span>\n";
			echo " </td>\n";

			// By Poster
			echo " <td class='col5bt'>\n";
			echo showLUser($ourDir[$i]["last_post_by"]);
			// Link to last post
			echo " <img src='image/skin/" . $skin . "/lastpost.gif' alt='Go'>\n";
			echo " </td>\n";

			echo "</tr>\n";

			// Description
			echo "<tr valign='top'>\n";
			echo " <td class='coltxt' colspan='6' align='right'>\n";
			if ($admin===TRUE) 
			{
				echo "<a class='optionLink' href='index.php?action=edit&amp;f=" . $f
				  ."&amp;t={$ourDir[$i]['id']}&amp;p=-2'>[{$GLOBALS['l_edit']}]</a></td>\n";
			} 
			else 
			{
				echo "&nbsp;</td>\n";
			}
			echo "</tr>\n";
		}
		echo "<tr><td class='filler' colspan='6'></td></tr>\n";
		echo "</table>\n";
		
		
		/*pagination code starts */
		if($CDX_page>1)
		{
		$buffer.="<a class='barreLien' href='index.php?f=". $f . "&amp;page=". ($CDX_page-1) ."'>Previous</a> | ";
		}
		
		if($i>=$topics_per_page)
		{
		$buffer.="<a class='barreLien' href='index.php?f=". $f . "&amp;page=". ($CDX_page+1) ."'>Next</a> | ";
		}
		
		/*Pagination code ends */
		$buffer.="<a class='barreLien' href='index.php?action=edit&amp;f=". $f . "&amp;p=-1'>".$GLOBALS['l_topicNew']."</a> | ";
	}

	// Show all posts in this forum/topic
	function showPosts($f, $t)
	{
		global $admin;
		global $user;
		global $skin;
		global $title;
		global $buffer;
		global $siteName;
		global $codex;
		$fname = "forums/" .$f. "/" .$t. "/hits.txt";
		hitme($fname);		// Inc view count
		
		$ray=$codex->select_where("topics","\$uid==$t and \$category==$f");
		$ray_tname=$ray[0]["name"];
		
		$ray=$codex->select_where("forums","\$uid==$f");
		$ray_fname=$ray[0]["name"];
		echo "<div class='barre'>\n <a class='barreLien' href='index.php'>".$siteName."</a> |";
		echo " <a class='barreLien' href='index.php?f=" . $f . "'>" .$ray_fname. "</a> | ";
		echo $ray_tname. "\n</div>\n";
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr><td class='filler' colspan='2'></td></tr>\n";

		$ourDir = getPosts($f, $t);
		//var_dump($ourDir);
		for ($i=0; $i<count($ourDir); $i++)
		{
			if ($i>0) echo "<tr><td class='filler' colspan='2'></td></tr>\n";

			echo "<tr valign='top'>\n";

			// Poster
			echo " <td class='foruml'>\n";
			echo "  <a id='x" . $ourDir[$i]["time"] .  "'></a>\n  ";
			showUser($ourDir[$i]["poster"]);
			echo "  <br>\n  <span class='little'>\n   " . date("d.M.y",$ourDir[$i]["time"])." <span class='little'>".date("g:ia",$ourDir[$i]["time"])."</span>\n";
			echo "\n  </span>\n";
			echo " </td>\n";

			// Description
			echo " <td class='courant'>\n";
			echo $ourDir[$i]["desc"];
			echo " </td>\n";

			echo "</tr>\n";
			
			// Options to edit or split post
			if (($user==$ourDir[$i]["poster"] and $user!="Guest") or $admin===TRUE) 
			{
				echo "<tr><td colspan='2' class='options' align='right'>";
				echo "<a class='optionLink' href='index.php?action=edit&amp;f={$f}"
				  . "&amp;t={$t}&amp;p={$ourDir[$i]["time"]}'>{$GLOBALS['l_edit']}</a>";
				if ($admin===TRUE) 
				{
					//echo " | <a class='optionLink' href='split.php?action=split&amp;f="
					//  .$f."&amp;t=".$t."&amp;p=".$i."'>{$GLOBALS['l_split']}</a>";
				} 
				echo "</td></tr>";
			}
		}
		echo "<tr><td class='filler' colspan='2'></td></tr>\n";
		echo "</table>";
		$buffer="<a class='barreLien' href='index.php?action=edit&amp;f=". $f . "&amp;t=" . $t . "&amp;p=0'>".$GLOBALS['l_postReply']."</a> | ";
	}

	// New post - Where we post depends on values of $f, $t and $p
	function newPost()
	{
		global $admin;
		global $f;
		global $t;
		global $p;
		global $user;
		global $uStat;
		global $skin;
		global $title;
		global $siteName;
		global $codex;

		if ($f>0) echo "<div class='barre'>\n <a class='barreLien' href='index.php'>".$siteName."</a> | <a class='barreLien' href='index.php?f=" . $f . "'>" . getFile("forums/" . $f . "/name.txt") . "</a>\n</div>\n";

		// Must be logged in, or else we can still post in a topic as a guest
		if ($uStat===TRUE or ($f>0 and $t>0 and $p==0))
		{
			echo "<form action='action.php' method='POST'>\n";
			echo "<input type=\"hidden\" id=\"u". umask() ."\" name=\"sessionid\" value=\"{$_REQUEST['PHPSESSID']}\">\n";
			echo "<input type='hidden' name='fid' value='" . $f . "'>\n";
			echo "<input type='hidden' name='tid' value='" . $t . "'>\n";
			echo "<input type='hidden' name='pid' value='" . $p . "'>\n";

			// Post new post within new topic
			if ($f>0 and $p==-1)
			{
				echo "<div class='editionDark'>".$GLOBALS['l_newPostTopic']."</div>\n";
				echo "<div class='editionLight'>\n";
				echo " ".$GLOBALS['l_topicCol1']." <input type='text' name='tname' value='".$GLOBALS['l_newPostTopicName']."' size='45'> | ".$GLOBALS['l_newPostTopicHead']."\n";
				echo "</div>\n";
				echo "<div class='editionDark'>\n";
				echo " <textarea rows=15 cols=70 name='txt'>".$GLOBALS['l_newPostTopicText']."</textarea>\n";
				echo " <input type='hidden' name='action' value='npostntopic'>\n";
				echo "</div>\n";
			}
			// Post new post within this topic
			else if ($f>0 and $t>0 and $p==0)
			{
				echo "<div class='editionDark'>".$GLOBALS['l_newPost']."</div>\n";
				echo "<div class='editionLight'>\n";
				$ray=$codex->select_where("topics","\$uid==$t");
				$txray=$ray[0]["name"];
				echo " ".$GLOBALS['l_topicCol1']." " . str_replace("'","&#39;",$txray) . "\n";
				echo "</div>\n";
				echo "<div class='editionDark'>\n";
				echo " <textarea rows=15 cols=70 name='txt'>".$GLOBALS['l_newPostType']."</textarea>\n";
				echo " <input type='hidden' name='action' value='npost'>\n";
				echo "</div>\n";
			}
			// Edit post within this topic
			else if ($f>0 and $t>0 and $p>0)
			{
				echo "<div class='editionDark'>".$GLOBALS['l_editPost']."</div>\n";
				echo "<div class='editionLight'>\n";
				echo " ".$GLOBALS['l_topicCol1']." " . str_replace("'","&#39;",getFile("forums/" . $f . "/" . $t . "/name.txt")) . "\n";
				echo "</div>\n";
				echo "<div class='editionDark'>\n";
				echo " ".$GLOBALS['l_editPostWarn']." <input type='checkbox' name='delete'>\n";
				echo "</div>\n";
				$ray=$codex->select_where("posts","\$time_stamp==$p and \$uid==$t");
				$txt=$ray[0]["description"];
				//$txt=getFile("forums/" . $f . "/" . $t . "/" . $p . ".dat");
				$txt=decode($txt);
				echo "<div class='editionLight'>\n";
				echo " <textarea rows=15 cols=70 name='txt'>\n" . $txt . "\n </textarea>\n";
				echo " <input type='hidden' name='action' value='epost'>\n";
				echo "</div>\n";
			}
			// New forum
			else if ($f==-1)
			{
				echo "<div class='barre'>\n <a class='barreLien' href='index.php'>".$siteName."</a> | ".$GLOBALS['l_newForum']."\n</div>\n";
				echo "<div class='editionLight'>\n";
				echo " ".$GLOBALS['l_forumCol1']." <input type='text' name='fname' value='".$GLOBALS['l_newForumName']."' size='45'>\n";
				echo "</div>\n";
				echo "<div class='editionDark'>\n";
				echo " <textarea rows=3 cols=70 name='txt'>".$GLOBALS['l_newForumDesc']."\n </textarea>\n";
				echo " <input type='hidden' name='action' value='nforum'>\n";
				echo "</div>\n";
			}
			// Edit forum details
			else if ($f>0 and $t==-1)
			{
				echo "<div class='editionDark'>".$GLOBALS['l_editForum']."</div>\n";
				echo "<div class='editionLight'>\n";
				echo "  ".$GLOBALS['l_editForumWarn']." <input type='checkbox' name='delete'>\n";
				echo "</div>\n";
				echo "<div class='editionDark'>\n";
				$ray=$codex->select_where("forums","\$uid==$f");
				$ray_name=$ray[0]["name"];
				$ray_desc=$ray[0]["description"];
				$ray_order_id=$ray[0]["order_id"];
				echo " {$GLOBALS['l_forumCol1']} <input type='text' name='fname' value='". str_replace("'","&#39;",$ray_name). "' size='45'>\n";
				echo "</div>\n";
				echo "<div class='editionLight'>\n";
				echo "\n<input type='hidden' name='fid2' value='".$f."'/>\n";
				echo " #ID <input type='text' name='order_id' value='" .$ray_order_id. "'> ".$GLOBALS['l_editForumID']."\n";
				echo " <textarea rows=3 cols=70 name='txt'>\n" .$ray_desc. "\n </textarea>\n";
				echo " <input type='hidden' name='action' value='eforum'>\n";
				echo "</div>\n";
			}
			// Edit topic details
			else if ($f>0 and $t>0 and $p==-2)
			{
				echo "<div class='editionDark'>".$GLOBALS['l_editTopic']."</div>\n";
				echo "<div class='editionLight'>\n";
				echo " ".$GLOBALS['l_editTopicWarn']." <input type='checkbox' name='delete'>\n";
				echo "</div>\n";
				echo "<div class='editionDark'>\n";
				$ray=$codex->select_where("topics","\$uid==$t and \$category==$f");
				$ray_name=$ray[0]["name"];
				echo " {$GLOBALS['l_topicCol1']} <input type='text' name='tname' value='"
				  . str_replace("'", '&#'. ord("'") .';',$ray_name) . "' size='45'> | "
				  . $GLOBALS['l_newPostTopicHead'] ."\n";
				echo " <input type='hidden' name='action' value='etopic'>\n";
				echo "</div>\n";
			}
			echo "<div class='editionLight'>\n <input type='submit' value='OK'>";
			// Shows formatting options
			echo " | <a class='link' href=\"javascript:popup('formathelp.php')\">".$GLOBALS['l_formatHelp']."</a>\n</div>\n";		
			echo "</form>\n";
		}
		else
		{
			echo "<br><div class='quote'>".$GLOBALS['l_guestError']."<div>\n";
		}
	}
?>
 
<!-- HTML Page starts here - header.php is always the same for every file -->
<?php include("header.php"); ?>

<!-- This is the main page content listing -->

<?php
	// If openForum==false, don't show aything
	if ($openForum===TRUE or $user!=="Guest")
	{
		// We're posting or editing?
		if ($action=="edit")
		{
			newPost();
		}
		// No, just viewing...
		else
		{
			// Display a particular topic?
			if ($f>0 and $t>0)
			{
				showPosts($f, $t);
			}
			// Else, show list of topics in this forum
			else if ($f>0)
			{
				showTopics($f);
			}
			// Show list of forums (main page)
			else
			{
				showForums();
				if ($hitcounter) hitme("forums/indexhits.txt");
			}
		}
	}
	else
	{
		echo "<div class='barre'>\n";
		echo " Please Login\n";
		echo "</div>\n<br>";
		echo "<span class='quote'>You must login, or <a href='profile.php?action=new'>register</a> to use this forum.</span>";
	}
?>


<?php include("menu.php"); ?>

</table>
</body>
</html>