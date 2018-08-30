
<?php
//desincronizare pentru conexiunea mysql
usleep(5250);
//informatii conectare mysql
$servername = "localhost";
$username = "id6882721_airsense_user";
$password = "airsense";
$dbname = "id6882721_airsense";

$conn = new mysqli($servername, $username, $password, $dbname);
//verific daca m-am conectat. cand apar conexiuni simultane la baza de date si primesc eroare, reincarc.
if ($conn->connect_error) {
    die("Eroare de conectare, reincarc " . $conn->connect_error . header( "refresh:0;" ));
} 
//selectez inregistrarile din ultima zi.
$sql = "SELECT * FROM airsense where data > date_sub(now(), interval 1 day)";
//selectez doar ultima inregistrare, doar coloana de nivel.

$sql2 = "SELECT calitateaer FROM airsense ORDER BY nr DESC LIMIT 1";

 
$result = $conn->query($sql);
$result2 = $conn->query($sql2);



//daca am inregistrari din ultima zi atunci statia este activa, daca nu, este inactiva.
if ($result->num_rows > 0) {
$Senzor_ok=true;
}else{
$Senzor_ok=false;
}

// trebuie comparat ultimul nivel inregistrat cu nivele prag, pentru a sti in ce caz de viitura suntem (1%, 0.5%, 0.1%)
// corespundenta nivel>tip viitura e data de studiul hidro.

if ($result2->num_rows > 0) {
    $row = $result2->fetch_assoc() ;
        echo  "- Nivelul este sub limita de atentionare : "  . $row["nivel"]. "<br>";
		//cand nivelul este peste 400cm consider ca este viitura Q01%
	if($row["nivel"]>=400){
		 $nivelQ01=true;
		 $nivelQ05=false;
		 $nivelQ1=false;
	}else{
		 $nivelQ01=false;
		
		 }
		 
		 
		 	//cand nivelul este sub 400cm si peste 300cm consider ca este viitura Q05%
		 	if($row["nivel"]>=300 AND $row["nivel"]<400){
		 $nivelQ05=true;
		  $nivelQ01=false;
		 $nivelQ1=false;
		
	}else{
		
		 $nivelQ05=false;
		 
		 }
		 		 	//cand nivelul este sub 300cm si peste 200cm consider ca este viitura Q1%
		 		 	if($row["nivel"]>=200 AND $row["nivel"]<300){
		 $nivelQ1=true;
		  $nivelQ05=false;
		 $nivelQ01=false;
	}else{
		 $nivelQ1=false;
		  
		 }
		 
		 
		 

}else{
 $nivelQ05=false;
		 $nivelQ01=false;
		 $nivelQ1=false;
}

//inchid conexiunea cu baza de date
$conn->close();


?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
<!-- Refresh la fiecare 10 secunde. se poate modifica la un timp mai mare.  -->
	 <meta http-equiv="refresh" content="10" />
     <title>NextFlood Harta inundabilitate</title>
	 
	  <script type="text/javascript">
        <!-- functia  redirectionare pentru cazul Q01 spre pagina corespunzatoare -->

			function Redirect01() {
				setTimeout(function () {
   window.location.href = "http://www.nextflood.tk/hartaQ01.php"; 
}, 5000);
			}
			        <!--  functia redirectionare pentru cazul Q05 spre pagina corespunzatoare -->
			function Redirect05() {
				setTimeout(function () {
   window.location.href = "http://www.nextflood.tk/hartaQ05.php"; 
}, 5000); 
}
          <!-- functia redirectionare pentru cazul Q1 spre pagina corespunzatoare -->
			function Redirect1() {
				setTimeout(function () {
   window.location.href = "http://www.nextflood.tk/hartaQ1.php"; 
}, 5000);
}
        <!-- functia  redirectionare pentru cazul in care nu am nici un pericol spre aceeasi pagina pentru recitire parametrii noi -->
						function RedirectH() {
				setTimeout(function () {
   window.location.href = "http://www.nextflood.tk/harta.php"; 
}, 5000);
}
         //-->
      </script>
	 
	 

    <style>
  
      #map {
        height: 100%;
      }

      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
  

  	    <script type="text/javascript">
				<!--  transfer valorile variabilelor de nivel din php spre javascript utilizand formatul json -->
  var nivelQ1 = <?php echo json_encode($nivelQ1); ?>;
   var nivelQ05 = <?php echo json_encode($nivelQ05); ?>;
      var nivelQ01 = <?php echo json_encode($nivelQ01); ?>;
	  
	  		<!--  conditiile de redirectare spre pagina corespunzatoare -->
	  			if(nivelQ1==true){
		 Redirect1();
		}
			
		if(nivelQ05==true){
 Redirect05();
	}
		
			if(nivelQ01==true){
	 Redirect01();
		}
				if(nivelQ01==false && nivelQ05==false && nivelQ1==false){
	 RedirectH();
		}
	
  </script>
  
  		<!--  divul in care se incarca harta -->
    <div id="map"></div>


<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

   

   
   
   
   
   <script type="text/javascript">

		<!--  preiau valoarea variabilei boolene care face referire la starea statiei -->
var StatieUPTok = <?php echo json_encode($Senzor_ok); ?>;

var culoare;
		<!--  schimb culoarea cercului pulsatoriu in functie de starea statiei.rosu daca e offline de mai mult de o zi. verde daca e ok  -->
if (StatieUPTok==true) {
  culoare="#0fe313";
} else {
    culoare="#e42f0c";
}




		<!-- harta google maps . initializare si parametrii.  -->
    function initialize() {
        var center = new google.maps.LatLng(45.748705, 21.225649);
        
        var mapOptions = {
            center: center,
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        var map = new google.maps.Map(document.getElementById("map"),
                        mapOptions);

        var circle = new google.maps.Circle({
            center: center,
            radius: 300,
            strokeColor: "#000000",
            strokeOpacity: 1,
            strokeWeight: 1,
            fillColor: culoare,
            fillOpacity: 0.7
        });
		
				<!--  desenez cercul pulsatoriu -->
        circle.setMap(map);
        
        var direction = 1;
        var rMin = 150, rMax = 300;
        setInterval(function() {
            var radius = circle.getRadius();
            if ((radius > rMax) || (radius < rMin)) {
                direction *= -1;
            }
            circle.setRadius(radius + direction * 10);
        }, 50);
    }




    google.maps.event.addDomListener(window, 'load', initialize);
</script>
















      

      



    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA08roZd3mbEWLlI3hiQy_n8rmvMly0iYc&callback=initMap">
    </script>
  </body>
</html>