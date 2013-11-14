<?php
	session_start();
	error_reporting(E_ALL);
  require_once('config.php');
	setcookie ("Alien", "", time()-3600, '/', '');
	setcookie ("AlienID", "", time()-3600, '/', '');
	unset($_SESSION["user"]);
	// Redirect to new file
	header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
?>