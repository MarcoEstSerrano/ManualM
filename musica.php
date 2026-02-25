<?php
session_start();
require('conexion/conexion.php');
if (!isset($_SESSION['usuario_id'])) exit;

$user_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT * FROM musica WHERE usuario_id = ?");
$stmt->execute([$user_id]);
$canciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>iPod Pro Olimpo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #111; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        
        .ipod-case {
            width: 340px;
            height: 600px;
            background: linear-gradient(145deg, #e6e6e6, #ffffff);
            border-radius: 45px;
            padding: 20px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
            border: 1px solid #bbb;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .ipod-screen {
            width: 100%;
            height: 440px; /* Ajustamos para que encaje mejor con el botón home */
            border-radius: 25px;
            overflow: hidden;
            position: relative;
            background: #f5f5f7; /* Gris clarito tipo Apple */
            border: 4px solid #333;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.2);
        }

        /* Pantalla de Reproducción (Azul vibrante) */
        .screen-playing {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, #4facfe 0%, #00f2fe 100%);
            display: none; 
            flex-direction: column;
            align-items: center;
            color: white;
            z-index: 20;
            padding: 15px;
        }

        /* Disco Giratorio */
        .vinyl {
            width: 160px; height: 160px;
            border-radius: 50%;
            background: #1a1a1a;
            border: 6px solid rgba(255,255,255,0.3);
            margin-top: 10px;
            display: flex; justify-content: center; align-items: center;
            animation: spin 6s linear infinite;
            animation-play-state: paused;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .vinyl.active { animation-play-state: running; }
        .vinyl-inner { width: 60px; height: 60px; border-radius: 50%; background: url('image/olympus_tholos.png') center/cover; border: 2px solid #fff; }

        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* Sliders Táctiles (Blancos para que resalten en el azul) */
        input[type=range] { 
            accent-color: #ffffff; 
            width: 85%; 
            height: 4px;
            cursor: pointer;
        }

        .play-touch-btn {
            width: 60px; height: 60px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(5px);
            border: 2px solid white;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-size: 24px;
            margin: 15px 0;
            transition: transform 0.1s;
        }
        .play-touch-btn:active { transform: scale(0.9); }

        /* Lista de canciones */
        .song-item { 
            padding: 15px; 
            border-bottom: 1px solid #e0e0e0; 
            background: #ffffff; 
            color: #333; /* Texto oscuro para que se lea perfecto */
            cursor: pointer; 
            transition: 0.2s; 
        }

        .song-item:hover { 
            background: #3498db; 
            color: white; /* Al pasar el mouse se pone azul con letras blancas */
        }

        /* Botón Home Físico */
        .home-button {
            width: 55px; height: 55px;
            border-radius: 50%; border: 2px solid #ccc;
            margin-top: 25px; display: flex; justify-content: center; align-items: center;
            cursor: pointer; transition: 0.2s;
        }
        .home-button:hover { background: #f0f0f0; }
        .square { width: 16px; height: 16px; border: 2px solid #ccc; border-radius: 4px; }
        
        #view-list div:first-child {
            background: #e8e8ed;
            color: #86868b;
            border-bottom: 1px solid #d2d2d7;
        }
    </style>
</head>
<body>

    <div class="ipod-case">
        <div class="ipod-screen">
            
            <div id="view-list" class="h-full overflow-y-auto">
                <div class="p-3 bg-gray-900 text-[10px] font-bold text-center text-gray-500 tracking-tighter">BIBLIOTECA</div>
                <?php foreach ($canciones as $c): ?>
                    <div class="song-item" onclick="playThis('musica/<?php echo $c['archivo']; ?>', '<?php echo htmlspecialchars($c['titulo']); ?>')">
                        <p class="text-sm font-bold">🎵 <?php echo htmlspecialchars($c['titulo']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="view-player" class="screen-playing">
                <div class="w-full flex justify-between items-center mb-2">
                    <button onclick="showList()" class="text-2xl font-bold">◂</button>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Ahora suena</span>
                    <span class="opacity-0">◂</span> </div>
                
                <div id="disc" class="vinyl">
                    <div class="vinyl-inner"></div>
                </div>

                <div class="text-center mt-4 mb-4">
                    <h2 id="now-title" class="text-lg font-black truncate w-60">Título de Canción</h2>
                    <p class="text-[10px] opacity-80 uppercase font-bold">Manual de Marco</p>
                </div>

                <input type="range" id="seek" value="0">
                <div class="flex justify-between w-[85%] text-[9px] mt-1 mb-2 font-bold">
                    <span id="time">0:00</span>
                    <span id="total">0:00</span>
                </div>

                <div onclick="toggle()" id="m-play" class="play-touch-btn">▶</div>

                <div class="flex items-center gap-2 w-full justify-center mt-2">
                    <span class="text-[10px]">🔈</span>
                    <input type="range" id="vol" min="0" max="1" step="0.1" value="0.5" style="width: 50%;">
                    <span class="text-[10px]">🔊</span>
                </div>
            </div>
        </div>

        <a href="index.php" class="home-button">
            <div class="square"></div>
        </a>
    </div>

    <audio id="audio"></audio>

    <script>
        const audio = document.getElementById('audio');
        const vList = document.getElementById('view-list');
        const vPlayer = document.getElementById('view-player');
        const disc = document.getElementById('disc');
        const btn = document.getElementById('m-play');

        function playThis(src, title) {
            audio.src = src;
            document.getElementById('now-title').innerText = title;
            vList.style.display = 'none';
            vPlayer.style.display = 'flex';
            toggle(true);
        }

        function showList() {
            vList.style.display = 'block';
            vPlayer.style.display = 'none';
        }

        function toggle(force = false) {
            if (audio.paused || force) {
                audio.play();
                btn.innerText = "⏸";
                disc.classList.add('active');
            } else {
                audio.pause();
                btn.innerText = "▶";
                disc.classList.remove('active');
            }
        }

        // Control de Volumen
        document.getElementById('vol').oninput = (e) => {
            audio.volume = e.target.value;
        };

        // Progreso de tiempo
        audio.ontimeupdate = () => {
            if (audio.duration) {
                const p = (audio.currentTime / audio.duration) * 100;
                document.getElementById('seek').value = p;
                document.getElementById('time').innerText = format(audio.currentTime);
                document.getElementById('total').innerText = format(audio.duration);
            }
        };

        // Saltar a punto de la canción
        document.getElementById('seek').oninput = (e) => {
            audio.currentTime = (e.target.value / 100) * audio.duration;
        };

        function format(s) {
            let m = Math.floor(s/60);
            let sec = Math.floor(s%60);
            return m + ":" + (sec < 10 ? "0" : "") + sec;
        }

        // Auto-reproducción de la siguiente canción
        audio.onended = () => {
            // Podríamos añadir lógica aquí, por ahora solo detiene el giro
            disc.classList.remove('active');
            btn.innerText = "▶";
        };
    </script>
</body>
</html>