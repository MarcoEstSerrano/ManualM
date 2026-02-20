<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT temas.id, temas.titulo, temas.descripcion, 
               usuarios.nombre AS autor,
               categorias.nombre AS categoria
        FROM temas
        JOIN usuarios ON temas.usuario_id = usuarios.id
        JOIN categorias ON temas.categoria_id = categorias.id
        ORDER BY temas.fecha_creacion DESC";

$stmt = $pdo->query($sql);
$temas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Santuario del Saber - Temas</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: url('olimpo_medio_agua.png'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.8); /* Velo oscuro general */
            min-height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center; /* Centra horizontalmente el contenido */
        }

        .container {
            width: 100%;
            max-width: 800px; /* Ancho máximo para que se vea centrado y ordenado */
            padding: 40px 20px;
        }

        h2 {
            text-align: center;
            color: #f1c40f;
            text-transform: uppercase;
            letter-spacing: 3px;
            border-bottom: 2px solid #f1c40f;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .header-actions {
            text-align: center; /* Centra el botón de crear */
            margin-bottom: 40px;
        }

        .btn-crear {
            display: inline-block;
            background: #f1c40f;
            color: black;
            padding: 12px 25px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-crear:hover {
            background: white;
            box-shadow: 0 0 15px #f1c40f;
        }

        .tema-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(241, 196, 15, 0.3); /* Borde dorado suave */
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        h3 {
            margin-top: 0;
            color: #f1c40f;
            font-size: 1.5em;
            text-align: center; /* Títulos de temas centrados */
        }

        pre {
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 8px;
            white-space: pre-wrap;
            word-wrap: break-word;
            color: #ecf0f1;
            font-family: 'Segoe UI', sans-serif; /* Cambiado a sans-serif para leer mejor */
            border: 1px solid #34495e;
            margin: 20px 0;
        }

        .meta {
            font-size: 0.9em;
            color: #bdc3c7;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 15px;
        }

        .actions {
            text-align: center;
            margin-top: 15px;
        }

        .actions a {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }

        .actions a.delete {
            color: #e74c3c;
        }

        .footer-nav {
            margin-top: 50px;
            text-align: center;
            padding-bottom: 40px;
        }

        .btn-volver {
            color: #f1c40f;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="overlay">
    <div class="container">
        <h2>Lista de Temas del Saber</h2>

        <div class="header-actions">
            <a href="crear_tema.php" class="btn-crear">➕ Crear Nuevo Tema</a>
        </div>

        <?php foreach ($temas as $tema): ?>
            <div class="tema-card">
                <h3><?php echo htmlspecialchars($tema['titulo']); ?></h3>
                
                <pre><?php echo htmlspecialchars($tema['descripcion']); ?></pre>

                <div class="meta">
                    <strong>Autor:</strong> <?php echo htmlspecialchars($tema['autor']); ?> | 
                    <strong>Categoría:</strong> <?php echo htmlspecialchars($tema['categoria']); ?>
                </div>

                <div class="actions">
                    <a href="editar_tema.php?id=<?php echo $tema['id']; ?>">✍️ Editar</a>
                    <a href="eliminar_tema.php?id=<?php echo $tema['id']; ?>" 
                       class="delete"
                       onclick="return confirm('¿Seguro que deseas eliminar este tema?');">🗑️ Eliminar</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="footer-nav">
            <a href="index.php" class="btn-volver">⬅ Volver al Olimpo</a>
        </div>
    </div>
</div>

</body>
</html>