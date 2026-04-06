<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
$user_id = $_SESSION['usuario_id'];

// Función para sacar la imagen de miniatura de YouTube
function get_yt_thumb($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $vars);
    $id = $vars['v'] ?? null;
    // Si no hay ID por query string, intentamos con el formato short de youtu.be
    if (!$id) {
        $path = parse_url($url, PHP_URL_PATH);
        $id = ltrim($path, '/');
    }
    return $id ? "https://img.youtube.com/vi/$id/maxresdefault.jpg" : "image/default_video.png";
}

// Obtener videos
$stmt = $pdo->prepare("SELECT * FROM multimedia WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
$stmt->execute([$user_id]);
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CEC | Video-Logs de Misión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --ishimura-cyan: #00f2ff;
            --ishimura-orange: #ff8000;
        }

        body { 
            background-color: #050a0f;
            background-image: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), 
                              url('https://wallpapercave.com/wp/wp2436440.jpg'); 
            background-size: cover; 
            background-attachment: fixed;
            font-family: 'Courier New', Courier, monospace;
        }

        .glass-panel { 
            background: rgba(0, 20, 30, 0.8); 
            backdrop-filter: blur(15px); 
            border: 1px solid var(--ishimura-cyan);
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.1);
        }

        .video-card {
            border: 1px solid rgba(0, 242, 255, 0.2);
            transition: all 0.4s ease;
            position: relative;
            background: black;
        }

        .video-card:hover {
            border-color: var(--ishimura-cyan);
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.3);
            transform: translateY(-5px);
        }

        .video-card img {
            filter: sepia(50%) hue-rotate(150deg) brightness(0.8);
            transition: 0.4s;
        }

        .video-card:hover img {
            filter: none;
            brightness: 1;
        }

        /* Efecto de Scanline sobre los videos */
        .video-card::after {
            content: "";
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 242, 255, 0.05) 50%), 
                        linear-gradient(90deg, rgba(255, 0, 0, 0.02), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.02));
            background-size: 100% 4px, 3px 100%;
            pointer-events: none;
        }

        .btn-cec {
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-light {
            width: 8px; height: 8px;
            background: var(--ishimura-cyan);
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 8px var(--ishimura-cyan);
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
    </style>
</head>
<body class="min-h-screen text-gray-200 p-4">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-8 glass-panel p-6 rounded-sm border-l-4 border-cyan-500">
            <div>
                <h1 class="text-3xl font-black text-white italic tracking-tighter">ARCHIVOS <span class="text-cyan-400">MULTIMEDIA</span></h1>
                <p class="text-[10px] text-cyan-700 tracking-[0.4em] uppercase">Base de Datos de Video-Logs USG Ishimura</p>
            </div>
            <a href="index.php" class="btn-cec bg-gray-800 px-8 py-2 border border-gray-600 text-[10px] hover:bg-gray-700 transition">Cerrar Terminal</a>
        </div>

        <div class="glass-panel p-6 rounded-sm mb-10 border-b-2 border-cyan-900">
            <h2 class="text-[10px] mb-4 text-cyan-500 tracking-widest uppercase">Cargar Nueva Transmisión</h2>
            <form action="procesar_multimedia.php" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <input type="text" name="titulo" required placeholder="IDENTIFICADOR DEL LOG..." 
                       class="bg-black/60 border border-cyan-900 p-3 text-white placeholder-cyan-900 font-bold focus:border-cyan-400 outline-none">
                <input type="url" name="url" required placeholder="SOURCE URL (YOUTUBE)..." 
                       class="bg-black/60 border border-cyan-900 p-3 text-white placeholder-cyan-900 font-bold focus:border-cyan-400 outline-none">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-black py-3 font-black btn-cec transition uppercase italic">Sincronizar</button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($videos as $v): ?>
                <div class="video-card rounded-sm overflow-hidden group">
                    <div class="relative">
                        <img src="<?php echo get_yt_thumb($v['url_youtube']); ?>" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-cyan-500/20">
                             <div class="text-black bg-white/80 p-2 font-black text-xs uppercase tracking-tighter">Play Log</div>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-gradient-to-b from-[#0a151a] to-black">
                        <div class="flex items-center mb-2">
                            <span class="status-light"></span>
                            <h3 class="font-bold text-sm tracking-tight text-white uppercase truncate"><?php echo htmlspecialchars($v['titulo']); ?></h3>
                        </div>
                        
                        <div class="flex justify-between items-center mt-6 border-t border-gray-800 pt-4">
                            <a href="<?php echo $v['url_youtube']; ?>" target="_blank" 
                               class="text-cyan-500 hover:text-white text-[10px] font-black uppercase tracking-widest transition"> [ Abrir Stream ] </a>
                            <a href="procesar_multimedia.php?id=<?php echo $v['id']; ?>&accion=eliminar" 
                               onclick="return confirm('¿PURGAR ESTA TRANSMISIÓN?')" 
                               class="text-gray-600 hover:text-orange-500 transition-colors text-xs uppercase"> Purgar </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($videos)): ?>
            <div class="text-center p-20 glass-panel border-dashed opacity-30">
                <p class="italic tracking-[0.5em] text-sm">[ NO SE DETECTAN SEÑALES DE VIDEO EN ESTE SECTOR ]</p>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>