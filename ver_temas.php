<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$categoria_id = isset($_GET['cat']) ? $_GET['cat'] : null;

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
        <title>CEC | Registros de Datos</title>
        <style>
            :root {
                --ishimura-cyan: #00f2ff;
                --ishimura-orange: #ff8000;
            }

            body {
                margin: 0;
                padding: 0;
                font-family: 'Courier New', Courier, monospace;
                background-color: #050a0f;
                background-image: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)),
                    url('https://i.imgur.com/kiW9cp7.jpg?1');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                color: var(--ishimura-cyan);
            }

            .overlay {
                min-height: 100vh;
                width: 100%;
                display: flex;
                justify-content: center;
                padding-top: 40px;
            }
            .container {
                width: 100%;
                max-width: 900px;
                padding: 20px;
            }

            h2 {
                text-align: center;
                color: white;
                text-transform: uppercase;
                letter-spacing: 5px;
                border-bottom: 1px solid var(--ishimura-cyan);
                padding-bottom: 15px;
                text-shadow: 0 0 10px var(--ishimura-cyan);
            }

            /* Filtros estilo terminal */
            .filter-container {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-bottom: 30px;
                flex-wrap: wrap;
            }

            .btn-filter {
                padding: 8px 15px;
                border: 1px solid var(--ishimura-cyan);
                background: rgba(0, 242, 255, 0.05);
                color: var(--ishimura-cyan);
                text-decoration: none;
                font-size: 0.8em;
                transition: 0.3s;
                text-transform: uppercase;
            }

            .btn-filter:hover, .btn-filter.active {
                background: var(--ishimura-cyan);
                color: black;
                box-shadow: 0 0 15px var(--ishimura-cyan);
            }

            /* Buscador */
            #buscador {
                width: 100%;
                padding: 15px;
                border: 1px solid rgba(0, 242, 255, 0.3);
                background: rgba(0,0,0,0.6);
                color: white;
                font-family: 'Courier New', Courier, monospace;
                margin-bottom: 30px;
                outline: none;
            }

            /* Tarjetas de registros (Logs) */
            .tema-card {
                background: rgba(0, 20, 30, 0.8);
                border-left: 4px solid var(--ishimura-cyan);
                padding: 25px;
                margin-bottom: 25px;
                backdrop-filter: blur(5px);
                position: relative;
                overflow: hidden;
            }

            .tema-card::after {
                content: "DECRYPTED DATA";
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 8px;
                opacity: 0.3;
                letter-spacing: 2px;
            }

            h3 {
                margin-top: 0;
                color: white;
                text-transform: uppercase;
            }

            pre {
                background: rgba(0, 0, 0, 0.4);
                padding: 15px;
                font-size: 0.9em;
                color: #bdc3c7;
                border: 1px solid rgba(0, 242, 255, 0.1);
                white-space: pre-wrap;
                line-height: 1.5;
            }

            .meta-data {
                font-size: 0.7em;
                color: var(--ishimura-cyan);
                opacity: 0.6;
                margin-top: 15px;
                text-transform: uppercase;
                border-top: 1px solid rgba(0, 242, 255, 0.1);
                padding-top: 10px;
            }

            .btn-crear {
                background: var(--ishimura-orange);
                color: black;
                padding: 12px 25px;
                text-decoration: none;
                font-weight: bold;
                text-transform: uppercase;
                display: inline-block;
                clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
                margin-bottom: 30px;
            }

            .actions a {
                font-size: 0.8em;
                text-transform: uppercase;
                font-weight: bold;
                margin-right: 15px;
            }
        </style>
    </head>
    <body>

        <div class="overlay">
            <div class="container">
                <p style="text-align: center; font-size: 10px; opacity: 0.5;">ISHIMURA DATA REPOSITORY // SECURE LINK ESTABLISHED</p>
                <h2>REGISTROS DE INGENIERÍA</h2>

                <div class="filter-container">
                    <a href="ver_temas.php" class="btn-filter <?php echo!$categoria_id ? 'active' : ''; ?>">Todos</a>
                    <a href="ver_temas.php?cat=1" class="btn-filter <?php echo $categoria_id == 1 ? 'active' : ''; ?>">Protocolos PHP</a>
                    <a href="ver_temas.php?cat=2" class="btn-filter <?php echo $categoria_id == 2 ? 'active' : ''; ?>">Estructuras HTML</a>
                    <a href="ver_temas.php?cat=3" class="btn-filter <?php echo $categoria_id == 3 ? 'active' : ''; ?>">Estilos CSS</a>
                    <a href="index.php" style="color: white; text-decoration: none; font-size: 0.8em; letter-spacing: 2px;">⬅ VOLVER AL PANEL DE CONTROL</a>
                </div>

                <input type="text" id="buscador" placeholder="[🔍] BUSCAR EN LA BITÁCORA..." onkeyup="filtrarTemas()">

                <div style="text-align: center;">
                    <a href="crear_tema.php" class="btn-crear">NUEVA ENTRADA DE DATOS</a>
                </div>

                <?php if (count($temas) > 0): ?>
                    <?php foreach ($temas as $tema): ?>
                        <div class="tema-card">
                            <h3><?php echo htmlspecialchars($tema['titulo']); ?></h3>
                            <pre><?php echo htmlspecialchars($tema['descripcion']); ?></pre>

                            <div class="meta-data">
                                CATEGORÍA: <?php echo htmlspecialchars($tema['categoria']); ?> | 
                                ORIGEN: <?php echo htmlspecialchars($tema['autor']); ?>
                            </div>

                            <div class="actions" style="margin-top: 15px;">
                                <a href="editar_tema.php?id=<?php echo $tema['id']; ?>" style="color: #3498db;">[Modificar]</a>
                                <a href="eliminar_tema.php?id=<?php echo $tema['id']; ?>" style="color: var(--ishimura-orange);" onclick="return confirm('¿PURGAR REGISTRO?');">[Purgar]</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; opacity: 0.4;">ERROR: No se han encontrado registros en este sector.</p>
                <?php endif; ?>

                <div style="text-align: center; margin-top: 40px; padding-bottom: 50px;">
                    <a href="index.php" style="color: white; text-decoration: none; font-size: 0.8em; letter-spacing: 2px;">⬅ VOLVER AL PANEL DE CONTROL</a>
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