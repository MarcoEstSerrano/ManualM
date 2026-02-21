<?php
require('conexion/conexion.php');

if (isset($_POST['email'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    // No guardamos la contraseña aquí porque ya vive segura en Firebase
    $uid = $_POST['uid']; 

    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([$nombre, $email, $uid])) {
        echo "Usuario consagrado en MySQL";
    } else {
        http_response_code(500);
        echo "Error al guardar en la base de datos local";
    }
}
?>