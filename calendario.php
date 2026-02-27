<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario | Manual M</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    
    <style>
        .bg-olympus {
            background-image: url('image/olympus_tholos.png'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-container {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(15px);
            border: 2px solid #f1c40f;
        }
        /* Personalización de colores del calendario para que sea dorado */
        .fc { color: white; font-family: sans-serif; }
        .fc-toolbar-title { color: #f1c40f !important; text-transform: uppercase; font-weight: bold; }
        .fc-button { background-color: #f1c40f !important; border: none !important; color: black !important; font-weight: bold !important; }
        .fc-daygrid-day:hover { background: rgba(241, 196, 15, 0.1) !important; cursor: pointer; }
        .fc-col-header-cell { background: rgba(255, 255, 255, 0.1); padding: 10px 0 !important; }
        .fc-day-today { background: rgba(241, 196, 15, 0.2) !important; }
    </style>
</head>
<body class="bg-olympus min-h-screen p-4 md:p-8">

    <div class="max-w-5xl mx-auto glass-container rounded-3xl p-6 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-black text-[#f1c40f] tracking-tighter">Herramienta de <span class="text-white">Calendario</span></h1>
            <a href="index.php" class="text-sm text-gray-400 hover:text-[#f1c40f] transition">← VOLVER</a>
        </div>

        <div id="calendar" class="min-h-[600px]"></div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'es', // Lo ponemos en español
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
          },
          // Aquí podrías cargar eventos desde tu base de datos después
          events: [
            {
              title: 'Lanzamiento Manual',
              start: '2024-05-20',
              color: '#f1c40f',
              textColor: 'black'
            },
            {
              title: 'Examen de Programación',
              start: '2024-05-25',
              end: '2024-05-27',
              color: '#e74c3c'
            }
          ],
          dateClick: function(info) {
            alert('Has seleccionado el día: ' + info.dateStr + '\n¡Aquí podrías abrir un modal para guardar una tarea!');
          }
        });
        calendar.render();
      });
    </script>
</body>
</html>