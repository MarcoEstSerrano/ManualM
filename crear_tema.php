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
    <title>Santuario del Saber - Crear Tema</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            color: white;
            overflow: hidden; /* Evita scroll innecesario en esta pantalla */
        }

        /* Fondo del templo en el agua */
        .bg-crear {
            background-image: url('olimpo_medio_agua.png'); 
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: absolute;
            width: 100%;
            z-index: -1;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.5); /* Oscurece un poco la imagen */
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-crear {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            border: 2px solid #f1c40f; /* Borde dorado */
            box-shadow: 0 0 30px rgba(241, 196, 15, 0.2);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            color: #f1c40f;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #f1c40f;
            color: white;
            border-radius: 8px;
            box-sizing: border-box; /* Asegura que el padding no rompa el ancho */
            font-size: 1em;
        }

        textarea {
            resize: none; /* Evita que el usuario deforme la card */
        }

        option {
            background: #2c3e50; /* Color para que se lean las opciones del select */
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #f1c40f;
            color: #000;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #fff;
            box-shadow: 0 0 20px #f1c40f;
            transform: scale(1.02);
        }

        .link-volver {
            display: inline-block;
            margin-top: 20px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 0.9em;
        }

        .link-volver:hover {
            color: #f1c40f;
        }
    </style>
</head>
<body>

<div class="bg-crear"></div>

<div class="overlay">
    <div class="card-crear">
        <h2>Nuevo Saber</h2>
        
        <form method="POST">
            <input type="text" name="titulo" placeholder="Título del Tema" required>

            <textarea name="descripcion" rows="6" placeholder="Describe tu conocimiento..." required></textarea>

            <select name="categoria" required>
                <option value="" disabled selected>Seleccione categoría</option>
                <option value="1">PHP</option>
                <option value="2">HTML</option>
                <option value="3">CSS</option>
            </select>

            <button type="submit">Consagrar Tema</button>
        </form>

        <a href="index.php" class="link-volver">⬅ Volver al Olimpo</a>
    </div>
</div>

</body>
</html>