<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM temas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $tema = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria'];

    $sql = "UPDATE temas 
            SET titulo = ?, descripcion = ?, categoria_id = ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descripcion, $categoria_id, $_GET['id']]);

    header("Location: ver_temas.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Santuario del Saber - Editar Tema</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            color: white;
            overflow: hidden;
        }

        /* Reutilizamos el fondo del Olimpo para consistencia */
        .bg-editar {
            background-image: url('https://wallpapers.com/images/hd/writing-with-maroon-quill-qqpw204dld913g68.jpg'); 
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: absolute;
            width: 100%;
            z-index: -1;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.6); 
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-editar {
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 20px;
            border: 2px solid #f1c40f;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 550px;
            text-align: center;
        }

        h2 {
            color: #f1c40f;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(241, 196, 15, 0.5);
            color: white;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1em;
            outline: none;
        }

        input:focus, textarea:focus {
            border-color: #f1c40f;
            background: rgba(255, 255, 255, 0.1);
        }

        textarea {
            resize: none;
        }

        option {
            background: #1a1a1a;
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
            transition: 0.4s;
        }

        button:hover {
            background-color: #fff;
            box-shadow: 0 0 20px #f1c40f;
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

<div class="bg-editar"></div>

<div class="overlay">
    <div class="card-editar">
        <h2>Refinar Conocimiento</h2>

        <form method="POST">
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($tema['titulo']); ?>" placeholder="Título" required>

            <textarea name="descripcion" rows="8" required><?php echo htmlspecialchars($tema['descripcion']); ?></textarea>

            <select name="categoria" required>
                <option value="1" <?php echo ($tema['categoria_id'] == 1) ? 'selected' : ''; ?>>PHP</option>
                <option value="2" <?php echo ($tema['categoria_id'] == 2) ? 'selected' : ''; ?>>HTML</option>
                <option value="3" <?php echo ($tema['categoria_id'] == 3) ? 'selected' : ''; ?>>CSS</option>
            </select>

            <button type="submit">Actualizar Pergamino</button>
        </form>

        <a href="ver_temas.php" class="link-volver">⬅ Volver al Santuario</a>
    </div>
</div>

</body>
</html>