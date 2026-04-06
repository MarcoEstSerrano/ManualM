<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
$user_id = $_SESSION['usuario_id'];

// Función para sacar la imagen de miniatura de YouTube
function get_yt_thumb($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $vars);
    $id = $vars['v'] ?? null;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEC | Video-Logs de Misión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --ishimura-cyan: #00f2ff;
            --ishimura-orange: #ff8000;
        }

        body { 
            background-color: #050a0f;
            /* Nueva imagen de fondo con Isaac Clarke */
            background-image: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)), 
                              url('https://i.imgur.com/kiW9cp7.jpg?1'); 
            background-size: cover; 
            background-position: center;
            background-attachment: fixed;
            font-family: 'Courier New', Courier, monospace;
        }

        .glass-panel { 
            background: rgba(0, 15, 25, 0.85); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(0, 242, 255, 0.3);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }

        .video-card {
            border: 1px solid rgba(0, 242, 255, 0.15);
            transition: all 0.4s ease;
            position: relative;
            background: rgba(0,0,0,0.8);
        }

        .video-card:hover {
            border-color: var(--ishimura-cyan);
            box-shadow: 0 0 25px rgba(0, 242, 255, 0.25);
            transform: translateY(-5px);
        }

        /* Ajuste de filtros para las miniaturas */
        .video-card img {
            filter: grayscale(40%) brightness(0.7) contrast(1.2);
            transition: 0.4s;
        }

        .video-card:hover img {
            filter: none;
            brightness: 1;
        }

        .video-card::after {
            content: "";
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 242, 255, 0.03) 50%);
            background-size: 100% 4px;
            pointer-events: none;
        }

        .btn-cec {
            clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 900;
        }

        .status-light {
            width: 10px; height: 10px;
            background: var(--ishimura-cyan);
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            box-shadow: 0 0 10px var(--ishimura-cyan);
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; box-shadow: 0 0 10px var(--ishimura-cyan); }
            50% { opacity: 0.3; box-shadow: 0 0 2px var(--ishimura-cyan); }
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--ishimura-cyan); }
    </style>
</head>
<body class="min-h-screen text-gray-200 p-6">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-8 glass-panel p-6 border-l-8 border-cyan-500">
            <div>
                <h1 class="text-3xl font-black text-white italic tracking-tighter uppercase">ARCHIVOS <span class="text-cyan-400">MULTIMEDIA</span></h1>
                <p class="text-[11px] text-cyan-600 tracking-[0.5em] uppercase font-bold mt-1">SISTEMA VISUAL // CEC_LOG_STORAGE</p>
            </div>
            <a href="index.php" class="btn-cec bg-cyan-900/30 px-8 py-2 border border-cyan-500 text-[11px] text-cyan-400 hover:bg-cyan-500 hover:text-black transition-all">
                Cerrar Terminal
            </a>
        </div>

        <div class="glass-panel p-8 rounded-sm mb-12 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-2 text-[10px] text-cyan-900 font-bold">RE-04_SYNC</div>
            <h2 class="text-[12px] mb-5 text-cyan-400 tracking-widest uppercase font-black">Sincronizar Nueva Transmisión</h2>
            <form action="procesar_multimedia.php" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <input type="text" name="titulo" required placeholder="IDENTIFICADOR DEL LOG..." 
                       class="bg-black/80 border border-cyan-900/50 p-4 text-sm text-white placeholder-cyan-900 focus:border-cyan-400 outline-none transition-all">
                <input type="url" name="url" required placeholder="SOURCE URL (YOUTUBE)..." 
                       class="bg-black/80 border border-cyan-900/50 p-4 text-sm text-white placeholder-cyan-900 focus:border-cyan-400 outline-none transition-all">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-400 text-black py-4 btn-cec transition-all italic">Sincronizar Datos</button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($videos as $v): ?>
                <div class="video-card overflow-hidden group">
                    <div class="relative">
                        <img src="<?php echo get_yt_thumb($v['url_youtube']); ?>" class="w-full h-52 object-cover border-b border-cyan-900/30">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-cyan-500/10 backdrop-blur-[2px]">
                             <div class="text-black bg-cyan-400 px-4 py-1 font-black text-[10px] uppercase tracking-widest shadow-lg">Play Log</div>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-gradient-to-b from-[#051015] to-black">
                        <div class="flex items-center mb-4">
                            <span class="status-light"></span>
                            <h3 class="font-bold text-[13px] tracking-tight text-cyan-50 uppercase truncate"><?php echo htmlspecialchars($v['titulo']); ?></h3>
                        </div>
                        
                        <div class="flex justify-between items-center mt-6 border-t border-cyan-900/20 pt-4">
                            <a href="<?php echo $v['url_youtube']; ?>" target="_blank" 
                               class="text-cyan-500 hover:text-white text-[11px] font-black uppercase tracking-[0.2em] transition"> [ Abrir Stream ] </a>
                            <a href="procesar_multimedia.php?id=<?php echo $v['id']; ?>&accion=eliminar" 
                               onclick="return confirm('¿PURGAR ESTA TRANSMISIÓN?')" 
                               class="text-gray-700 hover:text-orange-600 transition-colors text-[10px] uppercase font-bold"> Purgar </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($videos)): ?>
            <div class="text-center p-24 glass-panel border-dashed border-cyan-900/50">
                <p class="italic tracking-[0.6em] text-cyan-800 text-xs uppercase font-bold">[ No se detectan señales de video ]</p>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>