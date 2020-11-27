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
    $dossier = $_POST['del_dossier'];
    
    $dir = 'entries/'.$dossier;
    deleteDirectory($dir);
    
    function deleteDirectory($dir) {
        system('rm -rf -- ' . escapeshellarg($dir), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }
    
    $query="DELETE FROM entries WHERE dossier = '".$dossier."'";
	if($con->query($query)) echo 1;
	else echo mysqli_error($con);
?>
