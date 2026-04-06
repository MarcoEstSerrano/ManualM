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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CEC | Modificar Registro Técnico</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            :root {
                --ishimura-cyan: #00f2ff;
                --ishimura-orange: #ff8000;
            }

            body, html {
                height: 100%;
                margin: 0;
                font-family: 'Courier New', Courier, monospace;
                background-color: #050a0f;
                color: white;
                overflow: hidden;
            }

            .bg-ishimura {
                background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                    url('https://wallpaperaccess.com/full/1235791.jpg');
                height: 100%;
                background-position: center;
                background-size: cover;
                position: absolute;
                width: 100%;
                z-index: -1;
            }

            .overlay {
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: radial-gradient(circle, rgba(0,242,255,0.05) 0%, transparent 70%);
            }

            .card-holograma {
                background: rgba(0, 20, 30, 0.9);
                backdrop-filter: blur(15px);
                padding: 40px;
                border: 1px solid var(--ishimura-cyan);
                box-shadow: 0 0 30px rgba(0, 242, 255, 0.2);
                width: 95%;
                max-width: 600px;
                position: relative;
            }

            /* Línea decorativa superior estilo RIG */
            .card-holograma::after {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: var(--ishimura-cyan);
                box-shadow: 0 0 15px var(--ishimura-cyan);
            }

            h2 {
                color: white;
                text-transform: uppercase;
                letter-spacing: 4px;
                margin-bottom: 30px;
                font-weight: black;
                text-shadow: 0 0 10px var(--ishimura-cyan);
            }

            /* Input y Textarea con letra más legible */
            input, textarea, select {
                width: 100%;
                padding: 14px;
                margin-bottom: 20px;
                background: rgba(0, 0, 0, 0.6);
                border: 1px solid rgba(0, 242, 255, 0.3);
                color: var(--ishimura-cyan);
                font-size: 14px; /* Aumentado como pediste */
                outline: none;
                transition: all 0.3s;
            }

            input:focus, textarea:focus {
                border-color: var(--ishimura-cyan);
                box-shadow: 0 0 10px rgba(0, 242, 255, 0.3);
                background: rgba(0, 40, 50, 0.6);
            }

            textarea {
                resize: none;
                scrollbar-width: thin;
                scrollbar-color: var(--ishimura-cyan) #000;
            }

            label {
                display: block;
                text-align: left;
                font-size: 12px;
                text-transform: uppercase;
                color: #555;
                margin-bottom: 5px;
                letter-spacing: 1px;
            }

            button {
                width: 100%;
                padding: 16px;
                background-color: var(--ishimura-cyan);
                color: #000;
                border: none;
                font-weight: 900;
                text-transform: uppercase;
                cursor: pointer;
                transition: 0.4s;
                clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
            }

            button:hover {
                background-color: white;
                box-shadow: 0 0 25px var(--ishimura-cyan);
            }

            .link-volver {
                display: inline-block;
                margin-top: 25px;
                color: rgba(0, 242, 255, 0.5);
                text-decoration: none;
                font-size: 13px; /* Aumentado */
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .link-volver:hover {
                color: white;
            }

            .scanline {
                width: 100%;
                height: 2px;
                background: rgba(0, 242, 255, 0.1);
                position: absolute;
                animation: moveScanline 8s linear infinite;
            }

            @keyframes moveScanline {
                from {
                    top: 0;
                }
                to {
                    top: 100%;
                }
            }
        </style>
    </head>
    <body>

        <div class="bg-ishimura"></div>

        <div class="overlay">
            <div class="card-holograma">
                <div class="scanline"></div>
                <p class="text-[10px] text-cyan-800 text-left mb-2 tracking-[0.2em]">CEC_DATA_ENTRY // SECTOR_ENGINEERING</p>
                <h2>Reescribir Registro</h2>

                <form method="POST">
                    <label>Identificador del Tema</label>
                    <input type="text" name="titulo" value="<?php echo htmlspecialchars($tema['titulo']); ?>" placeholder="Título del archivo" required>

                    <label>Cuerpo del Documento Técnico</label>
                    <textarea name="descripcion" rows="6" required><?php echo htmlspecialchars($tema['descripcion']); ?></textarea>

                    <label>Módulo de Categorización</label>
                    <select name="categoria" required>
                        <option value="1" <?php echo ($tema['categoria_id'] == 1) ? 'selected' : ''; ?>>PROTOCOLO: PHP</option>
                        <option value="2" <?php echo ($tema['categoria_id'] == 2) ? 'selected' : ''; ?>>PROTOCOLO: HTML</option>
                        <option value="3" <?php echo ($tema['categoria_id'] == 3) ? 'selected' : ''; ?>>PROTOCOLO: CSS</option>
                    </select>

                    <button type="submit">Sincronizar Cambios</button>
                </form>

                <a href="ver_temas.php" class="link-volver">[ Regresar al Almacenamiento ]</a>
            </div>
        </div>

    </body>
</html>