<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
$user_id = $_SESSION['usuario_id'];

// Función para sacar la imagen de miniatura de YouTube
function get_yt_thumb($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $vars);
    $id = $vars['v'] ?? null;
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
    <title>Cámara Multimedia | Olimpo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-olympus { background-image: url('image/olympus_tholos.png'); background-size: cover; background-attachment: fixed; }
        .glass { background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(10px); border: 1px solid #f1c40f; }
        .video-card:hover .play-overlay { opacity: 1; }
    </style>
</head>
<body class="bg-olympus min-h-screen text-white p-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8 glass p-6 rounded-2xl">
            <h1 class="text-3xl font-black text-[#f1c40f]">BIBLIOTECA <span class="text-white">VISUAL</span></h1>
            <a href="index.php" class="bg-gray-700 px-6 py-2 rounded-full">Volver</a>
        </div>

        <div class="glass p-6 rounded-2xl mb-8">
            <form action="procesar_multimedia.php" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="titulo" required placeholder="Título del video" class="bg-black/50 border border-gray-600 p-3 rounded-xl text-white">
                <input type="url" name="url" required placeholder="Link de YouTube (https://www.youtube.com/watch?v=...)" class="bg-black/50 border border-gray-600 p-3 rounded-xl text-white">
                <button type="submit" class="bg-red-600 hover:bg-red-500 py-3 rounded-xl font-bold transition">Añadir Video</button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($videos as $v): ?>
                <div class="video-card glass rounded-2xl overflow-hidden relative group">
                    <img src="<?php echo get_yt_thumb($v['url_youtube']); ?>" class="w-full h-48 object-cover opacity-80 group-hover:opacity-100 transition">
                    <div class="p-4">
                        <h3 class="font-bold text-lg truncate"><?php echo htmlspecialchars($v['titulo']); ?></h3>
                        <div class="flex justify-between mt-4">
                            <a href="<?php echo $v['url_youtube']; ?>" target="_blank" class="text-blue-400 hover:underline text-sm italic">Ver en YouTube →</a>
                            <a href="procesar_multimedia.php?id=<?php echo $v['id']; ?>&accion=eliminar" onclick="return confirm('¿Quitar del Olimpo?')" class="text-gray-500 hover:text-red-500">🗑️</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>