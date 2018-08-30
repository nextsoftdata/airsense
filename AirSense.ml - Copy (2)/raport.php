<?php
//desincronizare pentru conexiunea mysql
usleep(5100);
//informatii conectare mysql
$servername = "localhost";
$username = "id1592623_nextflood";
$password = "nextflood";
$dbname = "id1592623_nextflood";


$conn = new mysqli($servername, $username, $password, $dbname);
//verific daca m-am conectat. cand apar conexiuni simultane la baza de date si primesc eroare, reincarc.
if ($conn->connect_error) {
    die("Eroare de conectare, reincarc " . $conn->connect_error . header( "refresh:1;" ));
} 

echo "<strong>" . "NIVELE BEGA (P. Mihai Viteazul):" .  "</strong><br>";
    	echo "<br />";

//selectez ultimele 20 de inregistrari si le listez in ordine descrescatoare.cea mai recenta in partea inferioara.
$sql = "SELECT * FROM (SELECT nr, data, ora, nivel
           FROM nextflooddb
          ORDER BY nr DESC LIMIT 20) nextflooddbselect
  ORDER BY nextflooddbselect.nr ASC";




$result = $conn->query($sql);
//inchid conexiunea.
$conn->close();
//afisez pe rand inregistrarile
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    echo "Nr:  " . $row["nr"]. " - Data:  " . $row["data"]. " - Ora:  "  . $row["ora"].  "- Nivel:  "  . $row["nivel"]. "cm" ."<br>";
        	//echo "<br />";


	
		
	}
} else {
    echo "Nu exista inregistrari";
}

//reincarc pagina cu noile valori.
 $page = $_SERVER['PHP_SELF'];
 $sec = "3";
 header("Refresh: $sec; url=$page");

?>

