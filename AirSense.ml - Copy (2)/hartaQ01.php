
<?php
//desincronizare pentru conexiunea mysql
usleep(3600);
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

//selectez doar ultima inregistrare, doar coloana de nivel.

$sql2 = "SELECT nivel FROM nextflooddb ORDER BY nr DESC LIMIT 1";

$result2 = $conn->query($sql2);
//inchid conexiunea.
$conn->close();

// trebuie comparat ultimul nivel inregistrat cu nivele prag, pentru a sti in ce caz de viitura suntem (1%, 0.5%, 0.1%)
// corespundenta nivel>tip viitura e data de studiul hidro.
if ($result2->num_rows > 0) {
  $row = $result2->fetch_assoc() ;
        echo  "- Limita de inundabilitate corespunzatoare nivelului (cm): "  . $row["nivel"]. "<br>";
		
	if($row["nivel"]>=400){
		 $nivelQ01=true;
		 $nivelQ05=false;
		 $nivelQ1=false;
	}else{
		 $nivelQ01=false;
		
		 }
		 
		 
		 
		 	if($row["nivel"]>=300 AND $row["nivel"]<400){
		 $nivelQ05=true;
		  $nivelQ01=false;
		 $nivelQ1=false;
		
	}else{
		
		 $nivelQ05=false;
		 
		 }
		 
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




?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
<!-- Refresh la fiecare 10 secunde. se poate modifica la un timp mai mare.  -->
	 <meta http-equiv="refresh" content="10" />
     <title>Limita de inundabilitate pentru debitul cu probabilitatea de depasire Q0.1%</title>
    <script type="text/javascript">
         
    <!-- functiile  redirectionare pentru fiecare caz spre pagina corespunzatoare -->
			function Redirect01() {
				setTimeout(function () {
   window.location.href = "http://www.nextflood.tk/hartaQ01.php"; 
}, 5000);
			}
			function Redirect05() {
				setTimeout(function () {
   window.location.href = "http://www.nextflood.tk/hartaQ05.php"; 
}, 5000); 
}
  
			function Redirect1() {
				setTimeout(function () {
   window.location.href = "http://www.nextflood.tk/hartaQ1.php"; 
}, 5000);
}
			function RedirectH() {
				setTimeout(function () {
   window.location.href = "http://www.nextflood.tk/harta.php"; 
}, 5000);
}
       
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
<!--  spatiul pentru harta -->

    <div id="map"></div>
    <script>


      var map;
      var infoWindow;

      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
  <!-- coordonate de focus -->
          center: {lat: 45.747266, lng: 21.226553},
  <!-- harta va fi satelitara cu strazile afisate -->

          mapTypeId: 'hybrid'
        });

  <!-- coordonatele poligonului care reprezinta limita de inundabilitate -->
        var Q01Coordonate = [

{lat:	45.75624299	,lng:	21.28725194	},
{lat:	45.75780646	,lng:	21.28028289	},
{lat:	45.76027204	,lng:	21.27370302	},
{lat:	45.76153054	,lng:	21.26534042	},
{lat:	45.76356445	,lng:	21.25830399	},
{lat:	45.76509058	,lng:	21.25302291	},
{lat:	45.76368538	,lng:	21.24693177	},
{lat:	45.7608031	,lng:	21.23995699	},
{lat:	45.75849394	,lng:	21.23439831	},
{lat:	45.75570308	,lng:	21.2323874	},
{lat:	45.7532286	,lng:	21.23180046	},
{lat:	45.75291222	,lng:	21.22777168	},
{lat:	45.75338309	,lng:	21.22422142	},
{lat:	45.75260111	,lng:	21.22054775	},
{lat:	45.75136021	,lng:	21.215652	},
{lat:	45.75001617	,lng:	21.20957004	},
{lat:	45.74532383	,lng:	21.19368269	},
{lat:	45.73960849	,lng:	21.18321965	},
{lat:	45.73772728	,lng:	21.17835569	},
{lat:	45.73297026	,lng:	21.18268376	},
{lat:	45.73881762	,lng:	21.20197293	},
{lat:	45.74233381	,lng:	21.209586	},
{lat:	45.74425586	,lng:	21.21776942	},
{lat:	45.74439357	,lng:	21.22599807	},
{lat:	45.74458776	,lng:	21.23063584	},
{lat:	45.74546718	,lng:	21.23573358	},
{lat:	45.74731999	,lng:	21.24061812	},
{lat:	45.75165786	,lng:	21.24462217	},
{lat:	45.75511241	,lng:	21.24597444	},
{lat:	45.75561886	,lng:	21.24854358	},
{lat:	45.75667591	,lng:	21.25117727	},
{lat:	45.75680495	,lng:	21.25510061	},
{lat:	45.75762921	,lng:	21.25843247	},
{lat:	45.75532906	,lng:	21.26837667	},
{lat:	45.75250271	,lng:	21.27833685	},
{lat:	45.75047912	,lng:	21.2860613	}






        ];

<!--  construire poligon-->
        var Q01Poligon = new google.maps.Polygon({
          paths: Q01Coordonate,
          strokeColor: '#ed0404',
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: '#1b0775',
          fillOpacity: 0.7
        });
        Q01Poligon.setMap(map);

        // API-ul permite afisarea informatiilor cand se face click pe poligon.
        Q01Poligon.addListener('click', showArrays);

        infoWindow = new google.maps.InfoWindow;
      }


      function showArrays(event) {

        var vertices = this.getPath();

        var contentString = '<b>Zona inundabila pentru debitul cu probabilitatea de depasire Q0.1%</b><br>' +
            'click: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
            '<br>';


        for (var i =0; i < vertices.getLength(); i++) {
          var xy = vertices.getAt(i);
          contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
              xy.lng();
        }


        infoWindow.setContent(contentString);
        infoWindow.setPosition(event.latLng);

        infoWindow.open(map);
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVoJ9c89L-0fmW2ilO2zhgyPzju24JTpQ&callback=initMap">
    </script>
  </body>
</html>