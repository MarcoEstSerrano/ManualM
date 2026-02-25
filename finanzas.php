<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['usuario_id'];

try {
    // 1. Obtener saldo de BAC
    $stmt_bac = $pdo->prepare("SELECT saldo FROM cuentas WHERE banco = 'BAC' AND usuario_id = ?");
    $stmt_bac->execute([$user_id]);
    $saldo_bac = $stmt_bac->fetchColumn() ?: 0;

    // 2. Obtener saldo de BCR
    $stmt_bcr = $pdo->prepare("SELECT saldo FROM cuentas WHERE banco = 'BCR' AND usuario_id = ?");
    $stmt_bcr->execute([$user_id]);
    $saldo_bcr = $stmt_bcr->fetchColumn() ?: 0;

    $total_capital = $saldo_bac + $saldo_bcr;

    // 3. Obtener últimos 10 movimientos
    $stmt_movs = $pdo->prepare("SELECT * FROM movimientos WHERE usuario_id = ? ORDER BY fecha DESC LIMIT 10");
    $stmt_movs->execute([$user_id]);
    $movimientos = $stmt_movs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Tesorería del Olimpo | Manual M</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .bg-olympus {
                background-image: url('image/olympus_tholos.png');
                background-size: cover;
                background-attachment: fixed;
            }
            .glass {
                background: rgba(0, 0, 0, 0.8);
                backdrop-filter: blur(10px);
                border: 1px solid #f1c40f;
            }
            #calc-modal {
                display: none;
            }
        </style>
    </head>
    <body class="bg-olympus min-h-screen text-white p-4">

        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8 glass p-6 rounded-2xl">
                <div>
                    <h1 class="text-3xl font-black text-[#f1c40f]">FINANZAS <span class="text-white">REALES</span></h1>
                    <p class="text-gray-400">Capital Total: <span class="text-green-400 font-bold">₡<?php echo number_format($total_capital); ?></span></p>
                </div>
                <div class="flex gap-4">
                    <button onclick="toggleCalc()" class="bg-blue-600 hover:bg-blue-500 p-3 rounded-full shadow-lg transition">🔢 Calculadora</button>
                    <a href="index.php" class="bg-gray-700 p-3 rounded-full">Volver</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass p-6 rounded-2xl h-fit">
                    <h2 class="text-[#f1c40f] font-bold mb-4 border-b border-yellow-900/50 pb-2">Bancos</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center bg-white/5 p-4 rounded-xl">
                            <span>🏦 BAC</span>
                            <span class="font-bold">₡<?php echo number_format($saldo_bac); ?></span>
                        </div>
                        <div class="flex justify-between items-center bg-white/5 p-4 rounded-xl">
                            <span>🏦 BCR</span>
                            <span class="font-bold">₡<?php echo number_format($saldo_bcr); ?></span>
                        </div>
                    </div>
                </div>

                <div class="glass p-6 rounded-2xl md:col-span-2">
                    <h2 class="text-[#f1c40f] font-bold mb-4 border-b border-yellow-900/50 pb-2">Nuevo Registro</h2>
                    <form action="procesar_finanzas.php" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <select name="tipo" class="bg-black/50 border border-gray-600 p-2 rounded text-white">
                            <option value="gasto">Tipo: Gasto</option>
                            <option value="ingreso">Tipo: Ingreso</option>
                            <option value="capricho">Tipo: Capricho</option>
                        </select>
                        <select name="banco" class="bg-black/50 border border-gray-600 p-2 rounded text-white">
                            <option value="BAC">Banco: BAC</option>
                            <option value="BCR">Banco: BCR</option>
                        </select>
                        <input type="number" name="monto" required placeholder="Monto" class="bg-black/50 border border-gray-600 p-2 rounded text-white">
                        <input type="text" name="descripcion" required placeholder="Descripción (ej. Comida)" class="bg-black/50 border border-gray-600 p-2 rounded text-white">
                        <button type="submit" class="sm:col-span-2 bg-green-600 hover:bg-green-500 py-3 rounded-xl font-bold transition">Registrar Movimiento</button>
                    </form>
                </div>
            </div>

            <div class="glass mt-8 p-6 rounded-2xl overflow-x-auto">
                <h2 class="text-[#f1c40f] font-bold mb-4">Últimos Movimientos</h2>
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-700">
                            <th class="p-3">Fecha</th>
                            <th>Descripción</th>
                            <th>Tipo</th> <th>Banco</th>
                            <th>Monto</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimientos as $row): ?>
                            <tr class="border-b border-gray-800 hover:bg-white/5">
                                <td class="p-3 text-sm"><?php echo date('d/m/y', strtotime($row['fecha'])); ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>

                                <td class="text-xs uppercase font-bold">
                                    <?php
                                    $tipo = $row['tipo'];
                                    $color_tipo = 'text-gray-400'; // Default
                                    if ($tipo == 'ingreso')
                                        $color_tipo = 'text-green-400';
                                    if ($tipo == 'gasto')
                                        $color_tipo = 'text-red-400';
                                    if ($tipo == 'capricho')
                                        $color_tipo = 'text-purple-400'; // El capricho resalta en púrpura
                                    ?>
                                    <span class="<?php echo $color_tipo; ?> bg-white/5 px-2 py-1 rounded">
    <?php echo $tipo; ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="<?php echo ($row['banco'] == 'BAC') ? 'text-red-400' : 'text-blue-400'; ?>">
                                    <?php echo $row['banco']; ?>
                                    </span>
                                </td>
                                <td class="<?php echo ($row['tipo'] == 'ingreso') ? 'text-green-400' : 'text-red-500'; ?> font-bold">
    <?php echo ($row['tipo'] == 'ingreso') ? '+' : '-'; ?> ₡<?php echo number_format($row['monto']); ?>
                                </td>
                                <td>
                                    <a href="eliminar_movimiento.php?id=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('¿Revertir este movimiento?')" 
                                       class="text-gray-500 hover:text-red-500">🗑️</a>
                                </td>
                            </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="calc-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="relative">
                <button onclick="toggleCalc()" class="absolute -top-12 right-0 text-white font-bold bg-red-600 px-4 py-1 rounded-full">CERRAR X</button>
                <iframe src="calculadora.php" class="w-[350px] h-[550px] rounded-3xl border-2 border-[#f1c40f]"></iframe>
            </div>
        </div>

        <script>
            function toggleCalc() {
                const modal = document.getElementById('calc-modal');
                modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
            }
        </script>
    </body>
</html>