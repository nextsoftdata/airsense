
<?php
//desincronizare pentru conexiunea mysql
usleep(1950);
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
    while($row = $result2->fetch_assoc()) {
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
    <title>Limita de inundabilitate pentru debitul cu probabilitatea de depasire Q0.5%</title>
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
        var Q05Coordonate = [

{lat:	45.75586648	,lng:	21.28717416	},
{lat:	45.75739066	,lng:	21.28003117	},
{lat:	45.75984567	,lng:	21.27347952	},
{lat:	45.76109938	,lng:	21.26514871	},
{lat:	45.76314876	,lng:	21.25805875	},
{lat:	45.76394012	,lng:	21.2530429	},
{lat:	45.76272356	,lng:	21.24723573	},
{lat:	45.75975011	,lng:	21.24032915	},
{lat:	45.75681354	,lng:	21.2356918	},
{lat:	45.75524162	,lng:	21.233698	},
{lat:	45.75174414	,lng:	21.23242725	},
{lat:	45.75136103	,lng:	21.22664859	},
{lat:	45.7518971	,lng:	21.22432634	},
{lat:	45.7517569	,lng:	21.22068469	},
{lat:	45.75136203	,lng:	21.21654059	},
{lat:	45.74959364	,lng:	21.20979203	},
{lat:	45.74397255	,lng:	21.19510768	},
{lat:	45.73923203	,lng:	21.18357466	},
{lat:	45.73718811	,lng:	21.17884628	},
{lat:	45.73315834	,lng:	21.18251265	},
{lat:	45.73969725	,lng:	21.19960471	},
{lat:	45.74274152	,lng:	21.20930228	},
{lat:	45.74460172	,lng:	21.21157921	},
{lat:	45.74571266	,lng:	21.21878592	},
{lat:	45.74591665	,lng:	21.23064304	},
{lat:	45.74589006	,lng:	21.23549997	},
{lat:	45.74836886	,lng:	21.23858092	},
{lat:	45.75292365	,lng:	21.24262333	},
{lat:	45.7554764	,lng:	21.24545078	},
{lat:	45.75697166	,lng:	21.24791681	},
{lat:	45.75771106	,lng:	21.25095252	},
{lat:	45.75813663	,lng:	21.25491641	},
{lat:	45.75810447	,lng:	21.25842547	},
{lat:	45.75575142	,lng:	21.26859838	},
{lat:	45.75292179	,lng:	21.27857015	},
{lat:	45.75103165	,lng:	21.28617542	}






        ];

  <!--  construire poligon-->
        var Q05Poligon = new google.maps.Polygon({
          paths: Q05Coordonate,
          strokeColor: '#9d15dd',
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: '#1586dd',
          fillOpacity: 0.60
        });
        Q05Poligon.setMap(map);


     // API-ul permite afisarea informatiilor cand se face click pe poligon.
        Q05Poligon.addListener('click', showArrays);

        infoWindow = new google.maps.InfoWindow;
      }

     
      function showArrays(event) {

        var vertices = this.getPath();

        var contentString = '<b>Zona inundabila pentru debitul cu probabilitatea de depasire Q0.5%</b><br>' +
            'Click: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
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