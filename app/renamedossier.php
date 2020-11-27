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
    $actuel_dossier = $_POST['actuel_dossier'];
    $dossier_new_name = $_POST['dossier_new_name'];
    
    $dir = 'entries/'.$actuel_dossier;
    $new_dir =  'entries/'.$dossier_new_name;
    renameDirectory($dir, $new_dir);
    
    function renameDirectory($dir, $new_dir) {
        rename($dir, $new_dir);
    }
    
	$query="UPDATE entries SET dossier = '".htmlspecialchars($dossier_new_name,ENT_QUOTES)."' WHERE dossier = '".$actuel_dossier."'";
	if($con->query($query)) echo 1;
	else echo mysqli_error($con);
?>
