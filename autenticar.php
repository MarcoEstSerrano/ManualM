<?php
session_start();
require('conexion/conexion.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // 1. Buscamos al guerrero por su email de Google en nuestra tabla local
    // eso si pido no solo el id sino tambien el nombre para que no salga bienvenido y un correo casi entero como nombre
    // sino que salga bienvenido y el nombre nada mas que agrega cuando se registra.
    $sql = "SELECT id, nombre FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    

    if ($usuario) {
        // 2. ¡Éxito! Guardamos el ID numérico (el 1) en la sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        
        // También es buena idea guardar el email para usarlo en el resto del sitio
        $_SESSION['email'] = $email; 
        
        $_SESSION['nombre'] = $usuario['nombre'];
        
        echo "Acceso concedido, ID local: " . $usuario['id'];
    } else {
        // 3. Si el email no está en phpMyAdmin, el acceso es denegado localmente
        http_response_code(404);
        echo "Mortal no registrado en el reino local.";
    }
}
?>