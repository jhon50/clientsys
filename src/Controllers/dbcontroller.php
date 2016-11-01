 <?php

$array = parse_ini_file("config/db.ini", true);
$array = array_shift($array);

$servername = $array['localhost'];
$username = $array['username']; 
$password = $array['password'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=clientsys", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?> 