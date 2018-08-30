
<?php
//desincronizare pentru conexiunea mysql
usleep(2450);
//informatii conectare mysql
$servername = "localhost";
$username = "id1592623_nextflood";
$password = "nextflood";
$dbname = "id1592623_nextflood";

$conn = new mysqli($servername, $username, $password, $dbname);
//verific daca m-am conectat. cand apar conexiuni simultane la baza de date si primesc eroare, reincarc.
if ($conn->connect_error) {
    die("Eroare de conectare, reincarc " . $conn->connect_error . header( "refresh:0;" ));
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
     <title>Limita de inundabilitate pentru debitul cu probabilitatea de depasire Q1%</title>
    
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
        var Q1Coordonate = [

{lat:	45.75485603	,lng:	21.28696541	},
{lat:	45.75656103	,lng:	21.27952249	},
{lat:	45.75899292	,lng:	21.27303254	},
{lat:	45.76023706	,lng:	21.26476531	},
{lat:	45.76231739	,lng:	21.25756827	},
{lat:	45.76365266	,lng:	21.25294771	},
{lat:	45.76244958	,lng:	21.24773271	},
{lat:	45.75980842	,lng:	21.24134133	},
{lat:	45.75738225	,lng:	21.2391704	},
{lat:	45.75412779	,lng:	21.23721833	},
{lat:	45.75174869	,lng:	21.2341761	},
{lat:	45.74992286	,lng:	21.22869145	},
{lat:	45.75057306	,lng:	21.22539072	},
{lat:	45.75117824	,lng:	21.22073594	},
{lat:	45.75107771	,lng:	21.21656314	},
{lat:	45.74882455	,lng:	21.2104738	},
{lat:	45.74318433	,lng:	21.19537254	},
{lat:	45.73844293	,lng:	21.18419058	},
{lat:	45.73634454	,lng:	21.17885232	},
{lat:	45.73386972	,lng:	21.18110401	},
{lat:	45.74075754	,lng:	21.19851912	},
{lat:	45.74411546	,lng:	21.20667556	},
{lat:	45.74752995	,lng:	21.21740372	},
{lat:	45.74643002	,lng:	21.22559134	},
{lat:	45.74662276	,lng:	21.23026104	},
{lat:	45.74753397	,lng:	21.23477342	},
{lat:	45.74924401	,lng:	21.23782886	},
{lat:	45.7529812	,lng:	21.24024537	},
{lat:	45.75670042	,lng:	21.2421188	},
{lat:	45.75906725	,lng:	21.24457363	},
{lat:	45.76080617	,lng:	21.25110123	},
{lat:	45.76038251	,lng:	21.25559568	},
{lat:	45.75886395	,lng:	21.25923758	},
{lat:	45.75659615	,lng:	21.2690418	},
{lat:	45.75376087	,lng:	21.27903348	},
{lat:	45.75186609	,lng:	21.28634778	},
{lat:	45.75485603	,lng:	21.28696541	}





        ];

  <!--  construire poligon-->
        var Q1Poligon = new google.maps.Polygon({
          paths: Q1Coordonate,
          strokeColor: '#dbad39',
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: '#15bfdd',
          fillOpacity: 0.35
        });
        Q1Poligon.setMap(map);

     // API-ul permite afisarea informatiilor cand se face click pe poligon.
        Q1Poligon.addListener('click', showArrays);

        infoWindow = new google.maps.InfoWindow;
      }


      function showArrays(event) {

        var vertices = this.getPath();

        var contentString = '<b>Zona inundabila pentru debitul cu probabilitatea de depasire Q1%</b><br>' +
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