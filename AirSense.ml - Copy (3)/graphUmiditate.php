<?php

//desincronizare intre pagini pentru a nu avea conexiuni concomitente
usleep(1500);

// detalii baza de date
$DB_NAME = 'id6882721_airsense';
$DB_HOST = 'localhost';
$DB_USER = 'id6882721_airsense_user';
$DB_PASS = 'airsense';

  $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

  if (mysqli_connect_errno()) {
 header( "refresh:1;" );
    printf("Conexiunea nu a reusit: %s\n", mysqli_connect_error());
    exit();
  }

//selectez din baza de date doar inregistrarile din ultima zi.
  $result = $mysqli->query('SELECT * FROM airsense WHERE airsense.data>DATE_SUB(CURDATE(), INTERVAL 1 DAY)');
//inchid conexiunea cu baza de date
$mysqli->close();




  $rows = array();
  $table = array();
  $table['cols'] = array(



    array('label' => 'data', 'type' => 'string'),
    array('label' => 'umiditate', 'type' => 'number'),


);
    //citire din result
    foreach($result as $r) {

      $t = array();

    
$t[] = array('v' => (string) $r['data']); 
      $t[] = array('v' => (string) $r['umiditate']); 
      $rows[] = array('c' => $t);
    }

$table['rows'] = $rows;

//conversie in json
$jsonTable = json_encode($table);





?>


<html>
  <head>
  
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">

    //incarcare api vizualizare


    google.load('visualization', '1', {'packages':['corechart']});
    google.setOnLoadCallback(drawChart);

    function drawChart() {

//creeare tabel cu datele preluate (json)
      var data = new google.visualization.DataTable(<?=$jsonTable?>);
      var options = {
           title: 'Valori inregistrate umiditate relativa',
          is3D: 'true',
          width: 600,
          height: 300,
		  		  colors: ['#00BFFF'],
				  		             lineWidth: 3 ,
curveType: 'function'
        };
//instanta de grafic.


      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));



      chart.draw(data, options);
	  //reincarc pagina
	  setTimeout(function(){
   window.location.reload(1);
}, 5000);
    }
	
	
	
	
    </script>
	
	
	
  </head>

  <body>

    <div id="chart_div"></div>
	



  </body>
</html>