<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

//prueba

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cámara del Tholos - Manual Web</title>
    
    <style>
    body, html {
        margin: 0; padding: 0; height: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Fondo del Olimpo */
    .bg-olympus {
        background-image: url('olympus_tholos.png'); 
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    .overlay {
        background: rgba(0, 0, 0, 0.4); 
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    header {
        background: rgba(0, 0, 0, 0.7);
        color: #f1c40f; 
        padding: 20px;
        text-align: center;
        border-bottom: 2px solid #f1c40f;
    }

    main {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-olympus {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #f1c40f;
        width: 320px;
        text-align: center;
        color: white;
    }

    .btn-god {
        display: block;
        width: 100%;
        padding: 12px 0;
        margin: 10px 0;
        background: #f1c40f;
        color: black;
        text-decoration: none;
        font-weight: bold;
        text-transform: uppercase;
        border-radius: 5px;
        transition: 0.3s;
    }

    .btn-god:hover { background: white; box-shadow: 0 0 15px #f1c40f; }

    .btn-exit { background: #e74c3c !important; color: white !important; }

    footer {
        background: rgba(0, 0, 0, 0.8);
        color: white;
        text-align: center;
        padding: 10px;
    }
</style>
    
</head>
<body class="bg-olympus">

<div class="overlay">
    <header>
        <h2> 
            <?php 
            $nombre_usuario = $_SESSION['nombre'] ?? 'Guerrero'; 
            echo "Bienvenido 👋 " . htmlspecialchars($nombre_usuario);
            ?> 
        </h2>
    </header>

    <main>
        <div class="card-olympus">
            <h2>Panel de Control</h2>
            <a href="crear_tema.php" class="btn-god">Crear Nuevo Tema</a>
            <a href="ver_temas.php" class="btn-god">Ver Temas del Saber</a>
            <a href="logout.php" class="btn-god btn-exit">Abandonar el Olimpo</a>
        </div>
    </main>

    <footer>
        <p>© Manual de Marco 2026 - El conocimiento es poder</p>
    </footer>
</div>

</body>
</html>
