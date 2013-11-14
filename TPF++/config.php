<?php
	date_default_timezone_set('America/Los_Angeles');	//required for E_STRICT standards

	$siteName = "TPF++ Forum";
	$siteURL = "index.php";
	$title = $siteName;			// Displayed in headings, etc
	$skin = "tpf";
	$email = "stuff@ralpharama.co.uk";
	$version = "Beta";
	$openForum = TRUE;			// Guests can view this forum?
	$lang = "en";				// Default language code
	$hitcounter = TRUE;			// Index hit counter?
	$cookietm = 10800;			// Time for cookie expiry
	
	
	$topics_per_page=10;		//works with 10 and 20 never tried the rest
	/*used while paginating your topics 
	if a new post has been made, The topic with that post will appear first
	
	*/
?>