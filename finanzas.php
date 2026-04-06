<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['usuario_id'];

try {
    $stmt_bac = $pdo->prepare("SELECT saldo FROM cuentas WHERE banco = 'BAC' AND usuario_id = ?");
    $stmt_bac->execute([$user_id]);
    $saldo_bac = $stmt_bac->fetchColumn() ?: 0;

    $stmt_bcr = $pdo->prepare("SELECT saldo FROM cuentas WHERE banco = 'BCR' AND usuario_id = ?");
    $stmt_bcr->execute([$user_id]);
    $saldo_bcr = $stmt_bcr->fetchColumn() ?: 0;

    $total_capital = $saldo_bac + $saldo_bcr;

    $stmt_movs = $pdo->prepare("SELECT * FROM movimientos WHERE usuario_id = ? ORDER BY fecha DESC LIMIT 10");
    $stmt_movs->execute([$user_id]);
    $movimientos = $stmt_movs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la terminal de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CEC | Gestión de Créditos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --ishimura-cyan: #00f2ff;
            --ishimura-orange: #ff8000;
        }
        body {
            background-color: #050a0f;
            background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), 
                              url('https://w0.peakpx.com/wallpaper/189/127/HD-wallpaper-dead-space-dead-space-remake-isaac-clarke.jpg');
            background-size: cover;
            background-attachment: fixed;
            font-family: 'Courier New', Courier, monospace;
        }
        .hologram-panel {
            background: rgba(0, 40, 50, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid var(--ishimura-cyan);
            box-shadow: 0 0 15px rgba(0, 242, 255, 0.1);
        }
        .scanline {
            width: 100%; height: 2px;
            background: rgba(0, 242, 255, 0.2);
            position: absolute;
            animation: moveScan 8s linear infinite;
            pointer-events: none;
        }
        @keyframes moveScan {
            from { top: 0; } to { top: 100%; }
        }
        .btn-cec {
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
            transition: all 0.3s;
        }
        .btn-cec:hover {
            filter: brightness(1.2);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="text-gray-200 p-4">

    <div class="max-w-6xl mx-auto relative">
        <div class="scanline"></div>

        <div class="flex justify-between items-center mb-8 hologram-panel p-6 rounded-sm border-l-4">
            <div>
                <h1 class="text-3xl font-black text-cyan-400 tracking-tighter italic">CRÉDITOS <span class="text-white">CEC</span></h1>
                <p class="text-[10px] text-cyan-700 tracking-widest uppercase">Estatus Financiero del RIG</p>
                <p class="mt-2 text-xl">Balance Total: <span class="text-green-400 font-bold">₡<?php echo number_format($total_capital); ?></span></p>
            </div>
            <div class="flex gap-4">
                <button onclick="toggleCalc()" class="btn-cec bg-cyan-600 text-black font-bold px-6 py-2 shadow-lg">CALCULADORA</button>
                <a href="index.php" class="btn-cec bg-gray-800 px-6 py-2 border border-gray-600">VOLVER</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="hologram-panel p-6 rounded-sm border-t-2 border-cyan-500">
                <h2 class="text-cyan-400 font-bold mb-4 uppercase tracking-widest text-sm border-b border-cyan-900 pb-2 italic">Nodos de Almacenamiento</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-black/40 p-4 border-l-2 border-red-600">
                        <span class="text-xs">RED BAC</span>
                        <span class="font-bold text-cyan-300">₡<?php echo number_format($saldo_bac); ?></span>
                    </div>
                    <div class="flex justify-between items-center bg-black/40 p-4 border-l-2 border-blue-600">
                        <span class="text-xs">RED BCR</span>
                        <span class="font-bold text-cyan-300">₡<?php echo number_format($saldo_bcr); ?></span>
                    </div>
                </div>
            </div>

            <div class="hologram-panel p-6 rounded-sm md:col-span-2 border-t-2 border-orange-500">
                <h2 class="text-orange-500 font-bold mb-4 uppercase tracking-widest text-sm border-b border-orange-900 pb-2 italic">Nuevo Registro de Transacción</h2>
                <form action="procesar_finanzas.php" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <select name="tipo" class="bg-black/60 border border-orange-900/50 p-2 text-orange-500 text-xs focus:border-orange-500 outline-none">
                        <option value="gasto">TRANSACCIÓN: GASTO</option>
                        <option value="ingreso">TRANSACCIÓN: INGRESO</option>
                        <option value="capricho">TRANSACCIÓN: CAPRICHO</option>
                    </select>
                    <select name="banco" class="bg-black/60 border border-orange-900/50 p-2 text-orange-500 text-xs focus:border-orange-500 outline-none">
                        <option value="BAC">NODO: BAC</option>
                        <option value="BCR">NODO: BCR</option>
                    </select>
                    <input type="number" name="monto" required placeholder="CANTIDAD" class="bg-black/60 border border-orange-900/50 p-2 text-white placeholder-orange-900 text-xs outline-none">
                    <input type="text" name="descripcion" required placeholder="DETALLES DEL SUMINISTRO" class="bg-black/60 border border-orange-900/50 p-2 text-white placeholder-orange-900 text-xs outline-none">
                    <button type="submit" class="sm:col-span-2 bg-orange-600 hover:bg-orange-500 text-black py-3 font-bold uppercase tracking-widest text-xs btn-cec">Ejecutar Transferencia</button>
                </form>
            </div>
        </div>

        <div class="hologram-panel mt-8 p-6 rounded-sm overflow-x-auto border-b-4 border-cyan-900">
            <h2 class="text-cyan-400 font-bold mb-4 uppercase text-xs italic tracking-widest">Historial de Registros Ishimura</h2>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-cyan-800 border-b border-cyan-900 text-[10px] uppercase">
                        <th class="p-3">Sincronización</th>
                        <th>Descripción</th>
                        <th>Protocolo</th> 
                        <th>Nodo</th>
                        <th>Créditos</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    <?php foreach ($movimientos as $row): ?>
                        <tr class="border-b border-cyan-900/30 hover:bg-cyan-400/5 transition-colors">
                            <td class="p-3 opacity-60"><?php echo date('d/m/y', strtotime($row['fecha'])); ?></td>
                            <td class="uppercase"><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td>
                                <?php
                                $tipo = $row['tipo'];
                                $color = ($tipo == 'ingreso') ? 'text-cyan-400' : (($tipo == 'gasto') ? 'text-orange-500' : 'text-purple-400');
                                ?>
                                <span class="<?php echo $color; ?> border border-current px-2 py-0.5 text-[9px] font-bold">
                                    <?php echo strtoupper($tipo); ?>
                                </span>
                            </td>
                            <td>
                                <span class="opacity-70"><?php echo $row['banco']; ?></span>
                            </td>
                            <td class="<?php echo ($row['tipo'] == 'ingreso') ? 'text-cyan-400' : 'text-orange-600'; ?> font-bold">
                                <?php echo ($row['tipo'] == 'ingreso') ? '+' : '-'; ?> ₡<?php echo number_format($row['monto']); ?>
                            </td>
                            <td>
                                <a href="eliminar_movimiento.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('¿Anular registro de datos?')" 
                                   class="text-gray-600 hover:text-red-500"> [X] </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="calc-modal" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 hidden">
        <div class="relative">
            <button onclick="toggleCalc()" class="absolute -top-12 right-0 text-cyan-400 font-bold hover:text-white"> TERMINAR SESIÓN [X] </button>
            <iframe src="calculadora.php" class="w-[350px] h-[550px] border-2 border-cyan-400 shadow-[0_0_30px_rgba(0,242,255,0.3)]"></iframe>
        </div>
    </div>

    <script>
        function toggleCalc() {
            const modal = document.getElementById('calc-modal');
            modal.classList.toggle('hidden');
        }
    </script>
</body>
</html>