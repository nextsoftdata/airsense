
<?php
//parametrii conectare la baza de date
$servername = "localhost";
$username = "id1592623_nextflood";
$password = "nextflood";
$dbname = "id1592623_nextflood";

//conectare baza de date
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//transmitere parametrii de la requestul http la php
	$data=htmlspecialchars($_GET["data"]);
	$ora=htmlspecialchars($_GET["ora"]);
	$nivel=htmlspecialchars($_GET["nivel"]);
echo $data;
echo $ora;
echo $nivel;

//inserare in baza de date a unei noi inregistrari cu parametrii primiti.
    $sql = "INSERT INTO nextflooddb (data,ora,nivel)
    VALUES ('$data', '$ora', '$nivel')";
    
    $conn->exec($sql);
    echo "Inregistrare realizata cu succes";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>