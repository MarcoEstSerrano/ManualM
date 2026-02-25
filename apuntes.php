<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['usuario_id'];

// Obtener apuntes
$stmt = $pdo->prepare("SELECT * FROM apuntes WHERE usuario_id = ? ORDER BY fecha_actualizacion DESC");
$stmt->execute([$user_id]);
$apuntes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Apuntes del Olimpo | Manual M</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-olympus { background-image: url('image/olympus_tholos.png'); background-size: cover; background-attachment: fixed; }
        .glass { background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(10px); border: 1px solid #f1c40f; }
        .note-card { transition: all 0.3s ease; border: 1px solid rgba(241, 196, 15, 0.2); }
        .note-card:hover { transform: scale(1.02); border-color: #f1c40f; }
    </style>
</head>
<body class="bg-olympus min-h-screen text-white p-4">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-8 glass p-6 rounded-2xl">
            <h1 class="text-3xl font-black text-[#f1c40f]">MIS <span class="text-white">APUNTES</span></h1>
            <div class="flex gap-4">
                <button onclick="abrirModal()" class="bg-yellow-600 hover:bg-yellow-500 px-6 py-2 rounded-full font-bold transition">+ Nueva Nota</button>
                <a href="index.php" class="bg-gray-700 px-6 py-2 rounded-full">Volver</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($apuntes as $a): ?>
                <div class="note-card glass p-6 rounded-2xl flex flex-col justify-between">
                    <div>
                        <h3 class="text-[#f1c40f] font-bold text-xl mb-3 border-b border-yellow-900/30 pb-2">
                            <?php echo htmlspecialchars($a['titulo']); ?>
                        </h3>
                        <p class="text-gray-300 text-sm whitespace-pre-wrap mb-4">
                            <?php echo htmlspecialchars($a['contenido']); ?>
                        </p>
                    </div>
                    <div class="flex justify-between items-center mt-4 border-t border-gray-700 pt-3">
                        <span class="text-[10px] text-gray-500 italic">
                            Editado: <?php echo date('d/m/y H:i', strtotime($a['fecha_actualizacion'])); ?>
                        </span>
                        <div class="flex gap-2">
                             <a href="procesar_apuntes.php?id=<?php echo $a['id']; ?>&accion=eliminar" 
                                onclick="return confirm('¿Borrar este apunte para siempre?')"
                                class="text-gray-500 hover:text-red-500">🗑️</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="modal-nota" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4">
        <div class="glass p-8 rounded-3xl w-full max-w-lg">
            <h2 class="text-2xl font-bold text-[#f1c40f] mb-6">Nuevo Apunte</h2>
            <form action="procesar_apuntes.php" method="POST" class="space-y-4">
                <input type="hidden" name="accion" value="crear">
                <input type="text" name="titulo" required placeholder="Título del apunte" 
                       class="w-full bg-black/50 border border-gray-600 p-3 rounded-xl text-white">
                <textarea name="contenido" required placeholder="Escribe tu sabiduría aquí..." rows="6"
                          class="w-full bg-black/50 border border-gray-600 p-3 rounded-xl text-white"></textarea>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-green-600 py-3 rounded-xl font-bold">Guardar</button>
                    <button type="button" onclick="cerrarModal()" class="flex-1 bg-red-900/50 py-3 rounded-xl font-bold">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal() { document.getElementById('modal-nota').style.display = 'flex'; }
        function cerrarModal() { document.getElementById('modal-nota').style.display = 'none'; }
    </script>
</body>
</html>