<?php
//desincronizare pentru conexiunea mysql
usleep(5100);
//informatii conectare mysql
$servername = "localhost";
$username = "id6882721_airsense_user";
$password = "airsense";
$dbname = "id6882721_airsense";


$conn = new mysqli($servername, $username, $password, $dbname);
//verific daca m-am conectat. cand apar conexiuni simultane la baza de date si primesc eroare, reincarc.
if ($conn->connect_error) {
    die("Eroare de conectare, reincarc " . $conn->connect_error . header( "refresh:1;" ));
} 

echo "<strong>" . "Valori masurate in timp real:" .  "</strong><br>";
    	echo "<br />";

//selectez ultimele 10  inregistrari si le listez in ordine descrescatoare.cea mai recenta in partea inferioara.
$sql = "SELECT * FROM (SELECT id, data, ora, calitateaer, temperatura, presiune, umiditate
           FROM airsense
          ORDER BY id DESC LIMIT 1) airsenseselect
  ORDER BY airsenseselect.id ASC";




$result = $conn->query($sql);
//inchid conexiunea.
$conn->close();
//afisez pe rand inregistrarile
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    echo "Data:  " . $row["data"]. " - Ora:  "  . $row["ora"].  " - Calitateaer:  "  . $row["calitateaer"]. "%". " - Temperatura:  ". $row["temperatura"]. " *C" ." - Presiunea:  ". $row["presiune"]. "mmHG". " - Umiditatea:  ". $row["umiditate"]. "%"."<br>";
        	//echo "<br />";


	
		
	}
} else {
    echo "Nu exista inregistrari";
}

?>


<html>
  <head>
      <script type="text/javascript">
  	  //reincarc pagina
	  setTimeout(function(){
   window.location.reload(1);
}, 5000);
    }
	
	
	
	
    </script>
	
	
	
  </head>

  <body>

  </body>
</html>