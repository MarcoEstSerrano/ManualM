<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Calculadora del Oráculo | Manual M</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .bg-olympus {
                background-image: url('image/olympus_tholos.png');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
            .glass-calc {
                background: rgba(0, 0, 0, 0.8);
                backdrop-filter: blur(15px);
                border: 2px solid #f1c40f;
                box-shadow: 0 0 20px rgba(241, 196, 15, 0.2);
            }
            .btn-digit {
                background: rgba(255, 255, 255, 0.1);
                transition: all 0.2s;
            }
            .btn-digit:hover {
                background: rgba(241, 196, 15, 0.2);
                color: #f1c40f;
            }
            .btn-op {
                background: rgba(241, 196, 15, 0.1);
                color: #f1c40f;
            }
            .btn-op:hover {
                background: #f1c40f;
                color: black;
            }
            button {
                color: #ffffff !important; /* Fuerza el color blanco */
                font-weight: 800 !important; /* Los hace más gruesos */
                text-shadow: 1px 1px 2px rgba(0,0,0,0.5); /* Sombra para que resalten */
            }
        </style>
    </head>
    <body class="bg-olympus min-h-screen flex items-center justify-center p-4">

        <div class="max-w-md w-full glass-calc rounded-3xl p-6">
            <div class="mb-6 text-right">
                <div id="prev-op" class="text-gray-500 text-sm h-6"></div>
                <div id="display" class="text-4xl font-mono text-white overflow-hidden">0</div>
            </div>

            <div class="grid grid-cols-4 gap-3">
                <button onclick="clearDisplay()" class="col-span-2 p-4 rounded-xl bg-red-900/30 text-red-500 font-bold hover:bg-red-600 hover:text-white transition">AC</button>
                <button onclick="deleteLast()" class="p-4 rounded-xl btn-digit">DEL</button>
                <button onclick="appendOp('/')" class="p-4 rounded-xl btn-op">÷</button>

                <button onclick="appendNum('7')" class="p-4 rounded-xl btn-digit text-xl">7</button>
                <button onclick="appendNum('8')" class="p-4 rounded-xl btn-digit text-xl">8</button>
                <button onclick="appendNum('9')" class="p-4 rounded-xl btn-digit text-xl">9</button>
                <button onclick="appendOp('*')" class="p-4 rounded-xl btn-op">×</button>

                <button onclick="appendNum('4')" class="p-4 rounded-xl btn-digit text-xl">4</button>
                <button onclick="appendNum('5')" class="p-4 rounded-xl btn-digit text-xl">5</button>
                <button onclick="appendNum('6')" class="p-4 rounded-xl btn-digit text-xl">6</button>
                <button onclick="appendOp('-')" class="p-4 rounded-xl btn-op">-</button>

                <button onclick="appendNum('1')" class="p-4 rounded-xl btn-digit text-xl">1</button>
                <button onclick="appendNum('2')" class="p-4 rounded-xl btn-digit text-xl">2</button>
                <button onclick="appendNum('3')" class="p-4 rounded-xl btn-digit text-xl">3</button>
                <button onclick="appendOp('+')" class="p-4 rounded-xl btn-op">+</button>

                <button onclick="appendNum('0')" class="col-span-2 p-4 rounded-xl btn-digit text-xl">0</button>
                <button onclick="appendNum('.')" class="p-4 rounded-xl btn-digit text-xl">.</button>
                <button onclick="calculate()" class="p-4 rounded-xl bg-[#f1c40f] text-black font-bold hover:bg-white transition">=</button>
            </div>

            <a href="index.php" class="block text-center mt-6 text-gray-400 hover:text-[#f1c40f] text-sm uppercase tracking-widest">
                ← Volver al Olimpo
            </a>
        </div>

        <script>
            let display = document.getElementById('display');
            let prevOp = document.getElementById('prev-op');
            let currentInput = '0';

            function updateDisplay() {
                display.innerText = currentInput;
            }

            function appendNum(num) {
                if (currentInput === '0')
                    currentInput = num;
                else
                    currentInput += num;
                updateDisplay();
            }

            function appendOp(op) {
                currentInput += op;
                updateDisplay();
            }

            function clearDisplay() {
                currentInput = '0';
                prevOp.innerText = '';
                updateDisplay();
            }

            function deleteLast() {
                currentInput = currentInput.slice(0, -1);
                if (currentInput === '')
                    currentInput = '0';
                updateDisplay();
            }

            function calculate() {
                try {
                    prevOp.innerText = currentInput + ' =';
                    currentInput = eval(currentInput).toString();
                    updateDisplay();
                } catch {
                    display.innerText = "Error";
                    currentInput = '0';
                }
            }
        </script>
    </body>
</html>