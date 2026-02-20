<?php
$host = 'localhost';
$db   = 'ManualM';   
$user = 'root';
$pass = '';         // no voy a usar contraseña porque el XAMPP no tiene.
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa";  // Solo para pruebas
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
