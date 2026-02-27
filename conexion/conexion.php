<?php
$host = "localhost";
$user = "root";
$pass = "Admin$1234"; 
$db   = "ManualM";

try {
    // Creamos la conexión tipo PDO para que coincida con tus otros archivos
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Configuramos para que PDO nos avise de CUALQUIER error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // También creamos $conn por si algún otro archivo viejo todavía usa mysqli
    $conn = mysqli_connect($host, $user, $pass, $db);

} catch (PDOException $e) {
    die("Error en el Olimpo (Conexión): " . $e->getMessage());
}
?>

