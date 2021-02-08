<?php
@ob_start();
session_start();
?>
<?php
	include 'functions.php';
	include 'credentials.php';
	$pass=$_SESSION['pass'];
	$search = $_POST['search'];
    $tags_search = $_POST['tags_search'];
    $dossier = $_GET['dossier'];
    $sousdossier = $_GET['sousdossier'];
    $dossier_search = $_POST['dossier_search'];
    $sousdossier_search = $_POST['sousdossier_search'];
    $note = $_GET['note'];
    
    // if (isset($dossier))
    // {
        // if ($dossier == "tous")
		// {
            // $new_dossier_name = "Nouveau";
            // $dossier_search = '';
            // $dossier = '';
        // }
        // else
        // {
            // $new_dossier_name = $dossier;
            // $dossier_search = $dossier;
            // $dossier = $dossier;
        // }
    // }

    if (isset($dossier_search))
    {
        $dossier = $dossier_search;
    }
    
    if (isset($sousdossier_search))
    {
        $sousdossier = $sousdossier_search;
    }
    
	if($pass!=APPPASSWORD)
	{
		header('Location: ./login.php');
	}
	include 'db_connect.php';	
?>

<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<title><?php echo JOURNAL_NAME;?></title>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
	<!--<link type="text/css" rel="stylesheet" href="css/animate.css"/>-->
	<link href='https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
	<link type="text/css" rel="stylesheet" href="css/style.css"/> 
	<script src="js/jquery.min.js"></script> 
    <link rel="stylesheet" href="css/font-awesome.css" />
	<!--<link rel="stylesheet" type="text/css" href="css/toggle-switch.css" />-->
	<link rel="stylesheet" type="text/css" href="css/page.css" />
    
    <!-- Bibliothèque jquery popline pour la barre qui apparait lorsque l'on sélectionne du texte dans une note / voir dans "js/plugins" -->
	<link rel="stylesheet" type="text/css" href="themes/default.css" /> <!-- Thème pour le popline -->
    <!-- Retirer les lignes pour les fonctions que l'on ne veut pas utiliser voir apparaitre dans la barre -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.popline.js"></script>
	<script type="text/javascript" src="js/plugins/jquery.popline.link.js"></script>
	<script type="text/javascript" src="js/plugins/jquery.popline.decoration.js"></script>
	<script type="text/javascript" src="js/plugins/jquery.popline.blockquote.js"></script>
	<script type="text/javascript" src="js/plugins/jquery.popline.list.js"></script>
	<!--<script type="text/javascript" src="js/plugins/jquery.popline.justify.js"></script> -->
	<!--<script type="text/javascript" src="js/plugins/jquery.popline.blockformat.js"></script> -->
	<script type="text/javascript" src="js/plugins/jquery.popline.social.js"></script>
	<script type="text/javascript" src="js/plugins/jquery.popline.textcolor.js"></script>
    <script type="text/javascript" src="js/plugins/jquery.popline.backgroundcolor.js"></script>
    <script type="text/javascript" src="js/plugins/jquery.popline.fontsize.js"></script>

	<script>
		var app_pass = '<?php echo APPPASSWORD;?>';
	</script>
    

    
