<?php
	session_start();
	//error_reporting(E_ALL);
	require_once("init-codex.php");
	//error_reporting(E_ALL);
	//ob_start();
	include ("config.php");
	include ("adminchk.php");	// Check if user is admin
	include ("lang/lang_{$lang}.php");
	include ("getdir.php");

	if (!checkSession())
	{
		// If we get here, there was an error, so redirect to error page
		header("Location: http://{$_SERVER['HTTP_HOST']}"
			. dirname($_SERVER['PHP_SELF']) .'/error.php?err=200');
	}
	$f=$_POST["fid"];		// Forum ID
	$f2 = isset($_POST["fid2"]) ? $_POST["fid2"] : '';		// Forum ID 2 (if renaming)
	$t=$_POST["tid"];		// Topic ID
	$p=$_POST["pid"];		// Post ID
	$fname = isset($_POST["fname"]) ? $_POST["fname"] : '';		// Forum Name
	$tname = isset($_POST["tname"]) ? $_POST["tname"] : '';		// Topic Name
	$txt = isset($_POST["txt"]) ? $_POST["txt"] : '';		// Description or post body
	$txt = strip_tags($txt);	// Get rid of tags
	$action=$_POST["action"];	// What are we doing again?
	$remove = isset($_POST["delete"]) ? $_POST["delete"] : '';	// Flag for delete

	// Post new post within new topic
	if ($action=="npostntopic")
	{
		global $tname;
		global $txt;
		global $user;
		global $f;

		$txt=fixit($txt);	// Format text
		$tname=fixit($tname);	// Format name
		$p=1;			// First post always 1
		global $codex;


		// Find highest ID, add 1 to it and make this the new ID for this
		$t = time();

		// Create topic dir
		mkdir("forums/" . $f . "/" . $t, 0777);

		// Write name of topic
		//$filename = "forums/" . $f . "/" . $t . "/" . "/name.txt";
		//writeFile($filename, $tname);

		// Write post
		//$filename = "forums/" . $f . "/" . $t . "/" . $p . ".dat";
		//writeFile($filename, $txt);

		// Write poster
		//$filename = "forums/" . $f . "/" . $t . "/" . $p . ".usr";
		//writeFile($filename, $user);

		// Write 'Last Post' for forum listing
		//$filename = "forums/" . $f . "/lastpost.txt";
		//writeFile($filename, $user);
		//$filename = "forums/" . $f . "/lastlink.txt";
		//writeFile($filename, "f=".$f."&amp;t=".$t."#x".$p);

		// Write 'Last Post' for topic listing
		//$filename = "forums/" . $f . "/" . $t . "/lastpost.txt";
		//writeFile($filename, $user);
		//$filename = "forums/" . $f . "/" . $t . "/lastlink.txt";
		//writeFile($filename, "f=".$f."&amp;t=".$t."#x".$p);

		// Write 'Author' for topic
		//$filename = "forums/" . $f . "/" . $t . "/author.txt";
		//writeFile($filename, $user);

		// Write hits.dat file for view count
		$filename = "forums/".$f."/".$t."/"."hits.txt";
		writeFile($filename, "0");

		// Write hit to user stats file
		$filename = "users/" . $user . ".hits";
		hitme($filename);
		
		$codex->insert("topics","uid=$t&category=$f&time_stamp=$t&name=$tname&author=$user&num_posts=1&views=1&last_post_time=$t&last_post_by=$user");
		$codex->commit_changes();
		$codex->insert("posts","uid=$t&time_stamp=$t&name=$tname&description=$txt&author=$user&category=$f");
		$codex->commit_changes();
		$codex->update_where("forums","last_post_time=$t&last_post_by=$user&last_post_topic_id=$t","\$uid=='$f'");
		$codex->commit_changes();
		// Redirect to new file
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f . "&t=" . $t);
	}

	// Post new post within this topic
	if ($action=="npost")
	{
		global $txt;
		global $user;
		global $f;
		global $t;
		$txt=fixit($txt);	// Format text

		// Check the validity of the directories


		// Find highest ID , add 1 to it and make this the new ID for this
		
		$p = time();

		// Write post
		//$filename = "forums/" . $f . "/" . $t . "/" . $p . ".dat";
		//writeFile($filename, $txt);

		// Write poster
		//$filename = "forums/" . $f . "/" . $t . "/" . $p . ".usr";
		//writeFile($filename, $user);

		// Write 'Last Post' for forum listing
		//$filename = "forums/" . $f . "/lastpost.txt";
		//writeFile($filename, $user);
		//$filename = "forums/" . $f . "/lastlink.txt";
		//writeFile($filename, "f=".$f."&amp;t=".$t."#x".$p);

		// Write 'Last Post' for topic listing
		//$filename = "forums/" . $f . "/" . $t . "/lastpost.txt";
		//writeFile($filename, $user);
		//$filename = "forums/" . $f . "/" . $t . "/lastlink.txt";
		//writeFile($filename, "f=".$f."&amp;t=".$t."#x".$p);

		// Write hit to user stats file
		$filename = "users/" . $user . ".hits";
		hitme($filename);
		$codex->insert("posts","uid=$t&time_stamp=$p&description=$txt&author=$user&category=$f");
		$codex->commit_changes();
		$codex->update_where("topics","last_post_time=$p&last_post_by=$user","\$uid=='$t' and \$category=='$f'");
		$codex->commit_changes();
		$codex->update_where("forums","last_post_time=$p&last_post_by=$user&last_post_topic_id=$t","\$uid=='$f'");
		$codex->commit_changes();
		// Redirect to new file
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f . "&t=" . $t . "#x" . $p);
	}

	// Edit post within this topic (nb, user remains unchanged)
	if ($action=="epost")
	{
		global $f;
		global $t;
		global $p;
		global $txt;
		global $user;
		global $codex;
		//$filename = "forums/" . $f . "/" . $t . "/" . $p . ".dat";
		$ray=$codex->select_where("posts","\$time_stamp==$p");
		$poster=$ray[0]["author"];
		
		//$poster=getFile(str_replace(".dat",".usr",$filename));

		if ($user==$poster or $admin===TRUE)
		{
			if ($remove==TRUE and $f>0 and $t>0)
			{
				unlink($filename);
				$codex->delete_where("posts","\$time_stamp=='$p' and \$uid=='$t'");
				$codex->commit_changes();
				// Redirect to new file
				header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f . "&t=" . $t);
			}
			else
			{
				$txt=decode($txt);	// Decode text back to [q] [i] etc
				$txt=fixit($txt);	// Format text
				$txt=$txt . "<br><br><span class='little'>[" . $user . " " . date('d.M.y') . " " .date('g:ia')."]</span>";
				//writeFile($filename, $txt);
				$r=$codex->update_where("posts","description=$txt","\$time_stamp=='$p' and \$uid=='$t'");
				$codex->commit_changes();

				// Redirect to new file
				header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f . "&t=" . $t . "#x" . $p);
			}
		}
	}

	// New forum
	if ($action=="nforum" and $admin==TRUE)
	{
		global $fname;
		global $txt;
		global $codex;
		$fname=fixit($fname);		// Format name

		// Find highest ID in all forums, add 1 to it and make this the new ID for this
		$f = time();

		// Make dir for forum
		mkdir("forums/" . $f, 0777);
		$codex->insert("forums","uid=$f&name=$fname&description=$txt");
		$codex->commit_changes();
		// Write name of forum
		//$filename = "forums/" . $f . "/name.txt";
		//writeFile($filename, $fname);

		// Make desc file for forum
		//$filename = "forums/" . $f . "/desc.txt";
		//writeFile($filename, $txt);

		// Redirect to new file
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f);
	}

	// Edit forum details
	if ($action=="eforum" and $admin===TRUE)
	{
		global $f;		// Use passed $f value
		global $f2;		// New forum ID
		global $fname;		// New/old forum name
		global $remove;		// Delete it flag
		global $txt;
		
		$idx=(int)$_POST["order_id"];
		$fname=fixit($fname);	// Format name
		$txt=fixit($txt);	// Format description

		// Delete this forum?
		if ($remove==TRUE and $f>0)
		{
		
			rmdir2("forums/" . $f);
			$codex->delete_where("forums","\$uid=='$f2'");
			$codex->commit_changes();
			$codex->delete_where("topics","\$category=='$f2'");
			$codex->commit_changes();
			$codex->delete_where("posts","\$category=='$f2'");
			$codex->commit_changes();
			//$codex->delete_where("");
			header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
		}
		// Or... write changes
		else
		{
			// Write name of forum
			//$filename = "forums/" . $f . "/name.txt";
			//writeFile($filename, $fname);

			// Make desc file for forum
			//$filename = "forums/" . $f . "/desc.txt";
			//writeFile($filename, $txt);

			// Renamed? (New ID)
	
			$codex->update_where("forums","name=$fname&description=$txt&order_id=$idx","\$uid=='$f2'");
			$codex->commit_changes();
			// Redirect to new file
			header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f);
		}
	}

	// Edit topic details
	if ($action=="etopic" and $admin===TRUE)
	{
		global $f;		// Forum ID
		global $t;		// Topic ID
		global $tname;		// Topic new/old name
		global $remove;		// Delete flag
		global $codex;
		$tname=fixit($tname);	// Format name

		// Delete this topic?
		if ($remove==TRUE and $f>0 and $t>0)
		{
			$codex->delete_where("topics","\$uid=='$t' and \$category=='$f'");
			$codex->commit_changes();
			$codex->delete_where("posts","\$uid=='$t' and \$category=='$f'");
			$codex->commit_changes();
			rmdir2("forums/" . $f . "/" . $t);
			header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f);
		}
		// Or... write changes
		else
		{
			// Write name of topic
			//$filename = "forums/" . $f . "/" . $t . "/name.txt";
			//writeFile($filename, $tname);
			$codex->update_where("topics","name=$tname","\$uid=='$t' and \$category=='$f'");
			$codex->commit_changes();
			// Redirect to new file
			header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f . "&t=" . $t);
		}
	}

	// Write file - general utility to write contents to a file
	function writeFile($filename, $txt)
	{
		$handle = fopen($filename, 'w'); // w+
		fwrite($handle, $txt);
		fclose($handle);	
	}

	// Delete all in a directory - for removing forums etc
	// Recursive dir function found on php.net, by [csnyder at mvpsoft dot com] - Thanks!
	function rmdir2($dir)
	{
		if ($handle = @opendir($dir))
		{
			while (($file = readdir($handle)) !== false)
			{
				if (($file == ".") or ($file == ".."))
				{
					continue;
				}
				if (is_dir($dir . '/' . $file))
				{
					// call self for this directory
					rmdir2($dir . '/' . $file);
				}
				else
				{
					unlink($dir . '/' . $file); // remove this file
				}
			}
			@closedir($handle);
			rmdir ($dir); 
		}
	}

	// This removed bad words, adds emoticons, formatting, etc
	// [NB, need to jazz this up and do it more intelligently]
	function fixit($txt)
	{
		// Basic rude-word filter
		$txt=str_replace(array("FUCK","fuck","Fuck"),"f**k",$txt);
		$txt=str_replace(array("CUNT","cunt","Cunt"),"c**t",$txt);
		$txt=str_replace(array("TWAT","twat","Twat"),"t**t",$txt);
		$txt=str_replace(array("SHIT","shit","Shit"),"s**t",$txt);
		$txt=str_replace(array("WANK","wank","Wank"),"w**k",$txt);

		// Generic slash, newline, tag stripping
		$txt=stripslashes($txt);
		$txt=strip_tags($txt);
		$txt=str_replace(array("<",">"),"",$txt);
		$txt=nl2br($txt);

		// May add [http://sk8.homelinux.org/scripts/SpecialChars.txt] at some point

		// Emoticons
		$txt=str_replace(array(":)",":-)"),"<img src='emoticons/smile.gif'>",$txt);	// Smile
		$txt=str_replace(array(";)",";-)", ",)",";-)"),"<img src='emoticons/wink.gif'>",$txt);	// Wink
		$txt=str_replace(array(":D",":-D"),"<img src='emoticons/laugh.gif'>",$txt);	// Laugh
		$txt=str_replace(array(":I",":-I",":|",":-|"),"<img src='emoticons/indifferent.gif'>",$txt);	// Indifferent
		$txt=str_replace(array(":(",":-("),"<img src='emoticons/sad.gif'>",$txt);	// Sad
		$txt=str_replace(array(":\\",":-\\"),"<img src='emoticons/wry.gif'>",$txt);	// Wry

		// Formatting
		$txt=str_replace("[pre]","<pre>",$txt);		// Pre start
		$txt=str_replace("[/pre]","</pre>",$txt);	// Pre end
		$txt=str_replace("[b]","<b>",$txt);		// Bold start
		$txt=str_replace("[/b]","</b>",$txt);		// Bold end
		$txt=str_replace("[q]","<div class='quote'>",$txt);		// Quote start
		$txt=str_replace("[/q]","</div>",$txt);		// Quote end
		$txt=str_replace("[i]","<em>",$txt);		// Italic Start
		$txt=str_replace("[/i]","</em>",$txt);		// Italic End
		$txt=str_replace("[a]","<a href='",$txt);	// Link
		$txt=str_replace("[/a]","' class='link'>link</a>",$txt);	// Link End
		$txt=str_replace("[img]","<img src='",$txt);	// Image
		$txt=str_replace("[/img]","' name='pic'>",$txt);		// Image End

		return $txt;
	}
?>