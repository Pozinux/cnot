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
	$id = $_POST['id'];
	$heading = $_POST['heading'];
	$entry = $_POST['entry'];
	$entrycontent = $_POST['entrycontent'];
	$now = $_POST['now'];
	$seconds = $now;
	$dossier = $_POST['dossier'];	
    $tags = $_POST['tags'];	
	
	$query="SELECT * from entries WHERE id=".$id;
	$res = $con->query($query);
	$row = mysqli_fetch_array($res,MYSQLI_ASSOC);
	
	$chemin_actuel = "entries/".$row['dossier']."/".$id.".html";
    
	if($dossier != ""){
		$handle = fopen("entries/".$dossier."/".$id.".html", 'w+') or die("Est-ce que ce dossier existe ?");
	}
	else
	{
		die("Vous devez définir un dossier qui existe déjà.");
	}
	
	$str = fread($handle, filesize($id.".html"));
    
	if(htmlspecialchars($heading,ENT_QUOTES)==$row['heading'] && $entry==$str && htmlspecialchars($dossier,ENT_QUOTES)==$row['dossier'] && htmlspecialchars($tags,ENT_QUOTES)==$row['tags'])
	{
		die('Dernière modification le '.formatDateTime(strtotime($row['updated'])));
	}
    
	fwrite($handle, $entry);
	fclose($handle);
	
	if ($dossier != $row['dossier'])
	{
		unlink("entries/".$row['dossier']."/".$id.".html");		
	}	
    
	$query="UPDATE entries SET heading = '".htmlspecialchars($heading,ENT_QUOTES)."', entry = '".htmlspecialchars($entrycontent,ENT_QUOTES)."', created = created, updated = '".date("Y-m-d H:i:s", $seconds)."', dossier = '".htmlspecialchars($dossier,ENT_QUOTES)."', tags = '".htmlspecialchars($tags,ENT_QUOTES)."' WHERE id=".$id;
	if($con->query($query)) echo die('Dernière modification le '.formatDateTime(strtotime(date("Y-m-d H:i:s", $seconds))));
	else echo mysqli_error($con);
?>