<?php

$servername = "localhost";
$username = "id6882721_airsense_user";
$password = "lakelake";
$dbname = "id6882721_airsense";

//conectare la baza de date
$conn = new mysqli($servername, $username, $password, $dbname);
//verificare conexiune
if ($conn->connect_error) {
    die("Eroare de conectare " . $conn->connect_error);
} 


//interogare nivel
$sql = "SELECT nivel FROM airsense ORDER BY nr DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $output[]=$row;
  print(json_encode($output));
	
	} 
}else {
    echo "0";
}




//inchidere conexiune baza de date
$conn->close();

?>