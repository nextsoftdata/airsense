<?php
//desincronizare pentru conexiunea mysql
usleep(1050);
//informatii conectare mysql
$servername = "localhost";
$username = "id6882721_airsense_user";
$password = "airsense";
$dbname = "id6882721_airsense";

$conn = new mysqli($servername, $username, $password, $dbname);
//verific daca m-am conectat. cand apar conexiuni simultane la baza de date si primesc eroare, reincarc.
if ($conn->connect_error) {
    die("Eroare de conectare, reincarc  " . $conn->connect_error . header( "refresh:0;" ));
} 


echo "<strong>" . "RAPORT STATIE DE MASURARE AIRSENSE:" .  "</strong><br>";
    	echo "<br />";

		
		
		echo "<strong>" . ":CALITATEA AERULUI:" .  "</strong><br>";	
//returnez calitateaer minima pentru ultima zi + afisare
$sql4 = "SELECT calitateaer , MIN(calitateaer) FROM airsense where data > date_sub(now(), interval 1 day)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Calitate aer minima/24h: " . $row4["MIN(calitateaer)"]. "%"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez calitateaer medie pentru ultima zi si o afisez
$sql2 = "SELECT calitateaer , AVG(calitateaer) FROM airsense where data > date_sub(now(), interval 1 day)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Calitate aer medie/24h: " . $row2["AVG(calitateaer)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez calitateaer maxima pentru ultima zi si o afisez
$sql6 = "SELECT calitateaer , MAX(calitateaer) FROM airsense where data > date_sub(now(), interval 1 day)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Calitate aer maxima/24h: " . $row6["MAX(calitateaer)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez calitateaer minima pentru ultima zi + afisare
$sql4 = "SELECT calitateaer , MIN(calitateaer) FROM airsense where data > date_sub(now(), interval 1 month)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Calitate aer minima/luna: " . $row4["MIN(calitateaer)"]. "%"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez calitateaer medie pentru ultima zi si o afisez
$sql2 = "SELECT calitateaer , AVG(calitateaer) FROM airsense where data > date_sub(now(), interval 1 month)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Calitate aer medie/luna: " . $row2["AVG(calitateaer)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez calitateaer maxima pentru ultima zi si o afisez
$sql6 = "SELECT calitateaer , MAX(calitateaer) FROM airsense where data > date_sub(now(), interval 1 month)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Calitate aer maxima/luna: " . $row6["MAX(calitateaer)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez calitateaer minima pentru ultima zi + afisare
$sql4 = "SELECT calitateaer , MIN(calitateaer) FROM airsense where data > date_sub(now(), interval 1 year)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Calitate aer minima/an: " . $row4["MIN(calitateaer)"]. "%"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez calitateaer medie pentru ultima zi si o afisez
$sql2 = "SELECT calitateaer , AVG(calitateaer) FROM airsense where data > date_sub(now(), interval 1 year)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Calitate aer medie/an: " . $row2["AVG(calitateaer)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez calitateaer maxima pentru ultima zi si o afisez
$sql6 = "SELECT calitateaer , MAX(calitateaer) FROM airsense where data > date_sub(now(), interval 1 year)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Calitate aer maxima/an: " . $row6["MAX(calitateaer)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}



///////////////////////////////////////////////////////////////////
		
		
		
	echo "<strong>" . ":TEMPERATURA:" .  "</strong><br>";	
		
		
//returnez temperatura minima pentru ultima zi + afisare
$sql4 = "SELECT temperatura , MIN(temperatura) FROM airsense where data > date_sub(now(), interval 1 day)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Temperatura minima/24h: " . $row4["MIN(temperatura)"]. "*C"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez temperatura medie pentru ultima zi si o afisez
$sql2 = "SELECT temperatura , AVG(temperatura) FROM airsense where data > date_sub(now(), interval 1 day)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Temperatura medie/24h: " . $row2["AVG(temperatura)"]. "*C"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez temperatura maxima pentru ultima zi si o afisez
$sql6 = "SELECT temperatura , MAX(temperatura) FROM airsense where data > date_sub(now(), interval 1 day)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Temperatura maxima/24h: " . $row6["MAX(temperatura)"]. "*C"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez temperatura minima pentru ultima zi + afisare
$sql4 = "SELECT temperatura , MIN(temperatura) FROM airsense where data > date_sub(now(), interval 1 month)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Temperatura minima/luna: " . $row4["MIN(temperatura)"]. "*C"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez temperatura medie pentru ultima zi si o afisez
$sql2 = "SELECT temperatura , AVG(temperatura) FROM airsense where data > date_sub(now(), interval 1 month)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Temperatura medie/luna: " . $row2["AVG(temperatura)"]. "*C"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez temperatura maxima pentru ultima zi si o afisez
$sql6 = "SELECT temperatura , MAX(temperatura) FROM airsense where data > date_sub(now(), interval 1 month)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Temperatura maxima/luna: " . $row6["MAX(temperatura)"]. "*C"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez temperatura minima pentru ultima zi + afisare
$sql4 = "SELECT temperatura , MIN(temperatura) FROM airsense where data > date_sub(now(), interval 1 year)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Temperatura minima/an: " . $row4["MIN(temperatura)"]. "*C"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez temperatura medie pentru ultima zi si o afisez
$sql2 = "SELECT temperatura , AVG(temperatura) FROM airsense where data > date_sub(now(), interval 1 year)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Temperatura medie/an: " . $row2["AVG(temperatura)"]. "*C"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez temperatura maxima pentru ultima zi si o afisez
$sql6 = "SELECT temperatura , MAX(temperatura) FROM airsense where data > date_sub(now(), interval 1 year)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Temperatura maxima/an: " . $row6["MAX(temperatura)"]. "*C"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}





//////////////////////////////////////
	echo "<strong>" . ":PRESIUNEA:" .  "</strong><br>";	
	
//returnez presiune minima pentru ultima zi + afisare
$sql4 = "SELECT presiune , MIN(presiune) FROM airsense where data > date_sub(now(), interval 1 day)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Presiune minima/24h: " . $row4["MIN(presiune)"]. "mmHG"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez presiune medie pentru ultima zi si o afisez
$sql2 = "SELECT presiune , AVG(presiune) FROM airsense where data > date_sub(now(), interval 1 day)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Presiune medie/24h: " . $row2["AVG(presiune)"]. "mmHG"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez presiune maxima pentru ultima zi si o afisez
$sql6 = "SELECT presiune , MAX(presiune) FROM airsense where data > date_sub(now(), interval 1 day)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Presiune maxima/24h: " . $row6["MAX(presiune)"]. "mmHG"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez presiune minima pentru ultima zi + afisare
$sql4 = "SELECT presiune , MIN(presiune) FROM airsense where data > date_sub(now(), interval 1 month)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Presiune minima/luna: " . $row4["MIN(presiune)"]. "mmHG"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez presiune medie pentru ultima zi si o afisez
$sql2 = "SELECT presiune , AVG(presiune) FROM airsense where data > date_sub(now(), interval 1 month)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Presiune medie/luna: " . $row2["AVG(presiune)"]. "mmHG"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez presiune maxima pentru ultima zi si o afisez
$sql6 = "SELECT presiune , MAX(presiune) FROM airsense where data > date_sub(now(), interval 1 month)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Presiune maxima/luna: " . $row6["MAX(presiune)"]. "mmHG"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez presiune minima pentru ultima zi + afisare
$sql4 = "SELECT presiune , MIN(presiune) FROM airsense where data > date_sub(now(), interval 1 year)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Presiune minima/an: " . $row4["MIN(presiune)"]. "mmHG"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez presiune medie pentru ultima zi si o afisez
$sql2 = "SELECT presiune , AVG(presiune) FROM airsense where data > date_sub(now(), interval 1 year)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Presiune medie/an: " . $row2["AVG(presiune)"]. "mmHG"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez presiune maxima pentru ultima zi si o afisez
$sql6 = "SELECT presiune , MAX(presiune) FROM airsense where data > date_sub(now(), interval 1 year)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Presiune maxima/an: " . $row6["MAX(presiune)"]. "mmHG"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}



//////////////////////////////////////
	echo "<strong>" . ":UMIDITATEA:" .  "</strong><br>";	
	
//returnez umiditate minima pentru ultima zi + afisare
$sql4 = "SELECT umiditate , MIN(umiditate) FROM airsense where data > date_sub(now(), interval 1 day)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Umiditate minima/24h: " . $row4["MIN(umiditate)"]. "%"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez umiditate medie pentru ultima zi si o afisez
$sql2 = "SELECT umiditate , AVG(umiditate) FROM airsense where data > date_sub(now(), interval 1 day)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Umiditate medie/24h: " . $row2["AVG(umiditate)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez umiditate maxima pentru ultima zi si o afisez
$sql6 = "SELECT umiditate , MAX(umiditate) FROM airsense where data > date_sub(now(), interval 1 day)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Umiditate maxima/24h: " . $row6["MAX(umiditate)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez umiditate minima pentru ultima zi + afisare
$sql4 = "SELECT umiditate , MIN(umiditate) FROM airsense where data > date_sub(now(), interval 1 month)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Umiditate minima/luna: " . $row4["MIN(umiditate)"]. "%"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez umiditate medie pentru ultima zi si o afisez
$sql2 = "SELECT umiditate , AVG(umiditate) FROM airsense where data > date_sub(now(), interval 1 month)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Umiditate medie/luna: " . $row2["AVG(umiditate)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez umiditate maxima pentru ultima zi si o afisez
$sql6 = "SELECT umiditate , MAX(umiditate) FROM airsense where data > date_sub(now(), interval 1 month)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Umiditate maxima/luna: " . $row6["MAX(umiditate)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez umiditate minima pentru ultima zi + afisare
$sql4 = "SELECT umiditate , MIN(umiditate) FROM airsense where data > date_sub(now(), interval 1 year)";

$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {

    while($row4 = $result4->fetch_assoc()) {
    echo "Umiditate minima/an: " . $row4["MIN(umiditate)"]. "%"  . "<br>"; 

	}
} else {
    echo "Nu exista inregistrari";
}


//returnez umiditate medie pentru ultima zi si o afisez
$sql2 = "SELECT umiditate , AVG(umiditate) FROM airsense where data > date_sub(now(), interval 1 year)";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {

    while($row2 = $result2->fetch_assoc()) {
    echo "Umiditate medie/an: " . $row2["AVG(umiditate)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}

//returnez umiditate maxima pentru ultima zi si o afisez
$sql6 = "SELECT umiditate , MAX(umiditate) FROM airsense where data > date_sub(now(), interval 1 year)";

$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {

    while($row6 = $result6->fetch_assoc()) {
    echo "Umiditate maxima/an: " . $row6["MAX(umiditate)"]. "%"  . "<br>";

	}
} else {
    echo "Nu exista inregistrari";
}



//////////////////////////////////////





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