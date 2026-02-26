<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Seguridad: evita que entren sin login
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
        <title>Cámara del Tholos | Dashboard</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .bg-olympus {
                background-image: url('https://images6.alphacoders.com/795/795828.jpg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
            .glass-card {
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(241, 196, 15, 0.3);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }
            .glass-card:hover {
                transform: translateY(-10px);
                border-color: #f1c40f;
                box-shadow: 0 10px 30px -5px rgba(241, 196, 15, 0.4);
            }
        </style>
    </head>
    <body class="bg-olympus min-h-screen text-white font-sans">

        <div class="min-h-screen bg-black/50 flex flex-col">

            <header class="p-6 bg-black/80 border-b-2 border-[#f1c40f] text-center">
                <h2 class="text-2xl font-bold text-[#f1c40f] tracking-widest uppercase">
                    <?php
                    $nombre_usuario = $_SESSION['nombre'] ?? 'Guerrero';
                    echo "Bienvenido, " . htmlspecialchars($nombre_usuario);
                    ?> 
                </h2>
            </header>

            <main class="flex-1 p-8">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-10">
                        <h1 class="text-4xl font-black mb-2">CENTRO DE <span class="text-[#f1c40f]">CONTROL</span></h1>
                        <p class="text-gray-300">Selecciona una herramienta para comenzar</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                        <a href="ver_temas.php" class="glass-card group p-6 rounded-2xl text-center">
                            <div class="text-4xl mb-4 group-hover:scale-125 transition-transform duration-300">📜</div>
                            <h3 class="text-xl font-bold text-[#f1c40f]">Manual</h3>
                            <p class="text-sm text-gray-400 mt-2">Consulta y crea temas del saber.</p>
                        </a>

                        <a href="calendario.php" class="glass-card group p-6 rounded-2xl text-center">
                            <div class="text-4xl mb-4 group-hover:scale-125 transition-transform duration-300">📅</div>
                            <h3 class="text-xl font-bold text-[#f1c40f]">Calendario</h3>
                            <p class="text-sm text-gray-400 mt-2">Organiza tus fechas importantes.</p>
                        </a>

                        <a href="calculadora.php" class="glass-card group p-6 rounded-2xl text-center">
                            <div class="text-4xl mb-4 group-hover:scale-125 transition-transform duration-300">🔢</div>
                            <h3 class="text-xl font-bold text-[#f1c40f]">Calculadora</h3>
                            <p class="text-sm text-gray-400 mt-2">Herramientas matemáticas.</p>
                        </a>

                        <a href="finanzas.php" class="glass-card group p-6 rounded-2xl text-center">
                            <div class="text-4xl mb-4 group-hover:scale-125 transition-transform duration-300">💰</div>
                            <h3 class="text-xl font-bold text-[#f1c40f]">Finanzas</h3>
                            <p class="text-sm text-gray-400 mt-2">Control de ingresos y gastos.</p>
                        </a>

                        <a href="tareas.php" class="glass-card group p-6 rounded-2xl text-center">
                            <div class="text-4xl mb-4 group-hover:scale-125 transition-transform duration-300">✅</div>
                            <h3 class="text-xl font-bold text-[#f1c40f]">Tareas</h3>
                            <p class="text-sm text-gray-400 mt-2">Lista de pendientes por hacer.</p>
                        </a>

                        <a href="apuntes.php" class="glass-card group p-6 rounded-2xl text-center">
                            <div class="text-4xl mb-4 group-hover:scale-125 transition-transform duration-300">✍️</div>
                            <h3 class="text-xl font-bold text-[#f1c40f]">Apuntes</h3>
                            <p class="text-sm text-gray-400 mt-2">Notas rápidas y recordatorios.</p>
                        </a>

                        <a href="multimedia.php" class="glass-card group p-6 rounded-2xl text-center">
                            <div class="text-4xl mb-4 group-hover:scale-125 transition-transform duration-300">🎬</div>
                            <h3 class="text-xl font-bold text-[#f1c40f]">Multimedia</h3>
                            <p class="text-sm text-gray-400 mt-2">Gestión de fotos y videos.</p>
                        </a>

                        <a href="musica.php" class="glass-card group p-6 rounded-2xl text-center">
                            <div class="text-4xl mb-4 group-hover:scale-125 transition-transform duration-300">📱</div>
                            <h3 class="text-xl font-bold text-[#f1c40f]">iPod</h3>
                            <p class="text-sm text-gray-400 mt-2">Tu biblioteca musical personal.</p>
                        </a>

                    </div>

                    <div class="mt-12 text-center">
                        <a href="logout.php" class="inline-block px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-colors shadow-lg">
                            Cerrar Sesión
                        </a>
                    </div>
                </div>
            </main>

            <footer class="p-4 bg-black/90 text-center text-xs text-gray-500 border-t border-[#f1c40f]/30">
                <p>© Manual de Marco 2026 - El conocimiento es poder</p>
            </footer>
        </div>

    </body>
</html>