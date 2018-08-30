
<?php
//desincronizare pentru conexiunea mysql
usleep(1050);
//informatii conectare mysql
$servername = "localhost";
$username = "id1592623_nextflood";
$password = "nextflood";
$dbname = "id1592623_nextflood";

$conn = new mysqli($servername, $username, $password, $dbname);
//verific daca m-am conectat. cand apar conexiuni simultane la baza de date si primesc eroare, reincarc.
if ($conn->connect_error) {
    die("Eroare de conectare, reincarc  " . $conn->connect_error . header( "refresh:0;" ));
} 


echo "<strong>" . "STATISTICI NIVELE BEGA (P. Mihai Viteazul):" .  "</strong><br>";
    	echo "<br />";

//returnez nivelul minim pentru ultima zi si il afisez
$sql4 = "SELECT nivel , MIN(nivel) FROM nextflooddb where data > date_sub(now(), interval 1 day)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Nivelul minim 24h: " . $row4["MIN(nivel)"]. "cm"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez nivelul minim pentru ultima luna si il afisez

$sql5 = "SELECT nivel , MIN(nivel) FROM nextflooddb where data > date_sub(now(), interval 1 month)";

$result5 = $conn->query($sql5);

if ($result5->num_rows > 0) {

    while($row5 = $result5->fetch_assoc()) {
    echo "Nivelul minim lunar: " . $row5["MIN(nivel)"]. "cm"  . "<br>";
    	    	echo "<br />";
	}
} else {
    echo "Nu exista inregistrari";
}

//returnez nivelul mediu pentru ultima zi si il afisez
$sql2 = "SELECT nivel , AVG(nivel) FROM nextflooddb where data > date_sub(now(), interval 1 day)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Nivelul mediu 24h: " . $row2["AVG(nivel)"]. "cm"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez nivelul minim pentru ultima luna si il afisez

$sql = "SELECT nivel , AVG(nivel) FROM nextflooddb where data > date_sub(now(), interval 1 month)";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
    echo "Nivelul mediu lunar: " . $row["AVG(nivel)"]. "cm"  . "<br>";
    	    	
	}
} else {
    echo "Nu exista inregistrari";
}

//returnez nivelul mediu pentru ultimul an si il afisez

$sql3 = "SELECT nivel , AVG(nivel) FROM nextflooddb where data > date_sub(now(), interval 1 year)";

$result3 = $conn->query($sql3);

if ($result3->num_rows > 0) {

    while($row3 = $result3->fetch_assoc()) {
    echo "Nivelul mediu anual: " . $row3["AVG(nivel)"]. "cm"  . "<br>";
    	    	echo "<br />";
	}
} else {
    echo "Nu exista inregistrari";
}

//returnez nivelul maxim pentru ultima zi si il afisez

$sql6 = "SELECT nivel , MAX(nivel) FROM nextflooddb where data > date_sub(now(), interval 1 day)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Nivelul maxim 24h: " . $row6["MAX(nivel)"]. "cm"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez nivelul maxim pentru ultima luna si il afisez

$sql7 = "SELECT nivel , MAX(nivel) FROM nextflooddb where data > date_sub(now(), interval 1 month)";

$result7 = $conn->query($sql7);

if ($result7->num_rows > 0) {

    while($row7 = $result7->fetch_assoc()) {
    echo "Nivelul maxim lunar: " . $row7["MAX(nivel)"]. "cm"  . "<br>";
 
	}
} else {
    echo "Nu exista inregistrari";
}


//returnez nivelul maxim pentru ultimul an si il afisez

$sql8 = "SELECT nivel , MAX(nivel) FROM nextflooddb where data > date_sub(now(), interval 1 year)";

$result8 = $conn->query($sql8);

if ($result8->num_rows > 0) {

    while($row8 = $result8->fetch_assoc()) {
    echo "Nivelul maxim anual: " . $row8["MAX(nivel)"]. "cm"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

$conn->close();

 $page = $_SERVER['PHP_SELF'];
 $sec = "10";
 header("Refresh: $sec; url=$page");




?>