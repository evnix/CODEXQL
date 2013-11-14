<?php
	//error_reporting(E_ALL);
require_once("init-codex.php");
	// Returns an array of forums - id, desc, name
	function getForums()
	{
		$count=0;
		$dirlist = array();
		$start="forums/";
		$file="";
		$url="";
		global $codex;
		$resource=$codex->select_where("forums");
		if($resource==false) //no results found so return
		{
		return $dirlist;
		}
		$resource=$codex->order_by($resource,"order_id",SORT_ASC);

		foreach($resource as $val)
		{
			//$dirlist[$count]->id=$val["uid"];// this is not correct!** 
			$dirlist[$count]["id"]=$val["uid"]; //this is perfecto
			$dirlist[$count]["last_post_time"]=$val["last_post_time"];
			$dirlist[$count]["last_post_by"]=$val["last_post_by"];
			$dirlist[$count]["last_post_topic_id"]=$val["last_post_topic_id"];
			$dirlist[$count]["desc"]=$val["description"];  //getFile($url . "/desc.txt");
			$dirlist[$count++]["name"]=$val["name"];	//getFile($url . "/name.txt");
		}
		return $dirlist;
	}

	// Return all the topics in this forum - id, description, 'name'
	function getTopics($f,$from,$nums)
	{
		global $skin;
		$count=0;
		$dirlist = array();
		$start="forums/" . $f . "/";
		global $codex;
		$topics=$codex->select_where("topics","\$category=='$f'");
		//var_dump($topics);
		if($topics==false)
		{
		return $dirlist;
		}
		$topics=$codex->order_by($topics,"last_post_time",SORT_DESC); //sort them according to last posted
		$topics=$codex->limit($topics,$from,$nums); //just as: SELECT *FROM blablah LIMIT $from,$nums;
		if($topics==false)
		{
		return $dirlist;
		}
			foreach($topics as $topic)
			{
						$dirlist[$count]["time"]=$topic["time_stamp"]; //filemtime($url);
						$dirlist[$count]["id"]=$topic["uid"]; //$file;
						$dirlist[$count]["author"]=$topic["author"];
						$dirlist[$count]["last_post_time"]=$topic["last_post_time"];
						$dirlist[$count]["last_post_by"]=$topic["last_post_by"];
						$dirlist[$count]["desc"]=""; //$topic["de"] //getFile($url . "/desc.txt");
						$dirlist[$count]["name"]=$topic["name"]; // getFile($url . "/name.txt");
						// If this starts with ! it is a headline, so ad gfx and move to top of list
						if (substr($dirlist[$count]["name"],0,1)=='!')
						{
							if (file_exists("image/skin/".$skin."/headline.gif"))
							{
								$dirlist[$count]["name"]="<img src='image/skin/".$skin."/headline.gif' alt='".$GLOBALS['l_getHead']."'> " . substr($dirlist[$count]["name"],1);
							}
							else
							{
								$dirlist[$count]["name"]="[!] " . substr($dirlist[$count]["name"],1);
							}
							$dirlist[$count]["time"]=time();
						}
						++$count;
			}	
		
		return $dirlist;
	}

	// Count how many topics are in this forum
	function countTopics($f)
	{
		$count=0;
		$start="forums/" . $f . "/";
		if($dir=opendir($start))
		{
			while (($file=readdir($dir))!=FALSE)
			{
				if ($file!="." and $file!="..")
				{
					$url=$start . $file;
					if (is_dir($url) and $file!=="_vti_cnf") $count++;
				}
			}
			closedir($dir);
		}
		return $count;
	}

	// Return all posts for this page as an array - id, time, poster, desc
	function getPosts($f, $t)
	{
		$dirlist = array();
		$count=0;
		$start="forums/" . $f . "/" . $t . "/";
		global $codex;
		$posts=$codex->select_where("posts","\$uid==$t");
		//		var_dump($posts);
		if($posts==false)
		{
		return $dirlist;
		}
		foreach($posts as $post)
		{	
			$dirlist[$count]["id"]=$post["uid"]; //str_replace(".dat","",$file);  // ID number used for link as #num
			$dirlist[$count]["time"]=$post["time_stamp"]; //filemtime($url);
			$dirlist[$count]["poster"]=$post["author"]; //getFile(str_replace(".dat",".usr",$url));
			$dirlist[$count++]["desc"]=$post["description"]; //getFile($url);
		//$count++;
		}				
		return $dirlist;
	}

	// Count how many posts in this topic
	function countPosts($f, $t)
	{
		$count=0;
		$start="forums/" . $f . "/" . $t . "/";
		if($dir=opendir($start))
		{
			while (($file=readdir($dir))!=FALSE)
			{
				if ($file!="." and $file!="..")
				{
					if (strpos($file, ".dat")>-1) $count++;
				}
			}
			closedir($dir);
		}
		return $count;
	}

	// This reads in a file devoid of nasty tags
	function getFile($f)
	{
		$files=" ";
		if (file_exists($f))
		{
			$handle = fopen($f, "r");
			//$files = file_get_contents($f);
			$files = implode("",file($f));
			fclose($handle);
			$files=str_replace("<html>","",$files);
			$files=str_replace("</html>","",$files);
			$files=str_replace("<head>","",$files);
			$files=str_replace("</head>","",$files);
			$files=str_replace("<body>","",$files);
			$files=str_replace("</body>","",$files);
		}
		return $files;
	}


	// Returns an array of users on-line
	function getUsersOnLine()
	{
		$count=0;
		$start="users/";
		$curTime=time();
		$dirlist=NULL;
		if($dir=opendir($start))
		{
			while (($file=readdir($dir))!=FALSE)
			{
				if ($file!="." and $file!="..")
				{
					$url=$start . $file;
					if (strpos($file, ".time")>-1)
					{
						$dirlist[$count]["user"]=str_replace(".time","",$file);
						$dirlist[$count]["tm"]=getFile($url);
						if ($curTime>$dirlist[$count]["tm"]+60)
						{
							unlink($url);
						}
						$count++;
					}
				}
			}
			closedir($dir);
			if ($dirlist!==NULL) sort($dirlist);	// Sort by ID
		}
		return $dirlist;
	}

	// Show user info (for posts, view profile etc) shows details and avatar
	function showUser($u)
	{
		global $codex;
		$ray=$codex->select_where("users","\$username=='$u'");
		if($ray!=false)
		{
		$cdx_group=$ray[0]["user_group"];
		$cdx_image=$ray[0]["avatar"];
		$cdx_skin=$ray[0]["skin"];
		$cdx_language=$ray[0]["language"];
		$cdx_posts=file_get_contents("users/".$u.".hits");
		$cdx_statement=$ray[0]["statement"];
		}
		else
		{
		
		$cdx_group="invalid user";
		$cdx_image="image/avatars/unknown/x.gif";
		$cdx_skin="XXXX";
		$cdx_language="XXXX";
		$cdx_posts="XXXX";
		$cdx_statement="User is Deleted Or an Unkown Error Occured";
				
		}
		echo "<a class='link' href='profile.php?action=view&amp;uname=" . $u . "'>" . $u . "</a>\n";
		$t="users/" . $u . ".info";
		if (file_exists($t)) 
		{
			if (strpos(getFile($t), "admin")>-1) echo "<br>\n<span class='little'>("
				."{$GLOBALS['l_ForumAdmin']})\n</span>\n";
		}
		echo "<br><img src='" . $cdx_image. "' alt='Avatar'>\n";
		echo "<br>\n<span class='little'>".$GLOBALS['l_profSkin'].": " .$cdx_skin. "\n</span>\n";
		echo "<br>\n<span class='little'>".$GLOBALS['l_profLang'].": " .$cdx_language. "\n</span>\n";
		echo "<br>\n<span class='little'>".$GLOBALS['l_getPosts'].": " .$cdx_posts. "\n</span>\n";
		echo "<br>\n<div class='altquote'>\n" .$cdx_statement. "\n</div>\n<br>\n";
		
		return $ray;
	}

	// Show user lite (for last post)
	function showLUser($u)
	{
		echo "<a class='link' href='profile.php?action=view&amp;uname=" . $u . "'>" . $u . "&nbsp;</a>";
	}

	// Used when editing a post - transforms html into forum-coded text
	function decode($txt)
	{
		// Emoticons
		$txt=str_replace("<img src='emoticons/smile.gif'>",":)",$txt);		// Smile
		$txt=str_replace("<img src='emoticons/wink.gif'>",";)",$txt);		// Wink
		$txt=str_replace("<img src='emoticons/laugh.gif'>",":D",$txt);		// Laugh
		$txt=str_replace("<img src='emoticons/indifferent.gif'>",":I",$txt);	// Indifferent
		$txt=str_replace("<img src='emoticons/sad.gif'>",":(",$txt);		// Sad
		$txt=str_replace("<img src='emoticons/wry.gif'>",":\\",$txt);		// Wry

		// Formatting
		$txt=str_replace("<pre>","[pre]",$txt);			// Pre start
		$txt=str_replace("</pre>","[/pre]",$txt);		// Pre end
		$txt=str_replace("<b>","[b]",$txt);			// Bold start
		$txt=str_replace("</b>","[/b]",$txt);			// Bold end
		$txt=str_replace("<div class='quote'>","[q]",$txt);	// Quote start
		$txt=str_replace("<div id='quote'>","[q]",$txt);	// Quote start
		$txt=str_replace("</div>","[/q]",$txt);			// Quote end
		$txt=str_replace("<em>","[i]",$txt);			// Italic Start
		$txt=str_replace("</em>","[/i]",$txt);			// Italic End
		$txt=str_replace("<a href='","[a]",$txt);		// Link
		$txt=str_replace("' class='link'>link</a>","[/a]",$txt); // Link
		$txt=str_replace("'>link</a>","[/a]",$txt); 		// Link End
		$txt=str_replace("'link","",$txt); 		// Fix bad links in old posts
		$txt=str_replace("<img src='","[img]",$txt);		// Image
		$txt=str_replace("' name='pic'>","[/img]",$txt);	// Image End

		// Generic slash, newline, tag stripping
		$txt=stripslashes($txt);
		$txt=strip_tags($txt);
		$txt=str_replace(array("<",">"),"",$txt);

		return $txt;
	}

	// For menu bar on right hand side, forum list
	function showMiniForums()
	{
		global $CDX_FORUMS_EXISTS;
		global $CDX_FORUMS;
		echo "<a class='link' href='index.php'>".$GLOBALS['l_index']."</a><br>\n";
		if($CDX_FORUMS_EXISTS==TRUE) 	//try and save some resources
		{
		$ourDir=$CDX_FORUMS;
		}
		else
		{
		$ourDir = getForums();
		}
		for ($i=0; $i<count($ourDir); $i++)
		{
			echo "<a class='link' href='index.php?f=" .  $ourDir[$i]["id"]. "'>" . $ourDir[$i]["name"] . "</a><br>\n";
		}
	}

	// Get highest ID from an array
	function getHighest($a)
	{
		$h=0;
		for ($i=0; $i<count($a); $i++)
		{
			if ($a[$i]["id"]>$h) $h=$a[$i]["id"];
		}
		return $h;
	}

	// Increments counter for topic view hits
	// Based on freeware script by http://www.cj-design.com/?id=forum
	function hitme($fname)
	{
		if (file_exists($fname) and is_writable($fname))
		{
			$handle = fopen($fname, "r+");
			flock($handle, 1);
			$count = fgets($handle, 4096);
			if ($count==NULL) $count=0;
			$count++; 
			fseek($handle, 0);
			flock($handle, 3);
 			fwrite($handle, $count);
			fclose($handle);
		}
		elseif (!file_exists($fname))
		{
			$handle = fopen($fname, 'w');
			fwrite($handle, "1");
			fclose($handle);	
		}
	}
?>