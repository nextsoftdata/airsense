
<?php
//parametrii conectare la baza de date
$servername = "localhost";
$username = "id6882721_airsense_user";
$password = "airsense";
$dbname = "id6882721_airsense";

//conectare baza de date
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//transmitere parametrii de la requestul http la php
	$data=htmlspecialchars($_GET["data"]);
	$ora=htmlspecialchars($_GET["ora"]);
	$temperatura=htmlspecialchars($_GET["temperatura"]);
	$presiune=htmlspecialchars($_GET["presiune"]);
	$umiditate=htmlspecialchars($_GET["umiditate"]);	
	$rezistenta=htmlspecialchars($_GET["rezistenta"]);
	$calitateaer=htmlspecialchars($_GET["calitateaer"]);	
	
echo $data;
echo $ora;
echo $temperatura;
echo $presiune;
echo $umiditate;
echo $rezistenta;
echo $calitateaer;

//inserare in baza de date a unei noi inregistrari cu parametrii primiti.
    $sql = "INSERT INTO airsense (data,ora,temperatura,presiune,umiditate,rezistenta,calitateaer)
    VALUES ('$data', '$ora', '$temperatura', '$presiune', '$umiditate', '$rezistenta', '$calitateaer')";
    
    $conn->exec($sql);
    echo "Inregistrare realizata cu succes";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>