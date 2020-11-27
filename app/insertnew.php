<?php
	date_default_timezone_set('UTC');
	include 'functions.php';
	include 'credentials.php';
	$pass=$_POST['pass'];
	if($pass!=APPPASSWORD)
	{
		die('Password Incorrect');
	}
	include 'db_connect.php';
	$now = $_POST['now'];
    $seconds = $now;
    $dossier = $_POST['dossier'];

	$query="INSERT into entries (heading,entry,created,updated,dossier) values ('Note sans titre','','".date("Y-m-d H:i:s", $seconds)."','".date("Y-m-d H:i:s", $seconds)."','".htmlspecialchars($dossier,ENT_QUOTES)."')";
	if($con->query($query)) die('1');
	else die(mysqli_error($con));
?>
