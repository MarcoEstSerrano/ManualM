<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria'];
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "INSERT INTO temas (titulo, descripcion, usuario_id, categoria_id) 
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descripcion, $usuario_id, $categoria_id]);

    header("Location: ver_temas.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CEC | Registro de Bitácora</title>
    <style>
        :root {
            --ishimura-cyan: #00f2ff;
            --ishimura-orange: #ff8000;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
            color: var(--ishimura-cyan);
            overflow: hidden;
            background-color: #050a0f;
        }

        .bg-crear {
            background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), 
                              url('https://images6.alphacoders.com/131/1310657.jpeg'); 
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: absolute;
            width: 100%;
            z-index: -1;
            filter: grayscale(0.5) brightness(0.4);
        }

        .overlay {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle, rgba(0, 242, 255, 0.05) 0%, rgba(0,0,0,0.8) 100%);
        }

        .card-crear {
            background: rgba(0, 20, 30, 0.85);
            backdrop-filter: blur(15px);
            padding: 40px;
            border: 1px solid var(--ishimura-cyan);
            box-shadow: 0 0 30px rgba(0, 242, 255, 0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
            clip-path: polygon(0 5%, 5% 0, 95% 0, 100% 5%, 100% 95%, 95% 100%, 5% 100%, 0 95%);
        }

        h2 {
            color: white;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 30px;
            text-shadow: 0 0 10px var(--ishimura-cyan);
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            background: rgba(0, 242, 255, 0.05);
            border: 1px solid rgba(0, 242, 255, 0.3);
            color: white;
            box-sizing: border-box;
            font-size: 0.9em;
            outline: none;
            font-family: inherit;
        }

        input:focus, textarea:focus {
            border-color: var(--ishimura-cyan);
            background: rgba(0, 242, 255, 0.1);
        }

        textarea {
            resize: none;
            border-left: 3px solid var(--ishimura-cyan);
        }

        option {
            background: #050a0f;
            color: var(--ishimura-cyan);
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: var(--ishimura-orange);
            color: #000;
            border: none;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.3s;
            clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
            letter-spacing: 2px;
        }

        button:hover {
            background-color: white;
            box-shadow: 0 0 20px var(--ishimura-orange);
            transform: scale(1.02);
        }

        .link-volver {
            display: inline-block;
            margin-top: 20px;
            color: rgba(0, 242, 255, 0.5);
            text-decoration: none;
            font-size: 0.8em;
            text-transform: uppercase;
        }

        .link-volver:hover {
            color: white;
        }

        .status-bar {
            font-size: 10px;
            margin-bottom: 10px;
            text-align: left;
            color: var(--ishimura-cyan);
            opacity: 0.7;
        }
    </style>
</head>
<body>

<div class="bg-crear"></div>

<div class="overlay">
    <div class="card-crear">
        <div class="status-bar">CEC_UPLINK_STATUS: CONNECTED...</div>
        <h2>NUEVO REGISTRO</h2>
        
        <form method="POST">
            <input type="text" name="titulo" placeholder="ID DEL REGISTRO (TÍTULO)" required>

            <textarea name="descripcion" rows="6" placeholder="INGRESAR DATOS DE LA BITÁCORA..." required></textarea>

            <select name="categoria" required>
                <option value="" disabled selected>SECTOR DE ARCHIVO</option>
                <option value="1">PROTOCOLOS PHP</option>
                <option value="2">ESTRUCTURAS HTML</option>
                <option value="3">ESTILOS CSS</option>
            </select>

            <button type="submit">Sincronizar con la Red</button>
        </form>

        <a href="index.php" class="link-volver">[ REVERTIR CONEXIÓN ]</a>
    </div>
</div>

</body>
</html>