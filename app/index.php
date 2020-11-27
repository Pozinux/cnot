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
    $dossier_en_cours = $_GET['doss'];
    $dossier_search = $_POST['dossier_search'];
    $note = $_GET['note'];
    
    if (isset($dossier_en_cours))
    {
        if ($dossier_en_cours == "tous")
		{
            $new_dossier_name = "Nouveau";
            $dossier_search = '';
            $dossier_filtre = '';
        }
        else
        {
            $new_dossier_name = $dossier_en_cours;
            $dossier_search = $dossier_en_cours;
            $dossier_filtre = $dossier_en_cours;
        }
    }

    if (isset($dossier_search))
    {
        if ($dossier_search == "tous"){
            $new_dossier_name = "Nouveau";
            $dossier_search = '';
            $dossier_filtre = '';
        }
        else
        {
            $new_dossier_name = $dossier_search;
            $dossier_en_cours = $dossier_search;
            $dossier_filtre = $dossier_search;
        }
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
	<link type="text/css" rel="stylesheet" href="css/animate.css"/>
	<link href='https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
	<link type="text/css" rel="stylesheet" href="css/style.css"/> 
	<script src="js/jquery.min.js"></script> 
    <link rel="stylesheet" href="css/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="css/toggle-switch.css" />
	<link rel="stylesheet" type="text/css" href="css/page.css" />
    
    <!-- Bibliothèque jquery popline pour la barre qui apparait lorsque l'on sélectionne du texte dans une note / voir dans "js/plugins" -->
	<link rel="stylesheet" type="text/css" href="themes/default.css" /> <!-- Thème pour le popline -->
    <!-- Retirer les lignes pour les fonctions que l'on ne veut pas utiliser voir apparaitre dans la barre -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.popline.js"></script>
	<!--<script type="text/javascript" src="js/plugins/jquery.popline.link.js"></script>-->
	<!-- <script type="text/javascript" src="js/plugins/jquery.popline.blockquote.js"></script> -->
	<script type="text/javascript" src="js/plugins/jquery.popline.decoration.js"></script>
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
        <div class="newbutton" onclick="newnote('<?php echo $new_dossier_name;?>');"><span style="text-align:center;"><span title="Créer une nouvelle note" class="fas fa-file-medical"></span></span></div>
        <div class="newdossierbutton" onclick="createNewdossierJS('<?php echo $dossier_en_cours;?>');"><span style="text-align:center;"><span title="Créer un nouveau dossier" class="fas fa-folder-plus"></span></span></div>
        <div class="trashnotebutton" onclick="window.location = 'trash.php';"><span style="text-align:center;"><span title="Aller à la corbeille" class="fas fa-trash-alt"></span></span></div>
        <div class="deletedossierbutton" onclick="deletedossierJS('<?php echo $dossier_en_cours;?>');"><span style="text-align:center;"><span title="Supprimer le dossier en cours" class="fas fa-folder-minus"></span></span></div>
        <div class="renamedossierbutton" onclick="renamedossierJS('<?php echo $dossier_en_cours;?>');"><span style="text-align:center;"><span title="Renommer le dossier en cours" class="fas fa-edit"></span></span></div>
        <div class="logoutbutton" onclick="window.location = 'logout.php';"><span style="text-align:center;"><span title="Se déconnecter" class="fas fa-sign-out-alt"></span></span></div>
	
        <script>
            function createNewdossierJS(dossier_en_cours) {
                var new_dossier_to_create = window.prompt("Nom du dossier à créer ?");
                if (new_dossier_to_create != null){
                    createnewdossier(new_dossier_to_create);
                    //window.location.href = "index.php?doss="+new_dossier_to_create;
                }
            }
			
            function deletedossierJS(dossier_en_cours) {
                 if (dossier_en_cours !== '') 
                {
                    var dossier_to_remove = dossier_en_cours;
                    if (dossier_to_remove != null){
                        removedossier(dossier_to_remove);
                    }
                }
                else
                {
                   confirm("Merci de sélectionner d'abord dans la liste le dossier à supprimer !");
                } 
            }     
			
            function renamedossierJS(dossier_en_cours) {
                if (dossier_en_cours !== '') 
                {
                    var dossier_a_renommer = dossier_en_cours;
                    var nouveau_nom_dossier = window.prompt("Nouveau nom pour le dossier "+dossier_a_renommer+" ?");
                    if (nouveau_nom_dossier != null){
                        renamedossier(dossier_a_renommer, nouveau_nom_dossier);
                        //window.location.href = "index.php?doss="+nouveau_nom_dossier;
                    }
                }
                else
                {
                   confirm("Merci de sélectionner d'abord dans la liste le dossier à renommer !");
                } 
            }
        </script>
	</div> 

    <!-- Menu déroulant infos --> 
    <?php
        // Récupérer la liste des dossiers existants sur le serveur (pas en base car il peut exister des dossiers sans notes en base) et les mettre dans un tableau. Ce tableau servira à populer le menu déroulant dossier
        $liste_dossier = array();
        if ($handle = opendir('entries')) {
            //$blacklist = array('.', '..', 'somedir', 'somefile.php');
            $blacklist = array('.', '..');
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, $blacklist)) {
                    $liste_dossier[] = $file;
                }
            }
            closedir($handle);
			
			sort($liste_dossier, SORT_STRING | SORT_FLAG_CASE); // Classer la liste des dossiers par ordre alphabétique
            
            // Compter le nombre de notes total (hors corbeille)
            $query_count_notes_total = 'SELECT * FROM entries WHERE trash = 0';
            $res_count_notes_total = $con->query($query_count_notes_total);
            $count_number_notes_total = mysqli_num_rows($res_count_notes_total);  // OU "$count_number_notes = $res_count_notes->num_rows;"
            if ($count_number_notes_total == 1)
            { 
                $label_note = "note"; 
            } 
            else
            { 
                $label_note = "notes"; 
            }
        }
    ?>    

	<!-- Menu déroulant généré --> 

	<div class="dossierselector">
		<select id="mydossierSelect" onchange="functionSendGetdossier()" style="padding: 1px">
			<optgroup label="Filtrer sur :">
			   <option value="tous" <?php if($dossier_en_cours == 'tous'){echo("selected");}?>>Tous les dossiers - <?php echo $count_number_notes_total.' '.$label_note;?></option>
			<?php                
				
				if (isset($dossier_en_cours))
				{
					$selected_category = $dossier_en_cours;
				}
				else if (isset($dossier_search))
				{
					$selected_category = $dossier_search;
				}
				
				foreach($liste_dossier as $key => $category) 
				{					
					// Compter le nombre de notes par dossiers en bdd (hors corbeille)
					$query_count_notes = 'SELECT * FROM entries WHERE trash = 0 AND dossier LIKE \'%'.htmlspecialchars($category,ENT_QUOTES).'\'';
					$res_count_notes = $con->query($query_count_notes);
					$count_number_notes = mysqli_num_rows($res_count_notes);  // OU "$count_number_notes = $res_count_notes->num_rows;"                        
					
					// Boucle sur le tableau des dossiers pour créer un menu déroulant en fonction des dossiers existants. Positionne aussi le "selected" pour le choix à afficher au chargement de la page + affiche nbr de notes par dossiers
					$selected = ($selected_category == $category) ? "selected" : ""; ?>
					<option value="<?php echo $category; ?>" <?php echo $selected; ?>> <?php echo $category?> (<?php echo $count_number_notes;?>)<?php; ?></option><?php
				}?>
		</optgroup>
		</select>  
		</span>   

        <script>
            function functionSendGetdossier() {
                var doss = document.getElementById("mydossierSelect").value;
                window.location.href = "index.php?doss="+doss;
            }              
        </script>
	</div>
    
    <br><hr><br>
         
	<!-- Suivant les cas, on créé les requêtes pour l'arborescence de gauche et la liste de droite -->  
	
    <?php
  
        if($note!='') // Si note n'est pas vide c'est que l'on vient de cliquer sur une note dans la liste de l'arborescence donc on ne veut afficher (GAUCHE) que les notes de son carnet (DROITE) que la note sur laquelle on a cliqué
        {
            $query_gauche = 'SELECT dossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier_filtre,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by dossier ASC'; // Classé par carnet par ordre alphabétique
            $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier_filtre,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($note,ENT_QUOTES).'%\')';

        }
        else // Sinon c'est que l'on a selectionné un carnet dans la liste
        {
			if($dossier_filtre == ''){ // Si on recherche sur toutes les notes
			
				if($tags_search!='') // c'est une recherche dans les tags donc on ne veut afficher que les notes qui contiennent le tag
				{
					$query_gauche = 'SELECT dossier, heading FROM entries WHERE trash = 0 AND dossier LIKE \'%'.htmlspecialchars($dossier_filtre,ENT_QUOTES).'%\' AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by dossier ASC'; // Classé par carnet par ordre alphabétique
					$query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier LIKE \'%'.htmlspecialchars($dossier_filtre,ENT_QUOTES).'%\' AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by updated DESC';
				}
				else // sinon c'est un recherche dans les notes donc on ne veut afficher que les notes qui contiennent le mot recherché // C'est aussi l'affichage de toutes les notes (recherche "")
				{
					$query_gauche = 'SELECT dossier, heading FROM entries WHERE trash = 0 AND dossier LIKE \'%'.htmlspecialchars($dossier_filtre,ENT_QUOTES).'%\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by dossier ASC'; // Classé par carnet par ordre alphabétique
                    $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier LIKE \'%'.htmlspecialchars($dossier_filtre,ENT_QUOTES).'%\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC';
				}
			}
			else // sinon c'est que l'on recherche sur un carnet en particulier
			{
				if($tags_search!='') // c'est une recherche dans les tags donc on ne veut afficher que les notes qui contiennent le tag
				{
					$query_gauche = 'SELECT dossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier_filtre,ENT_QUOTES).'\' AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by dossier ASC'; // Classé par carnet par ordre alphabétique
					$query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier_filtre,ENT_QUOTES).'\' AND (tags like \'%'.htmlspecialchars($tags_search,ENT_QUOTES).'%\') ORDER by updated DESC';
				}
				else // sinon c'est un recherche dans les notes donc on ne veut afficher que les notes qui contiennent le mot recherché // C'est aussi l'affichage de toutes les notes (recherche "")
				{
					$query_gauche = 'SELECT dossier, heading FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier_filtre,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by dossier ASC'; // Classé par carnet par ordre alphabétique
				    $query_droite = 'SELECT * FROM entries WHERE trash = 0 AND dossier = \''.htmlspecialchars($dossier_filtre,ENT_QUOTES).'\' AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC';
				}
			} 
        } 
		
		
	// Arborescence de gauche //
		
        $res_gauche = $con->query($query_gauche);
 		
		$table = array(); // On créé un tableau vide dans lequel on va mettre les notes des dossiers

		while($row1 = mysqli_fetch_array($res_gauche, MYSQLI_ASSOC)) 
		{
			if (!isset($table[$row1["dossier"]])){ // Si le dossier n'existe pas dans le nouveau tableau...
                $table[$row1["dossier"]] = array(); // ...on insert au nouveau tableau global un nouveau tableau vide au nom de ce dossier
			}
			$table[$row1["dossier"]][] = $row1["heading"]; // Append / on ajoute au tableau global/tableau du dossier le titre de la note
		}
		
		foreach ($table as $key => $value) 
		{            
            echo "<form action=index.php><input type=hidden name=note>                        
                        <a href='index.php?doss=".$key."' style='text-decoration:none; color:#333' onclick='document.getElementById(clicnote).submit()'><div id=icon_notes; style='padding-right: 7px; font-size:13px;' class='far fa-folder'></div>".$key."</a>
                     </form>";
           
			echo "<div style='height: 2px'></div>"; // Ajuster la distance entre le dossier et sa première note
			foreach ($value as $v2) 
			{           			
				echo "<form action=index.php><input type=hidden name=note>                        
                        <a href='index.php?doss=".$key."&note=".urlencode($v2)."' style='text-decoration:none; color:#333' onclick='document.getElementById(clicnote).submit()'><div id=icon_notes; style='padding-right: 7px;padding-left: 15px; font-size:11px;' class='far fa-file'></div>".$v2."</a>
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
				<input type="hidden" name="dossier_search" value='<?php echo $dossier_search;?>'/>  <!-- Pour envoyer au serveur en post en même temps que la recherche l'info du carnet en cours -->
			</form>
			
			<form class="form_search_tags" action="index.php" method="POST">          
				<div class="right-inner-addon">
					<i class="fas fa-tags"></i>
					<input autocomplete="off" autocapitalize="off" spellcheck="false" id="tags-search" type="search" class="form-control search" placeholder="Rechercher dans les tags" onfocus="updateidsearch(this);" name="tags_search"/>
				</div>  
					<input type="hidden" name="dossier_search" value='<?php echo $dossier_search;?>'/>  <!-- Pour envoyer au serveur en post en même temps que la recherche l'info du carnet en cours -->
			</form>                 
		</div> 
                       
        <!-- Recherche - "Résultat pour...." -->             
        <?php
            if($search!='') // on arrive ici suite à une recherche, ça va afficher les résultat de la recherche + le petit icone en croix pour sortir de la recherche
            {
                if($dossier_filtre == '') 
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$search.'</b> dans toutes les notes existantes <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';
                }
                else
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$search.'</b> dans le titre et le contenu des notes du dossier <b>'.$dossier_filtre.'</b> <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';
                }
            }
            if($tags_search!='') // on arrive ici suite à une recherche tag, ça va afficher les résultat de la recherche + le petit icone en croix pour sortir de la recherche
            {
                if($dossier_filtre == '') 
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$tags_search.'</b> dans les tags de toutes les notes existantes <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';
                }
                else
                {
                    echo '<br><div style="text-align:center; font-weight:300;"> Résultats pour la recherche de <b>'.$tags_search.'</b> dans les tags des notes du dossier <b>'.$dossier_filtre.'</b> <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'index.php\'"><span style="color:red" class="fa fa-times"></span></span></div><br>';
                }
            }
			
			// Liste de droite en fonction de la requête créée plus haut //		
		
            $res_droite = $con->query($query_droite);
            while($row = mysqli_fetch_array($res_droite, MYSQLI_ASSOC))
            {
                $filename = "entries/".$row["dossier"]."/".$row["id"].".html";
                $handle = fopen($filename, "r");
                $contents = fread($handle, filesize($filename));
                $entryfinal = $contents;
                fclose($handle);
                
                // Afficher les notes
                echo '<div id="note'.$row['id'].'" class="notecard">
                    <div class="innernote">
                    
                        <div id="lastupdated'.$row['id'].'" class="lastupdated">Dernière modification le '.formatDateTime(strtotime($row['updated'])).'</div>
                        
                        <div class="contain_doss_tags" >
							
							<div class="icon_doss"><span class="fa fa-folder"></div>
							<div class="name_doss"><span><input size="40px" autocomplete="off" autocapitalize="off" spellcheck="false" onfocus="updateiddoss(this);" id="doss'.$row['id'].'" type="text" placeholder="Dossier ?" value="'.$row['dossier'].'"></input></span></div>
							
							<div class="icon_tag"><span style="text-align:center; font-size:12px;" class="fa fa-tag"></div>
							<div class="name_tags"><span><input size="50px" autocomplete="off" autocapitalize="off" spellcheck="false" onfocus="updateidtags(this);" id="tags'.$row['id'].'" type="text" placeholder="Tags ?" value="'.$row['tags'].'"></input></span></div>
                        </div>
                        
                        <hr>
                        
                        <h4><input style="color:#0066cc" autocomplete="off" autocapitalize="off" spellcheck="false" onfocus="updateidhead(this);" id="inp'.$row['id'].'" type="text" placeholder="Titre ?" value="'.$row['heading'].'"></input></h4>
                        
                        <div class="noteentry" autocomplete="off" autocapitalize="off" spellcheck="false" onload="initials(this);" onfocus="updateident(this);" id="entry'.$row['id'].'" data-ph="Entrer du texte ou coller une image" contenteditable="true">'.$entryfinal.'</div>
                        
                        <span style="cursor:pointer" title="Supprimer cette note" onclick="deleteNote(\''.$row['id'].'\')" class="fas fa-trash pull-right"></span>
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