</head>
<body>
	<!-- COLONNE 1 -->	
    <div id="col_1">
        <!-- Menu -->
        <div class="containbuttons">
            <div class="newbutton" onclick="newnote('<?php echo $dossier;?>', '<?php echo $sousdossier;?>');"><span style="text-align:center;"><span title="Créer une nouvelle note" class="fas fa-file-medical"></span></span></div>
            <div class="newdossierbutton" onclick="createNewdossierJS();"><span style="text-align:center;"><span title="Créer un nouveau dossier" class="fas fa-folder-plus"></span></span></div>
            <div class="trashnotebutton" onclick="window.location = 'trash.php';"><span style="text-align:center;"><span title="Aller à la corbeille" class="fas fa-trash-alt"></span></span></div>
            <div class="deletedossierbutton" onclick="deletedossierJS('<?php echo $dossier;?>');"><span style="text-align:center;"><span title="Supprimer le dossier en cours" class="fas fa-folder-minus"></span></span></div>
            <div class="lister_tags" onclick="window.location = 'listertags.php';"><span style="text-align:center;"><span title="Lister les tags" class="fas fa-tags"></span></span></div>
            <div class="renamedossierbutton" onclick="renamedossierJS('<?php echo $dossier;?>');"><span style="text-align:center;"><span title="Renommer le dossier en cours" class="fas fa-edit"></span></span></div>
            <div class="logoutbutton" onclick="window.location = 'logout.php';"><span style="text-align:center;"><span title="Se déconnecter" class="fas fa-sign-out-alt"></span></span></div>
        </div> 
        
        <hr><br>
             
        <!-- Suivant les cas, on créé les requêtes pour l'arborescence de gauche, du milieu et la liste de droite -->  
        
            <?php
            
            // echo "dossier : $dossier<br />";
            // echo "sousdossier : $sousdossier<br />";
      
            // echo "<pre>";
            // var_dump($sousdossier);
            // echo "</pre>";  
      
            if($sousdossier!='') // Si sousdossier n'est pas vide c'est que l'on vient de cliquer sur un sous-dossier
            {
                if($tags_search!='') // c'est une recherche dans les tags donc on ne veut afficher que les notes qui contiennent le tag
                {
                    $query_gauche = 'SELECT dossier, sousdossier FROM entries WHERE trash = 0 ORDER BY dossier ASC'; 
                    $query_milieu = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND sousdossier = \''.htmlspecialchars($sousdossier,ENT_QUOTES).'\' AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by updated DESC'; 
                    $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND sousdossier = \''.htmlspecialchars($sousdossier,ENT_QUOTES).'\' AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by updated DESC';
                }
                else // sinon c'est une recherche dans les notes donc on ne veut afficher que les notes qui contiennent le mot recherché ou afficher toutes les notes
                {
                    $query_gauche = 'SELECT dossier, sousdossier FROM entries WHERE trash = 0 ORDER BY dossier ASC'; 
                    $query_milieu = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND sousdossier = \''.htmlspecialchars($sousdossier,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC'; 
                    $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND sousdossier = \''.htmlspecialchars($sousdossier,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC';
                }  
            }
            else // Sinon c'est que l'on a selectionné un dossier dans la liste
            {
                if($dossier == ''){ // Si on recherche sur toutes les notes
                
                    if($tags_search!='') // c'est une recherche dans les tags donc on ne veut afficher que les notes qui contiennent le tag
                    {
                        $query_gauche = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 ORDER BY dossier ASC'; 
                        $query_milieu = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by updated DESC';
                        $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by updated DESC';
                    }
                    else // sinon c'est un recherche dans les notes donc on ne veut afficher que les notes qui contiennent le mot recherché // C'est aussi l'affichage de toutes les notes (recherche "")
                    {
                        $query_gauche = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 ORDER BY dossier ASC';
                        //echo 'TPO :'.$dossier;
                        $query_milieu = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC';
                        //echo 'TPO :'.$query_milieu;
                        $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC LIMIT 50';

                    }
                }
                else // sinon c'est que l'on recherche sur un dossier en particulier
                {
                    if($tags_search!='') // c'est une recherche dans les tags donc on ne veut afficher que les notes qui contiennent le tag
                    {
                        $query_gauche = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 ORDER BY dossier ASC'; 
                        $query_milieu = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by updated DESC'; 
                        $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by updated DESC';
                    }
                    else // sinon c'est un recherche dans les notes donc on ne veut afficher que les notes qui contiennent le mot recherché ou juste afficher toutes les notes de ce dossier
                    {
                        $query_gauche = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 ORDER BY dossier ASC'; 
                        $query_milieu = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC'; 
                        $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC';

                    }
                } 
            }
            
            // fonction pour rechercher une valeur dans une tableau multidimmentionnel. Utilisé pour voir si un sous-dossier existe.
            function multi_array_search($search_for, $search_in) {
                    foreach ($search_in as $element) {
                        if ( ($element === $search_for) ){
                            return true;
                        }elseif(is_array($element)){
                            $result = multi_array_search($search_for, $element);
                            if($result == true)
                                return true;
                        }
                    }
                    return false;
                }          
            
            // Arborescence de gauche COL 1 //
            
            $res_gauche = $con->query($query_gauche);
            
            $table = array(); // On créé un tableau vide dans lequel on va mettre les sous-dossiers des dossiers

            while($row1 = mysqli_fetch_array($res_gauche, MYSQLI_ASSOC)) 
            {
                
                if (!isset($table[$row1["dossier"]])){ // Si le dossier n'existe pas dans le nouveau tableau...
                    $table[$row1["dossier"]] = array(); // ...on insert au nouveau tableau global un nouveau tableau vide au nom de ce dossier
                }
                
                // Si le sous-dossier a déjà été rajouté au tableau                
                if(multi_array_search($row1["sousdossier"], $table[$row1["dossier"]]))
                {
                    // Ne rien faire
                }
                else
                {
                    if ($row1["sousdossier"] == '')  // Pour éviter dans l'arbo de gauche des sous-dossiers vides
                    {
                        // Ne rien faire
                    }
                    else
                    {
                        $table[$row1["dossier"]][] = $row1["sousdossier"]; // Append / on ajoute au tableau global/tableau du dossier le titre du sous-dossier
                    }  
                }         
            }
            
            // echo "<pre>";
            // var_dump($table);
            // echo "</pre>";
            
            // Afficher le dossier "Toutes les notes" pour pouvoir cliquer dessus revenir à la vue de tous les dossiers
            $key2 = "Toutes les notes";
            echo "<form action=index.php><input type=hidden name=note>                        
                        <a class=links_arbo_gauche href='index.php' style='text-decoration:none; color:#333'><div id=icon_notes; style='padding-right: 7px; font-size:13px;' class='fas fa-folder'></div>".$key2."</a>
                     </form>";
           
            echo "<div style='height: 2px'></div>"; // Ajuster la distance entre le dossier et son premier sous-dossier
            
            // Afficher tous les dossiers
            foreach ($table as $key => $value) 
            {                    
                // Afficher les dossiers
                echo "<form action=index.php><input type=hidden name=note>                        
                            <a class=links_arbo_gauche href='index.php?dossier=".$key."' style='text-decoration:none; color:#333'><div id=icon_notes; style='padding-right: 7px; font-size:13px;' class='far fa-folder-open'></div>".$key."</a>
                         </form>";
               
                echo "<div style='height: 2px'></div>"; // Ajuster la distance entre le dossier et sa première note
                
                // Afficher les sous-dossiers des dossiers
                foreach ($value as $v2) 
                { 
                    // echo "<pre>";
                    // var_dump($v2);
                    // echo "</pre>";
                
                    if (isset($v2)){ // Si la note a un sous-dossier
                        // opacity:0; pour icon_sousdossier pour que l'icone n'apparaisse pas mais garde l'espace (pour qu'il y ait un décalage / comme dans Evernote)
                        echo "<form action=index.php><input type=hidden name=note>                        
                                <a class=links_arbo_gauche href='index.php?dossier=".$key."&sousdossier=".urlencode($v2)."' style='text-decoration:none; color:#333'><div id=icon_sousdossier; style='opacity:0; padding-right: 7px; padding-left: 10px; font-size:11px;' class='far fa-folder'></div>".$v2."</a>
                             </form>";

                        echo "<div id=pxbetweennotes; style='height: 0px'></div>";  // Pour ajuster la distance entre les sous-dossiers
                    }
                }
                //echo "<div style='height: 10px'></div>"; // Pour ajuster la distance entre les notes  // A commenter pour l'affichage des sous-dossier sinon trop d'espace
                
                
                
                // Afficher les notes des dossiers
                // foreach ($value as $v2) 
                // {           			
                    // echo "<form action=index.php><input type=hidden name=note>                        
                            // <a class=links_arbo_gauche href='index.php?dossier=".$key."&note=".urlencode($v2)."' style='text-decoration:none; color:#333'><div id=icon_notes; style='padding-right: 7px;padding-left: 15px; font-size:11px;' class='far fa-file'></div>".$v2."</a>
                         // </form>";

                    // echo "<div id=pxbetweennotes; style='height: 0px'></div>";  // Pour ajuster la distance entre les notes
                // }
                // echo "<div style='height: 10px'></div>"; // Pour ajuster la distance entre les notes
                
                
            }
     
        ?>
        </div>
    
    
    
    <!-- COLONNE MILIEU -->	
    <div id="col_milieu">
    
    <br>
         
	<!-- Suivant les cas, on créé les requêtes pour l'arborescence de gauche et la liste de droite -->  
	
    <?php
  
        if($note!='') // Si note n'est pas vide c'est que l'on vient de cliquer sur une note
        {
	    $note = str_replace("&#039;", "'", $note); // car les ' sont enregistrés en base comme des "&#039;"	
	    $note = str_replace("&quot;", "\"", $note); // car les ' sont enregistrés en base comme des "&#039;"	
	    #echo $note;
            //$query_gauche = 'SELECT dossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC'; // Classé par dossier par ordre alphabétique
            //$query_milieu = 'SELECT dossier, sousdossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND sousdossier = \''.htmlspecialchars($sousdossier,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC'; 
            $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($note,ENT_QUOTES).'%\')';     
        }
        
      
        // Arborescence COL MILIEU //            
        
        // Afficher le dossier ou sous dossier en gras en haut de la liste
                
        ?><div align = "center"><b><h4><?php echo $dossier." / ".$sousdossier;?></h4></b></div><br><?php
		
        $res_milieu = $con->query($query_milieu);
 		
		$table = array(); // On créé un tableau vide dans lequel on va mettre les notes des dossiers

		while($row1 = mysqli_fetch_array($res_milieu, MYSQLI_ASSOC)) 
		{
            // echo "<pre>";
            // var_dump($row1);
            // echo "</pre>";
            
            // if (isset($row1["sousdossier"]))
            // {
                // if (!isset($table[$row1["sousdossier"]])){ // Si le sous-dossier n'existe pas dans le nouveau tableau...
                    // $table[$row1["sousdossier"]] = array(); // ...on insert au nouveau tableau global un nouveau tableau vide au nom de ce sous-dossier
                // }
                // $table[$row1["sousdossier"]][] = $row1["heading"]; // Append / on ajoute au tableau global/tableau du dossier le titre de la note
            // }
            // else
            // { 
                if (!isset($table[$row1["dossier"]])){ // Si le dossier n'existe pas dans le nouveau tableau...
                    $table[$row1["dossier"]] = array(); // ...on insert au nouveau tableau global un nouveau tableau vide au nom de ce dossier
                }
                $table[$row1["dossier"]][] = $row1["heading"]; // Append / on ajoute au tableau global/tableau du dossier le titre de la note           
            //}
            
            // echo "<pre>";
            // var_dump($table);
            // echo "</pre>";   
            
            
		}
       
		foreach ($table as $key => $value)   // $value = note / $key = dossier / 
		{      
            // Afficher les dossiers (je ne l'affiche pas car c'est le dossier parent dont pour les sous-dossier ça embrouille. Au dessus j'affiche le vrai dossier donc c'est bon)
            
            // echo "<form action=index.php><input type=hidden name=note>                        
                        // <div id=icon_notes; style='padding-right: 7px; font-size:13px;' class='far fa-folder'></div>".$key."</a>
                     // </form>";
           
			// echo "<div style='height: 2px'></div>"; // Ajuster la distance entre le dossier et sa première note
            
            // Afficher les sous-dossiers des dossiers
            
            foreach ($value as $v2) 
            {           			
                echo "<form action=index.php><input type=hidden name=note>                        
                        <a class=links_arbo_gauche href='index.php?dossier=".$key."&sousdossier=".$sousdossier."&note=".urlencode($v2)."' style='text-decoration:none; color:#333'><div id=icon_notes; style='padding-right: 7px;padding-left: 8px; font-size:11px;' class='far fa-file'></div>".$v2."</a>
                     </form>";

                echo "<div id=pxbetweennotes; style='height: 0px'></div>";  // Pour ajuster la distance entre les notes
            }
            echo "<div style='height: 10px'></div>"; // Pour ajuster la distance entre les notes     
            
		}
 
    ?>
    </div>
	
	<!-- COLONNE 2 -->
    <div id="col_2">
    
        <!-- Recherche -->

	   <div class="contains_forms_search">
			<form class="form_search" action="index.php" method="POST">          
				<div class="right-inner-addon">
					<i class="fas fa-search"></i>
					<input autocomplete="off" autocapitalize="off" spellcheck="false" id="note-search" type="search" name="search" class="search form-control" placeholder="Rechercher dans les notes" onfocus="updateidsearch(this);"/>
				</div>
				<input type="hidden" name="dossier_search" value='<?php echo $dossier;?>'/>   <!-- Pour envoyer au serveur en post en même temps que la recherche l'info du dossier en cours -->
   				<input type="hidden" name="sousdossier_search" value='<?php echo $sousdossier;?>'/>   <!-- Pour envoyer au serveur en post en même temps que la recherche l'info du sous-dossier en cours -->
			</form>
			
			<form class="form_search_tags" action="index.php" method="POST">          
				<div class="right-inner-addon">
					<i class="fas fa-tags"></i>
					<input autocomplete="off" autocapitalize="off" spellcheck="false" id="tags-search" type="search" class="form-control search" placeholder="Rechercher dans les tags" onfocus="updateidsearch(this);" name="tags_search"/>
				</div>  
					<input type="hidden" name="dossier_search" value='<?php echo $dossier;?>'/>  <!-- Pour envoyer au serveur en post en même temps que la recherche l'info du dossier en cours -->
       				<input type="hidden" name="sousdossier_search" value='<?php echo $sousdossier;?>'/>   <!-- Pour envoyer au serveur en post en même temps que la recherche l'info du sous-dossier en cours -->
			</form>                 
		</div> 
                       
        <!-- Recherche - "Résultat pour...." -->             
        <?php
        
            // echo "dossier : $dossier<br />";
            // echo "sousdossier : $sousdossier<br />";
            // echo "dossier_search : $dossier_search<br />";
            // echo "sousdossier_search : $sousdossier_search<br />";           
        
            if($search!='') // on arrive ici suite à une recherche, ça va afficher les résultat de la recherche + le petit icone en croix pour sortir de la recherche
            { 
                if($dossier == '')  // Si on est sur le dossier toutes les notes
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$search.'</b> dans le titre et le contenu de <b>toutes les notes existantes</b> <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';
                }
                else if($sousdossier_search != '')  // Si on est sur un sous-dossier pour la recherche
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$search.'</b> dans le titre et le contenu des notes du dossier <b>'.$sousdossier_search.'</b> <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';

                }
                else // Sinon c'est forcément que l'on est sur un dossier (pas toutes les notes et pas sous-dossier)
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$search.'</b> dans le titre et le contenu des notes du dossier <b>'.$dossier_search.'</b> <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';
                }   
            }
            if($tags_search!='') // on arrive ici suite à une recherche tag, ça va afficher les résultat de la recherche + le petit icone en croix pour sortir de la recherche
            {
                if($dossier == '')  // Si on est sur le dossier toutes les notes
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$tags_search.'</b> dans les tags de <b>toutes les notes existantes</b> <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';
                }
                else if($sousdossier_search != '')  // Si on est sur un sous-dossier pour la recherche
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$tags_search.'</b> dans les tags des notes du dossier <b>'.$sousdossier_search.'</b> <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';

                }
                else // Sinon c'est forcément que l'on est sur un dossier (pas toutes les notes et pas sous-dossier)
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$tags_search.'</b> dans les tags des notes du dossier <b>'.$dossier_search.'</b> <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';
                }   
            }
			
			// Liste de droite en fonction de la requête créée plus haut //		
		
            $res_droite = $con->query($query_droite);
            while($row = mysqli_fetch_array($res_droite, MYSQLI_ASSOC))
            {
                $filename = "entries/".$row["dossier"]."/".$row["id"].".html";
                $handle = fopen($filename, "r");
                //$contents = file_get_contents($filename);  // Test pour remplacer fread
                $contents = fread($handle, filesize($filename));
                $entryfinal = $contents;
                fclose($handle);
           
                // Afficher les notes
                echo '<div id="note'.$row['id'].'" class="notecard">
                    <div class="innernote">
                    
                        <span style="cursor:pointer" title="Supprimer cette note" onclick="deleteNote(\''.$row['id'].'\')" class="fas fa-trash pull-right icon_corbeille"></span>
             
                    
                        <div id="lastupdated'.$row['id'].'" class="lastupdated">Dernière modification le '.formatDateTime(strtotime($row['updated'])).'</div>
                        
                        <div class="contain_doss_tags" >
							
							<div class="icon_doss">'.$row["id"].'&nbsp;&nbsp;&nbsp;<span class="fa fa-folder"></div>
							<div class="name_doss"><span><input size="40px" autocomplete="off" autocapitalize="off" spellcheck="false" onfocus="updateiddoss(this);" id="dossier'.$row['id'].'" type="text" placeholder="Dossier ?" value="'.$row['dossier'].'"></input></span></div>
                            							
                            <div class="icon_sousdoss"><span class="fa fa-folder-open"></div>
							<div class="name_sousdoss"><span><input size="40px" autocomplete="off" autocapitalize="off" spellcheck="false" onfocus="updateidsousdoss(this);" id="sousdoss'.$row['id'].'" type="text" placeholder="Sous-dossier ?" value="'.$row['sousdossier'].'"></input></span></div>
							
							<div class="icon_tag"><span style="text-align:center; font-size:12px;" class="fa fa-tag"></div>
							<div class="name_tags"><span><input size="40px" autocomplete="off" autocapitalize="off" spellcheck="false" placeholder="Tags" onfocus="updateidtags(this);" id="tags'.$row['id'].'" type="text" placeholder="Tags ?" value="'.$row['tags'].'"></input></span></div>
                        </div>
                        
                        <hr>                        
                        <hr>
                        
                        <h4><input style="color:#0066cc" autocomplete="off" autocapitalize="off" spellcheck="false" onfocus="updateidhead(this);" id="inp'.$row['id'].'" type="text" placeholder="Titre ?" value="'.$row['heading'].'"></input></h4>
                        
                        <div class="noteentry" autocomplete="off" autocapitalize="off" spellcheck="false" onload="initials(this);" onfocus="updateident(this);" id="entry'.$row['id'].'" data-ph="Entrer du texte ou coller une image" contenteditable="true">'.$entryfinal.'</div>
                        
                        <div style="height:30px;"></div>
                    </div>
                </div>';
            }
        ?>        
    </div>
</body>
	

<script src="js/script.js"></script>
<script> $(".noteentry").popline(); </script>  <!-- Lorsque l'on sélectionne un texte, cela fait apparaitre le menu d'édition en floatant dans la zone .noteentry (donc contenu des notes) au-dessus / Il faut que ce soit un 'contenteditable="true"' -->


</html>
