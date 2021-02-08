<?php
@ob_start();
session_start();
?>
<?php
	include 'functions.php';
	include 'credentials.php';
	$pass=$_SESSION['pass'];
	$search = $_POST['search'];
	if($pass!=APPPASSWORD)
	{
		die('Password Incorrect');
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
	<link rel="stylesheet" type="text/css" href="themes/default.css" />
	<link rel="stylesheet" type="text/css" href="css/page.css" />

	<script>
		var app_pass = '<?php echo APPPASSWORD;?>';
	</script>
</head>
<body style="background:#FF7043;"> <!-- Pour le fond orange de la corbeille -->
	<h2 style="text-align:center; font-weight:500;">Corbeille</h2>
	<?php
		if($search!='') // Si on arrive ici suite à une recherche, ça va afficher les résultat de la recherche + le petit icone en croix pour sortir de la recherche
		{
			echo '<h4 style="text-align:center; font-weight:300;"> Résultat pour '.$search.'. <span style="cursor:pointer;font-weight:700;" onclick="window.location=\'trash.php\'"><span class="fas fa-times"></span></span></h4>';
		}
	?>
	<form action="trash.php" method="POST">
		<h5 style="text-align:center; font-weight:300;"><input autocomplete="off" onfocus="updateidhead(this);" class="searchtrash" style="background:inherit; text-align:center; width:25%;" name="search" id="search" type="text" placeholder="Faire une recherche dans les notes de la corbeille ici"></h5>
	</form>
    
    <div id="containbuttonsstrash">
        <div class="backbutton" onclick="window.location = 'index.php';"><span style="text-align:center; font-size:20px; color:white;"><span title="Revenir aux notes" class="fas fa-arrow-circle-left"></span></span></div>
        <div class="emptytrash" onclick="emptytrash();"><span style="text-align:center; font-size:20px; color:white;"><span title="Vider la corbeille" class="fa fa-trash-alt"></span></span></div>
        <div class="logoutbutton" onclick="window.location = 'logout.php';"><span style="text-align:center;font-size:20px; color:white;"><span title="Se déconnecter" class="fas fa-sign-out-alt"></span></span></div>
    </div>
    
	<br>
	<?php
		$query = 'SELECT * FROM entries WHERE trash = 1 AND (heading like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\' OR entry like \'%'.htmlspecialchars($search,ENT_QUOTES).'%\') ORDER by updated DESC LIMIT 50';
		$res = $con->query($query);
		while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
		{
			$filename = "./entries/".$row["dossier"]."/".$row["id"].".html";
			$handle = fopen($filename, "r");
			$contents = fread($handle, filesize($filename));
			$entryfinal = $contents;
			fclose($handle);
			echo '<div id="note'.$row['id'].'" class="notecard">
            <div class="innernote">
                <span title="Supprimer définitivement" onclick="deletePermanent(\''.$row['id'].'\')" class="fas fa-trash pull-right icon_corbeille_trash" style="cursor: pointer;"></span>
                <span title="Restaurer cette note" onclick="putBack(\''.$row['id'].'\')" class="fa fa-trash-restore-alt pull-right icon_restore_trash" style="margin-right:20px; cursor: pointer;"></span>
                <div id="lastupdated'.$row['id'].'" class="lastupdated">Dernière modification le '.formatDateTime(strtotime($row['updated'])).'</div>
                <h4><input id="doss'.$row['id'].'" type="text" placeholder="dossier ?" value="'.$row['dossier'].'"></input> </h4>
                <h3><input id="inp'.$row['id'].'" type="text" placeholder="Titre ?" value="'.$row['heading'].'"></input> </h3>
                <hr>
                <div class="noteentry" onload="initials(this);" id="entry'.$row['id'].'" data-ph="Entrer un texte ou une image ici" contenteditable="true">'.$entryfinal.'</div>
                <div style="height:30px;"></div>
            </div>
            </div>';
		}
	?>
</body>
	<script src="js/script.js"></script>
</html>
