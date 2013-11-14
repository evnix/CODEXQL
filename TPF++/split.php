<?php
	session_start();
	//error_reporting(E_ALL);
	include ("config.php");
	include ("adminchk.php");	// Check if user is admin
	ob_start();
	include ("lang/lang_{$lang}.php");
	include ("getdir.php");

	$f=$_GET["f"];
	$t=$_GET["t"];
	$p=$_GET["p"];
	$action=$_GET["action"];

	// Split a topic from this point into a new topic called $f$t$p within same forum
	if ($action=="split")
	{
		global $f;		// Use passed $f value
		global $t;		// Use passed $t value
		global $p;		// Use passed $p value
		global $user;

		// Find highest ID, add 1 to it and make this the new ID for this
		$ourDir=getTopics($f);
		$t2 = getHighest($ourDir)+1;

		$ourDir=getPosts($f, $t);
		$p2=1;			// Renumber posts as we move them

		// Create topic dir
		mkdir("forums/" . $f . "/" . $t2);

		// Write name of topic
		$filename = "forums/" . $f . "/" . $t2 . "/name.txt";
		writeFile($filename, $GLOBALS['l_topicNew'] . " ".$f." ".$t." ".$p);

		// Copy posts to new location, and delete from old
		for ($i=$p; $i<count($ourDir); $i++)
		{
			$id=$ourDir[$i]->id;

			// Write post
			$filename = "forums/" . $f . "/" . $t . "/" . $id . ".dat";
			$filename2 = "forums/" . $f . "/" . $t2 . "/" . $p2 . ".dat";
			copy ($filename, $filename2);
			unlink ($filename);

			// Write poster
			$filename = "forums/" . $f . "/" . $t . "/" . $id . ".usr";
			$filename2 = "forums/" . $f . "/" . $t2 . "/" . $p2 . ".usr";
			copy ($filename, $filename2);
			unlink ($filename);

			$p2++;
		}

		// Copy hit counter for this topic
		$filename = "forums/" . $f . "/" . $t . "/hits.txt";
		$filename2 = "forums/" . $f . "/" . $t2 . "/hits.txt";
		copy ($filename, $filename2);

		// Write 'Last Post' for forum listing
		$filename = "forums/" . $f . "/lastpost.txt";
		writeFile($filename, $user);
		$filename = "forums/" . $f . "/lastlink.txt";
		writeFile($filename, "f=".$f."&t=".$t2."#0");

		// Write 'Last Post' for topic listing
		$filename = "forums/" . $f . "/" . $t2 . "/lastpost.txt";
		writeFile($filename, $user);
		$filename = "forums/" . $f . "/" . $t2 . "/lastlink.txt";
		writeFile($filename, "f=".$f."&t=".$t2."#0");

		// Redirect to new file
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?f=" . $f . "&t=" . $t2);
	}


//-------------

	// Write file
	function writeFile($filename, $txt)
	{
		$handle = fopen($filename, 'w+');
		fwrite($handle, $txt);
		fclose($handle);	
	}
?>