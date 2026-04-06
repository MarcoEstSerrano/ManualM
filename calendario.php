<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEC | Ciclos de Misión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    
    <style>
        :root {
            --ishimura-cyan: #00f2ff;
            --ishimura-orange: #ff8000;
        }

        body {
            background-color: #050a0f;
            background-image: 
                linear-gradient(rgba(0, 242, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 242, 255, 0.05) 1px, transparent 1px),
                url('https://wallpaperaccess.com/full/1235791.jpg');
            background-size: 50px 50px, 50px 50px, cover;
            background-attachment: fixed;
            font-family: 'Courier New', Courier, monospace;
            color: var(--ishimura-cyan);
        }

        .glass-container {
            background: rgba(0, 20, 30, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid var(--ishimura-cyan);
            box-shadow: 0 0 25px rgba(0, 242, 255, 0.15);
            clip-path: polygon(0 2%, 2% 0, 98% 0, 100% 2%, 100% 98%, 98% 100%, 2% 100%, 0 98%);
        }

        /* Personalización extrema de FullCalendar */
        .fc { 
            --fc-border-color: rgba(0, 242, 255, 0.2);
            --fc-page-bg-color: transparent;
            --fc-today-bg-color: rgba(0, 242, 255, 0.1) !important;
        }

        .fc-toolbar-title { 
            color: white !important; 
            text-transform: uppercase; 
            letter-spacing: 3px;
            font-weight: black !important;
            text-shadow: 0 0 8px var(--ishimura-cyan);
        }

        .fc-button { 
            background-color: transparent !important; 
            border: 1px solid var(--ishimura-cyan) !important; 
            color: var(--ishimura-cyan) !important; 
            text-transform: uppercase !important;
            font-size: 0.7rem !important;
            border-radius: 0 !important;
            transition: all 0.3s !important;
        }

        .fc-button:hover { 
            background: var(--ishimura-cyan) !important; 
            color: black !important; 
            box-shadow: 0 0 15px var(--ishimura-cyan);
        }

        .fc-button-active {
            background: var(--ishimura-cyan) !important;
            color: black !important;
        }

        .fc-daygrid-day-number {
            color: white;
            font-weight: bold;
            padding: 10px !important;
        }

        .fc-col-header-cell-cushion {
            color: var(--ishimura-cyan);
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .fc-event {
            border: none !important;
            padding: 2px 5px !important;
            font-size: 0.75rem !important;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Animación de escaneo */
        .scanline {
            width: 100%; height: 100%;
            background: linear-gradient(0deg, rgba(0, 242, 255, 0.03) 50%, transparent 50%);
            background-size: 100% 4px;
            position: absolute; pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8 relative">
    <div class="scanline"></div>

    <div class="max-w-5xl mx-auto glass-container p-6 relative">
        <div class="flex justify-between items-center mb-6 border-b border-cyan-900 pb-4">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tighter italic">MONITOR DE <span class="text-cyan-400">CICLOS</span></h1>
                <p class="text-[10px] text-cyan-700 tracking-widest uppercase">Sistema de Sincronización Orbital CEC</p>
            </div>
            <a href="index.php" class="text-xs text-cyan-600 hover:text-white transition"> [ REVERTIR SESIÓN ] </a>
        </div>

        <div id="calendar" class="min-h-[600px]"></div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'es',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
          },
          events: [
            {
              title: 'INICIO DE PROTOCOLO',
              start: '2026-04-01',
              color: '#00f2ff',
              textColor: 'black'
            },
            {
              title: 'ALERTA: EXAMEN DE RED',
              start: '2026-04-15',
              end: '2026-04-17',
              color: '#ff8000',
              textColor: 'black'
            },
            {
                title: 'ENTREGA DE PROYECTO',
                start: '2026-04-20',
                color: '#ff8000',
                textColor: 'black'
            }
          ],
          dateClick: function(info) {
            // Un alert con estilo de sistema
            const confirmacion = confirm('¿ACCEDER A LA BITÁCORA DEL CICLO: ' + info.dateStr + '?');
            if(confirmacion) {
                console.log("Accediendo a datos...");
            }
          }
        });
        calendar.render();
      });
    </script>
</body>
</html>