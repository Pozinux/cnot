<?php
@ob_start();
session_start();
?>
<?php
    include 'functions.php';
    include 'credentials.php';
    $pass=$_SESSION['pass'];
    
	if($pass!=APPPASSWORD)
	{
		header('Location: ./login.php');
	}
	include 'db_connect.php';	
?>

<html>

<style type="text/css">
* {
    font-family: 'Calibri';
}
</style>


<head>

	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<title><?php echo JOURNAL_NAME;?></title>

	<script>
		var app_pass = '<?php echo APPPASSWORD;?>';
	</script>
    
</head>
<body>
            
        <?php
            
            $query_droite = 'SELECT * FROM entries WHERE trash = 0 ORDER by dossier';
		
            $res_droite = $con->query($query_droite);
            while($row = mysqli_fetch_array($res_droite, MYSQLI_ASSOC))
            {
                $filename = "entries/".$row["dossier"]."/".$row["id"].".html";
                $handle = fopen($filename, "r");
                $contents = fread($handle, filesize($filename));
                $entryfinal = $contents;
                fclose($handle);

                echo $row['dossier'].' '.$row["sousdossier"].' -- <a href="./entries/'.$row['dossier'].'/'.$row['id'].'.html">'.$row["heading"].'</a> ('.$row["tags"].')<br>';           
            }
        ?>        
    </div>
</body>

</html>
