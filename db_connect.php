<?php

// Create connection
$conn = new mysqli(HOSTDB, USERDB, PASSWORDDB);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS ".DATABASEDB;
if ($conn->query($sql) === TRUE) {
} else {
	echo "Error creating database: " . $conn->error;
}
$conn->close();
$con = new mysqli(HOSTDB, USERDB, PASSWORDDB, DATABASEDB);
if (mysqli_connect_errno($con)){
	echo 'Could not connect: ' . mysqli_connect_error();
}
$con->query("SET NAMES 'utf8';");
// If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.
$query='CREATE TABLE IF NOT EXISTS entries (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,trash int(11) DEFAULT 0, heading text,entry text,created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated TIMESTAMP, dossier text, tags text)';
$con->query($query);
$query='SELECT * FROM entries';
$res = $con->query($query);

//if($res->num_rows==0) // Si à la connexion il n'y a aucune note on en créé une
//{
//	#$entry = '<h3 style="text-align: center;">Bienvenue !</h3><div><br></div><div>Exemple de texte</div>';
//	$entry = 'Entrer du texte ou coller une image ici.';
//
//	$query="INSERT INTO entries (heading,entry) values ('Titre ?','".htmlspecialchars($entry,ENT_QUOTES)."')";
//	
//	$con->query($query) or die(mysqli_error($con));
//	$query="SELECT id from entries ORDER by id DESC";
//	$res = $con->query($query);
//	$row = mysqli_fetch_array($res,MYSQLI_ASSOC);
//	$handle = fopen("entries/".$row['id'].".html", 'w+') or die("Unable to open file!");
//	fwrite($handle, $entry) or die("Unable to write to file!");
//	fclose($handle);
//	
//}

?>
