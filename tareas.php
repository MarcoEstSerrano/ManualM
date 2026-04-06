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
    <title>CEC | Objetivos de Misión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --ishimura-cyan: #00f2ff;
            --ishimura-orange: #ff8000;
            --ishimura-red: #ff3333;
        }

        body { 
            background-color: #050a0f;
            background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), 
                              url('https://images.alphacoders.com/112/1127331.jpg'); 
            background-size: cover; 
            background-attachment: fixed;
            font-family: 'Courier New', Courier, monospace;
        }

        .hologram-card { 
            background: rgba(0, 30, 40, 0.75); 
            backdrop-filter: blur(12px); 
            border: 1px solid var(--ishimura-cyan);
            box-shadow: 0 0 15px rgba(0, 242, 255, 0.1);
        }

        .task-item {
            border-left: 4px solid var(--ishimura-cyan);
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.03);
        }

        .task-item:hover {
            background: rgba(0, 242, 255, 0.08);
            transform: translateX(5px);
        }

        .completed {
            opacity: 0.4;
            border-left-color: #4a5568;
            filter: grayscale(1);
        }

        .priority-Alta { border-left-color: var(--ishimura-red); text-shadow: 0 0 5px rgba(255, 51, 51, 0.5); }
        .priority-Media { border-left-color: var(--ishimura-orange); }
        .priority-Baja { border-left-color: var(--ishimura-cyan); }

        .btn-cec {
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
        }

        input, select {
            outline: none !important;
            border-color: rgba(0, 242, 255, 0.3) !important;
        }

        input:focus {
            border-color: var(--ishimura-cyan) !important;
            box-shadow: 0 0 10px rgba(0, 242, 255, 0.2);
        }
    </style>
</head>
<body class="min-h-screen text-gray-200 p-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8 hologram-card p-6 rounded-sm border-b-2">
            <div>
                <h1 class="text-3xl font-black text-cyan-400 italic tracking-tighter">OBJETIVOS <span class="text-white font-light text-xl">ACTUALES</span></h1>
                <p class="text-[10px] text-cyan-800 tracking-[0.3em] uppercase">Módulo de Prioridades USG Ishimura</p>
            </div>
            <a href="index.php" class="btn-cec bg-gray-800 px-6 py-2 border border-gray-600 text-xs hover:bg-gray-700 transition">VOLVER</a>
        </div>

        <div class="hologram-card p-6 rounded-sm mb-8 border-t-2 border-orange-500">
            <form action="procesar_tareas.php" method="POST" class="flex flex-wrap gap-4">
                <input type="text" name="titulo" required placeholder="NUEVO PROTOCOLO..." 
                       class="flex-1 bg-black/60 border p-3 text-white placeholder-cyan-900 font-bold">
                <select name="prioridad" class="bg-black/60 border p-3 text-cyan-400 font-bold">
                    <option value="Baja">PRIORIDAD: BAJA</option>
                    <option value="Media">PRIORIDAD: MEDIA</option>
                    <option value="Alta">PRIORIDAD: CRÍTICA</option>
                </select>
                <button type="submit" name="accion" value="crear" 
                        class="bg-cyan-600 hover:bg-cyan-500 text-black px-8 py-3 font-black btn-cec transition uppercase italic">Asignar</button>
            </form>
        </div>

        <div class="space-y-4">
            <?php foreach ($tareas as $t): ?>
                <div class="hologram-card p-5 rounded-sm flex justify-between items-center task-item priority-<?php echo $t['prioridad']; ?> <?php echo $t['estado'] == 'Completada' ? 'completed' : ''; ?>">
                    <div class="flex items-center gap-6">
                        <a href="procesar_tareas.php?id=<?php echo $t['id']; ?>&accion=completar" 
                           class="w-8 h-8 border border-cyan-500 flex items-center justify-center transition hover:bg-cyan-500/20">
                            <?php if ($t['estado'] == 'Completada'): ?>
                                <div class="w-4 h-4 bg-cyan-400 shadow-[0_0_8px_#00f2ff]"></div>
                            <?php endif; ?>
                        </a>
                        
                        <div>
                            <p class="<?php echo $t['estado'] == 'Completada' ? 'line-through opacity-50' : 'text-white'; ?> font-bold tracking-wide uppercase text-sm">
                                <?php echo htmlspecialchars($t['titulo']); ?>
                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[9px] px-2 py-0.5 border border-current uppercase">
                                    <?php echo $t['prioridad'] == 'Alta' ? 'Estatus Crítico' : 'Estatus Estable'; ?>
                                </span>
                                <span class="text-[9px] text-gray-500">ID_REF: #00<?php echo $t['id']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <a href="procesar_tareas.php?id=<?php echo $t['id']; ?>&accion=eliminar" 
                           onclick="return confirm('¿ABORTAR OBJETIVO?')"
                           class="text-gray-600 hover:text-red-500 transition-colors font-bold text-xs uppercase"> [ Purgar ] </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($tareas)): ?>
            <div class="text-center p-10 opacity-30">
                <p class="italic tracking-widest">[ NO HAY OBJETIVOS PENDIENTES EN ESTE SECTOR ]</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>