<?php
	date_default_timezone_set('UTC');
	include 'functions.php';
	include 'credentials.php';
	$pass=$_POST['pass'];

        //echo '<script> alert("'.$pass.'")</script>';

	if($pass!=APPPASSWORD)
	{
		die('Password Incorrect (updatenote.php)');
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
    
    $filename = "entries/".$dossier."/".$id.".html";
	    
	if($dossier != ""){
		$handle = fopen($filename, 'w+') or die("Est-ce que ce dossier existe ?");
	}
	else
	{
		die("Vous devez définir un dossier qui existe déjà.");
	}

    // Test pour remplacer fread
    //$str = file_get_contents($filename);   

     // ATTENTION !!! $str est toujours vide donc cette partie ne marche jamais !!!!" 	
      $str = fread($handle, filesize($filename)); // Lecture du fichier en mode binaire. On lit ce qui existe déjà dans le fichier html enregistré sur le disque.
    
        // S'il n'y a eu aucun changement à la note, on sort du script
	if(htmlspecialchars($heading,ENT_QUOTES)==$row['heading'] && $entry==$str && htmlspecialchars($dossier,ENT_QUOTES)==$row['dossier'] && htmlspecialchars($sousdossier,ENT_QUOTES)==$row['sousdossier'] && htmlspecialchars($tags,ENT_QUOTES)==$row['tags'])
	{
		//die('Modification le '.formatDateTime(strtotime($row['updated'])));  // die = exit. Stop l'éxécution du script et affiche le message en paramètre.
        die('Aucun changement de la note.');  // die = exit. Stop l'éxécution du script et affiche le message en paramètre.

	}
    
	//$entry = str_replace("<br>", "&nbsp;", $entry); // Remplacer les lignes vides par &nbsp; pour que si on format en code le saut de ligne est gardé // Finalement je le fais en amont dans la fonction JS updatenote()
        
       if ($entry != '') // Si la note est vide et que l'on a changé que le titre, alors ne pas essayer d'écrire une entry vide dans le fichier html
 	{
       	    if (!fwrite($handle, $entry)){//;  // Ecrit un fichier en mode binaire.
               die("Erreur lors de l écriture du fichier html");
            }  
	}  
  
	fclose($handle);
    //file_put_contents($filename, $entry);   // Test pour remplacer fwrite    
	
    // Si le dossier a changé de nom, on supprime l'ancienne note qui se trouvait dans l'ancien dossier
	if ($dossier != $row['dossier'])
	{
		unlink("entries/".$row['dossier']."/".$id.".html");		
	}	
    
	$query="UPDATE entries SET heading = '".htmlspecialchars($heading,ENT_QUOTES)."', entry = '".htmlspecialchars($entrycontent,ENT_QUOTES)."', created = created, updated = '".date("Y-m-d H:i:s", $seconds)."', dossier = '".htmlspecialchars($dossier,ENT_QUOTES)."', sousdossier = '".htmlspecialchars($sousdossier,ENT_QUOTES)."', tags = '".htmlspecialchars($tags,ENT_QUOTES)."' WHERE id=".$id;
    
	// if($con->query($query)) echo die('Dernière modification le '.formatDateTime(strtotime(date("Y-m-d H:i:s", $seconds)))); // Pour avoir le "Dernière modification le"
	if($con->query($query)) echo die(formatDateTime(strtotime(date("Y-m-d H:i:s", $seconds)))); // Si l'écriture de la requête en base est ok alors on sort
	else echo 'Error mysql : '.mysqli_error($con); // Sinon on affiche l'erreur SQL
?>
