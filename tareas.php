<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['usuario_id'];

// Obtener tareas del usuario
$stmt = $pdo->prepare("SELECT * FROM tareas WHERE usuario_id = ? ORDER BY estado DESC, fecha_creacion DESC");
$stmt->execute([$user_id]);
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tareas | Manual M</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-olympus { background-image: url('image/olympus_tholos.png'); background-size: cover; background-attachment: fixed; }
        .glass { background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(10px); border: 1px solid #f1c40f; }
    </style>
</head>
<body class="bg-olympus min-h-screen text-white p-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8 glass p-6 rounded-2xl">
            <h1 class="text-3xl font-black text-[#f1c40f]">PENDIENTES <span class="text-white">DEL OLIMPO</span></h1>
            <a href="index.php" class="bg-gray-700 px-4 py-2 rounded-full">Volver</a>
        </div>

        <div class="glass p-6 rounded-2xl mb-8">
            <form action="procesar_tareas.php" method="POST" class="flex flex-wrap gap-4">
                <input type="text" name="titulo" required placeholder="¿Qué hay que hacer?" 
                       class="flex-1 bg-black/50 border border-gray-600 p-3 rounded-xl text-white">
                <select name="prioridad" class="bg-black/50 border border-gray-600 p-3 rounded-xl text-white">
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
                <button type="submit" name="accion" value="crear" 
                        class="bg-green-600 hover:bg-green-500 px-6 py-3 rounded-xl font-bold transition">Añadir</button>
            </form>
        </div>

        <div class="space-y-4">
            <?php foreach ($tareas as $t): ?>
                <div class="glass p-4 rounded-xl flex justify-between items-center <?php echo $t['estado'] == 'Completada' ? 'opacity-50' : ''; ?>">
                    <div class="flex items-center gap-4">
                        <a href="procesar_tareas.php?id=<?php echo $t['id']; ?>&accion=completar" 
                           class="w-6 h-6 border-2 border-[#f1c40f] rounded flex items-center justify-center">
                            <?php echo $t['estado'] == 'Completada' ? '✓' : ''; ?>
                        </a>
                        <div>
                            <p class="<?php echo $t['estado'] == 'Completada' ? 'line-through' : ''; ?> font-bold">
                                <?php echo htmlspecialchars($t['titulo']); ?>
                            </p>
                            <span class="text-xs <?php echo $t['prioridad'] == 'Alta' ? 'text-red-400' : ($t['prioridad'] == 'Media' ? 'text-yellow-400' : 'text-blue-400'); ?>">
                                ● Prioridad <?php echo $t['prioridad']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="procesar_tareas.php?id=<?php echo $t['id']; ?>&accion=eliminar" 
                           onclick="return confirm('¿Eliminar esta tarea?')"
                           class="text-gray-500 hover:text-red-500 text-xl">🗑️</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>