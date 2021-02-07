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
	$entry = $_POST['entry']; // Enregistrement du contenu html (donc avec les images) dans un fichier html. 
	$entrycontent = $_POST['entrycontent']; // Enregistrement du contenu texte (donc sans les images) en base. 
	$now = $_POST['now'];
	$seconds = $now;
	$dossier = $_POST['dossier'];
    $sousdossier = $_POST['sousdossier'];
    $tags = $_POST['tags'];	
	
	$query="SELECT * from entries WHERE id=".$id;
	$res = $con->query($query);
	$row = mysqli_fetch_array($res,MYSQLI_ASSOC);
	    
	if($dossier != ""){
		$handle = fopen("entries/".$dossier."/".$id.".html", 'w+') or die("Est-ce que ce dossier existe ?");
	}
	else
	{
		die("Vous devez définir un dossier qui existe déjà.");
	}
	
    // Test pour remplacer fread
    //$filename = "entries/".$dossier."/".$id.".html";
    //$str = file_get_contents($filename);   
    
	$str = fread($handle, filesize($id.".html")); // Lecture du fichier en mode binaire
    
	if(htmlspecialchars($heading,ENT_QUOTES)==$row['heading'] && $entry==$str && htmlspecialchars($dossier,ENT_QUOTES)==$row['dossier'] && htmlspecialchars($sousdossier,ENT_QUOTES)==$row['sousdossier'] && htmlspecialchars($tags,ENT_QUOTES)==$row['tags'])
	{
		die('Modification le '.formatDateTime(strtotime($row['updated'])));
	}
    
	//$entry = str_replace("<br>", "&nbsp;", $entry); // Remplacer les lignes vides par &nbsp; pour que si on format en code le saut de ligne est gardé // Finalement je le fais en amont dans la fonction JS updatenote()
    
    fwrite($handle, $entry);  // Ecrit un fichier en mode binaire.
	fclose($handle);
    // file_put_contents($filename, $entry);   // Test pour remplacer fwrite    
	
	if ($dossier != $row['dossier'])
	{
		unlink("entries/".$row['dossier']."/".$id.".html");		
	}	
    
	$query="UPDATE entries SET heading = '".htmlspecialchars($heading,ENT_QUOTES)."', entry = '".htmlspecialchars($entrycontent,ENT_QUOTES)."', created = created, updated = '".date("Y-m-d H:i:s", $seconds)."', dossier = '".htmlspecialchars($dossier,ENT_QUOTES)."', sousdossier = '".htmlspecialchars($sousdossier,ENT_QUOTES)."', tags = '".htmlspecialchars($tags,ENT_QUOTES)."' WHERE id=".$id;
	#$query="UPDATE entries SET heading = '".$heading."', entry = '".htmlspecialchars($entrycontent,ENT_QUOTES)."', created = created, updated = '".date("Y-m-d H:i:s", $seconds)."', dossier = '".htmlspecialchars($dossier,ENT_QUOTES)."', tags = '".htmlspecialchars($tags,ENT_QUOTES)."' WHERE id=".$id;
	// if($con->query($query)) echo die('Dernière modification le '.formatDateTime(strtotime(date("Y-m-d H:i:s", $seconds)))); // Pour avoir le "Dernière modification le"
	if($con->query($query)) echo die(formatDateTime(strtotime(date("Y-m-d H:i:s", $seconds))));
        // if($con->query($query)) echo die('(ID '.$id.') - Dernière modification le '.formatDateTime(strtotime(date("Y-m-d H:i:s", $seconds))));  // Pour rajouter (ID XX) dans la note à l'enregistrement
	else echo mysqli_error($con);
?>
