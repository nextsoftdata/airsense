
<?php
//desincronizare pentru conexiunea mysql
usleep(5250);
//informatii conectare mysql
$servername = "localhost";
$username = "id6882721_airsense_user";
$password = "airsense";
$dbname = "id6882721_airsense";
$textcalitateaer = "Statia este inactiva";

$conn = new mysqli($servername, $username, $password, $dbname);
//verific daca m-am conectat. cand apar conexiuni simultane la baza de date si primesc eroare, reincarc.
if ($conn->connect_error) {
    die("Eroare de conectare, reincarc " . $conn->connect_error . header( "refresh:0;" ));
} 
//selectez inregistrarile din ultima zi.
$sql = "SELECT * FROM airsense where data > date_sub(now(), interval 1 day)";
//selectez doar ultima inregistrare, doar coloana de nivel.

$sql2 = "SELECT calitateaer FROM airsense ORDER BY id DESC LIMIT 1";

 
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
        echo  "- Evaluarea starii de calitate a aerului -"  . $row["calitateaer"]. "%". "<br>";
		//cand nivelul este peste 400cm consider ca este viitura Q01%
	if($row["calitateaer"]>=90){
		 $textcalitateaer="Stare foarte buna";
	}else{
			
		 }

		 	if($row["calitateaer"]>=70 AND $row["calitateaer"]<90){
		 $textcalitateaer="Stare buna";
		
	}else{
		

		 
		 }

		 		 	if($row["calitateaer"]>=60 AND $row["calitateaer"]<70){
		 $textcalitateaer="Stare normala";
	}else{

		  
		 }
		 
		 
		 
		 		 		 	if($row["calitateaer"]>=50 AND $row["calitateaer"]<60){
		 $textcalitateaer="Stare satisfacatoare";
	}else{

		  
		 }
		 
		 
		 		 		 		 	if($row["calitateaer"]>=40 AND $row["calitateaer"]<50){
		 $textcalitateaer="Stare nesatisfacatoare";
	}else{

		  
		 }
		 
		 		 		 		 		 	if($row["calitateaer"]>=20 AND $row["calitateaer"]<40){
		 $textcalitateaer="Stare rea";
	}else{

		  
		 }
		 
		 	if($row["calitateaer"]<20){
		 $textcalitateaer="Stare periculoasa";
	}else{
			
		 }

}else{

}

//inchid conexiunea cu baza de date
$conn->close();


?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=yes">
    <meta charset="utf-8">
<!-- Refresh la fiecare 10 secunde. se poate modifica la un timp mai mare.  -->
	 <meta http-equiv="refresh" content="10" />
     <title>Harta senzori AirSense</title>
	 
 

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
  var textcalitateaer = <?php echo json_encode($textcalitateaer); ?>;
	  
	  		<!--  conditiile de redirectare spre pagina corespunzatoare -->

	
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

     
      var labelIndex = 0;


		<!-- harta google maps . initializare si parametrii.  -->
    function initialize() {
        var center = new google.maps.LatLng(46.77201545571916, 23.59680506514883);
        var markerlocation =  { lat: 46.77201545571916, lng: 23.59680506514883 };
        var mapOptions = {
            center: center,
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        var map = new google.maps.Map(document.getElementById("map"),
                        mapOptions);

        var circle = new google.maps.Circle({
            center: center,
            radius: 50,
            strokeColor: "#0000ff",
            strokeOpacity: 1,
            strokeWeight: 1,
            fillColor: culoare,
            fillOpacity: 0.7
        });
		
				<!--  desenez cercul pulsatoriu -->
        circle.setMap(map);
        
        var direction = 1;
        var rMin = 1, rMax = 50;
        setInterval(function() {
            var radius = circle.getRadius();
            if ((radius > rMax) || (radius < rMin)) {
                direction *= -1;
            }
            circle.setRadius(radius + direction * 10);
        }, 50);
		

		
		
	        // Add a marker at the center of the map.
        addMarker(markerlocation, map);	
    }




	  
	  
      // Adds a marker to the map.
      function addMarker(location, map) {
        // Add the marker at the clicked location, and add the next-available label
        // from the array of alphabetical characters.
        var marker = new google.maps.Marker({
          position: location,
          label: textcalitateaer,
          map: map
        });
      }


    google.maps.event.addDomListener(window, 'load', initialize);
</script>
















      

      



    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA08roZd3mbEWLlI3hiQy_n8rmvMly0iYc&callback=initMap">
    </script>
  </body>
</html>