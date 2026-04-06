<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Operaciones | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --ishimura-cyan: #00f2ff;
            --ishimura-orange: #ff8000;
        }

        body {
            background-color: #020205;
            /* Nueva imagen de fondo: USG Ishimura en órbita */
            background-image: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('https://i.redd.it/uur0s135yxc71.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .hologram-card {
            background: rgba(0, 242, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 242, 255, 0.2);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .hologram-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 2px;
            background: var(--ishimura-cyan);
            opacity: 0.3;
            animation: scanline 4s linear infinite;
        }

        @keyframes scanline {
            0% { top: 0%; }
            100% { top: 100%; }
        }

        .hologram-card:hover {
            background: rgba(0, 242, 255, 0.1);
            border-color: var(--ishimura-cyan);
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.4);
            transform: scale(1.02);
        }

        .text-hologram {
            color: var(--ishimura-cyan);
            text-shadow: 0 0 8px rgba(0, 242, 255, 0.8);
        }

        .cec-header {
            background: rgba(0, 0, 0, 0.9);
            border-bottom: 3px solid var(--ishimura-cyan);
            clip-path: polygon(0 0, 100% 0, 100% 80%, 95% 100%, 5% 100%, 0 80%);
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: var(--ishimura-cyan); }
    </style>
</head>
<body class="min-h-screen text-gray-200 font-mono">

    <div class="min-h-screen bg-black/40 flex flex-col">

        <header class="p-8 cec-header text-center mb-6">
            <div class="max-w-4xl mx-auto flex justify-between items-center">
                <div class="text-left">
                    <p class="text-xs tracking-[0.3em] text-cyan-500 uppercase">Makyleaf Corp.</p>
                    <h2 class="text-2xl font-bold text-hologram uppercase italic">
                        <?php echo "INGENIERO: " . htmlspecialchars($_SESSION['nombre'] ?? 'MARCO ESTEBAN'); ?> 
                    </h2>
                </div>
                <div class="text-right">
                    <p class="text-xs text-cyan-800">ESTADO DE RED: <span class="animate-pulse text-green-500 font-bold">CONECTADO</span></p>
                    <p class="text-xs text-cyan-800 uppercase">UBICACIÓN: Guayabo de Mora, CR</p>
                </div>
            </div>
        </header>

        <main class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h1 class="text-5xl font-black tracking-tighter text-white uppercase">SISTEMA DE <span class="text-hologram">OPERACIONES</span></h1>
                    <p class="text-cyan-600 text-sm mt-2 uppercase tracking-widest">Protocolos de Ingeniería Nivel 3</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <a href="ver_temas.php" class="hologram-card group p-6 rounded-sm text-center flex flex-col items-center">
                        <div class="text-cyan-400 text-4xl mb-4 group-hover:rotate-12 transition-all">📂</div>
                        <h3 class="text-lg font-bold text-hologram italic">BASE DE DATOS</h3>
                        <p class="text-sm text-cyan-700 mt-2">Registros y esquemas técnicos de la nave.</p>
                    </a>

                    <a href="calendario.php" class="hologram-card group p-6 rounded-sm text-center flex flex-col items-center">
                        <div class="text-cyan-400 text-4xl mb-4 group-hover:scale-110 transition-all">🕒</div>
                        <h3 class="text-lg font-bold text-hologram italic">CRONOGRAMA</h3>
                        <p class="text-sm text-cyan-700 mt-2">Ciclos de rotación y eventos estelares.</p>
                    </a>

                    <a href="finanzas.php" class="hologram-card group p-6 rounded-sm text-center flex flex-col items-center" style="border-color: rgba(255, 128, 0, 0.3);">
                        <div class="text-orange-500 text-4xl mb-4">💳</div>
                        <h3 class="text-lg font-bold text-orange-500 italic uppercase">Créditos</h3>
                        <p class="text-sm text-orange-800 mt-2">Balance de suministros y transacciones.</p>
                    </a>

                    <a href="tareas.php" class="hologram-card group p-6 rounded-sm text-center flex flex-col items-center">
                        <div class="text-cyan-400 text-4xl mb-4">🛠️</div>
                        <h3 class="text-lg font-bold text-hologram italic">MANTENIMIENTO</h3>
                        <p class="text-sm text-cyan-700 mt-2">Protocolos de reparación pendientes.</p>
                    </a>

                    <a href="musica.php" class="hologram-card group p-6 rounded-sm text-center flex flex-col items-center">
                        <div class="text-cyan-400 text-4xl mb-4">🔊</div>
                        <h3 class="text-lg font-bold text-hologram italic">AUDIO-LOGS</h3>
                        <p class="text-sm text-cyan-700 mt-2">Biblioteca de transmisiones recuperadas.</p>
                    </a>

                    <a href="multimedia.php" class="hologram-card group p-6 rounded-sm text-center flex flex-col items-center">
                        <div class="text-cyan-400 text-4xl mb-4">📷</div>
                        <h3 class="text-lg font-bold text-hologram italic">VIDEO-LOGS</h3>
                        <p class="text-sm text-cyan-700 mt-2">Archivos de video y cámaras de seguridad.</p>
                    </a>

                </div>

                <div class="mt-16 text-center">
                    <a href="logout.php" class="inline-block px-10 py-2 border-2 border-red-900 bg-red-950/20 hover:bg-red-600 text-red-500 hover:text-white font-bold uppercase tracking-widest transition-all italic">
                        Desconectar Terminal
                    </a>
                </div>
            </div>
        </main>

        <footer class="p-6 bg-black text-center text-xs text-cyan-900 border-t border-cyan-900/30">
            <p>ING. MARCO SERRANO | UNIDAD DE RESPUESTA TÉCNICA | © 2026 - MAKE US WHOLE</p>
        </footer>
    </div>

</body>
</html>