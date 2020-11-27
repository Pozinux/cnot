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
    $dossier = $_POST['new_dossier'];
    
	if (!file_exists('entries/'.$dossier)) 
	{
        mkdir('entries/'.$dossier, 0755, true);
	}	
?>
