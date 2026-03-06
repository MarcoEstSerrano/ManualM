<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// 1. CAPTURAR EL FILTRO (si existe)
$categoria_id = isset($_GET['cat']) ? $_GET['cat'] : null;

// 2. MODIFICAR LA CONSULTA SQL
$sql = "SELECT temas.id, temas.titulo, temas.descripcion, 
               usuarios.nombre AS autor,
               categorias.nombre AS categoria,
               temas.categoria_id
        FROM temas
        JOIN usuarios ON temas.usuario_id = usuarios.id
        JOIN categorias ON temas.categoria_id = categorias.id";

if ($categoria_id) {
    $sql .= " WHERE temas.categoria_id = :cat_id";
}

$sql .= " ORDER BY temas.fecha_creacion DESC";

$stmt = $pdo->prepare($sql);

if ($categoria_id) {
    $stmt->execute(['cat_id' => $categoria_id]);
} else {
    $stmt->execute();
}

$temas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Santuario del Saber - Temas</title>
    <style>
        /* ... (Mantenemos tus estilos anteriores y agregamos estos nuevos) ... */
        
        .filter-container {
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-filter {
            padding: 10px 20px;
            border: 1px solid #f1c40f;
            background: rgba(0,0,0,0.5);
            color: #f1c40f;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9em;
            transition: 0.3s;
            text-transform: uppercase;
            font-weight: bold;
        }

        .btn-filter:hover, .btn-filter.active {
            background: #f1c40f;
            color: black;
            box-shadow: 0 0 10px #f1c40f;
        }

        /* Estilo para los botones por color según categoría (opcional) */
        .cat-1 { border-color: #6181b6; color: #6181b6; } /* PHP azul */
        .cat-2 { border-color: #e34f26; color: #e34f26; } /* HTML naranja */
        .cat-3 { border-color: #1572b6; color: #1572b6; } /* CSS azul claro */

        /* Estilos base que ya tenías */
        body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background-image: url('image/olimpo_medio_agua.png'); background-size: cover; background-position: center; background-attachment: fixed; color: white; }
        .overlay { background: rgba(0, 0, 0, 0.8); min-height: 100vh; width: 100%; display: flex; justify-content: center; }
        .container { width: 100%; max-width: 800px; padding: 40px 20px; }
        h2 { text-align: center; color: #f1c40f; text-transform: uppercase; letter-spacing: 3px; border-bottom: 2px solid #f1c40f; padding-bottom: 10px; margin-bottom: 30px; }
        .tema-card { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(241, 196, 15, 0.3); padding: 25px; margin-bottom: 25px; border-radius: 15px; backdrop-filter: blur(10px); }
        pre { background: rgba(0, 0, 0, 0.5); padding: 15px; border-radius: 8px; white-space: pre-wrap; color: #ecf0f1; border: 1px solid #34495e; }
        .btn-crear { display: inline-block; background: #f1c40f; color: black; padding: 12px 25px; text-decoration: none; font-weight: bold; border-radius: 5px; }
    </style>
</head>
<body>

<div class="overlay">
    <div class="container">
        <h2>Lista de Temas del Saber</h2>

        <div class="filter-container">
            <a href="ver_temas.php" class="btn-filter <?php echo !$categoria_id ? 'active' : ''; ?>">Todos</a>
            <a href="ver_temas.php?cat=1" class="btn-filter <?php echo $categoria_id == 1 ? 'active' : ''; ?>">PHP</a>
            <a href="ver_temas.php?cat=2" class="btn-filter <?php echo $categoria_id == 2 ? 'active' : ''; ?>">HTML</a>
            <a href="ver_temas.php?cat=3" class="btn-filter <?php echo $categoria_id == 3 ? 'active' : ''; ?>">CSS</a>
        </div>

        <div class="search-container" style="text-align: center; margin-bottom: 20px;">
            <input type="text" id="buscador" placeholder="🔍 Buscar en esta categoría..." 
                   onkeyup="filtrarTemas()" 
                   style="width: 80%; padding: 12px; border-radius: 25px; border: 2px solid #f1c40f; background: rgba(0,0,0,0.6); color: white;">
        </div>

        <div class="header-actions" style="text-align: center; margin-bottom: 30px;">
            <a href="crear_tema.php" class="btn-crear">➕ Crear Nuevo Tema</a>
        </div>

        <?php if (count($temas) > 0): ?>
            <?php foreach ($temas as $tema): ?>
                <div class="tema-card">
                    <h3 style="color: #f1c40f; text-align: center;"><?php echo htmlspecialchars($tema['titulo']); ?></h3>
                    <pre><?php echo htmlspecialchars($tema['descripcion']); ?></pre>
                    <div style="text-align: center; color: #bdc3c7; font-size: 0.9em; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                        <strong>Categoría:</strong> <?php echo htmlspecialchars($tema['categoria']); ?> | 
                        <strong>Autor:</strong> <?php echo htmlspecialchars($tema['autor']); ?>
                    </div>
                    <div style="text-align: center; margin-top: 15px;">
                        <a href="editar_tema.php?id=<?php echo $tema['id']; ?>" style="color: #3498db; text-decoration: none; margin: 0 10px;">✍️ Editar</a>
                        <a href="eliminar_tema.php?id=<?php echo $tema['id']; ?>" class="delete" style="color: #e74c3c; text-decoration: none; margin: 0 10px;" onclick="return confirm('¿Eliminar?');">🗑️ Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; opacity: 0.6;">No hay temas en esta categoría todavía.</p>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="color: #f1c40f; text-decoration: none; font-weight: bold;">⬅ VOLVER AL OLIMPO</a>
        </div>
    </div>
</div>

<script>
    function filtrarTemas() {
        let filtro = document.getElementById('buscador').value.toLowerCase();
        let tarjetas = document.getElementsByClassName('tema-card');
        for (let i = 0; i < tarjetas.length; i++) {
            let texto = tarjetas[i].innerText.toLowerCase();
            tarjetas[i].style.display = texto.includes(filtro) ? "" : "none";
        }
    }
</script>

</body>
</html>