<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEC ISHIMURA | Acceso de Seguridad</title>

    <style>
        :root {
            --ishimura-cyan: #00f2ff;
            --ishimura-orange: #ff8000;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
            color: var(--ishimura-cyan);
            overflow: hidden;
            background-color: #050505;
        }

        /* Fondo espacial industrial */
        .bg-image {
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                              url('https://images6.alphacoders.com/131/1310657.jpeg');
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: absolute;
            width: 100%;
            z-index: -1;
            filter: grayscale(0.5) brightness(0.4);
        }

        /* Contenedor Holográfico */
        .login-container {
            background: rgba(0, 20, 30, 0.8);
            padding: 40px;
            border: 1px solid var(--ishimura-cyan);
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.2), inset 0 0 15px rgba(0, 242, 255, 0.1);
            width: 350px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            backdrop-filter: blur(10px);
            clip-path: polygon(0 10%, 10% 0, 90% 0, 100% 10%, 100% 90%, 90% 100%, 10% 100%, 0 90%);
        }

        h2 {
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 5px;
            color: white;
            text-shadow: 0 0 10px var(--ishimura-cyan);
        }

        .quote {
            font-size: 0.7em;
            color: var(--ishimura-cyan);
            opacity: 0.6;
            margin-bottom: 25px;
            display: block;
            text-transform: uppercase;
        }

        input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            background: rgba(0, 242, 255, 0.05);
            border: 1px solid rgba(0, 242, 255, 0.3);
            color: white;
            outline: none;
            transition: 0.3s;
            text-align: center;
        }

        input:focus {
            border-color: var(--ishimura-cyan);
            background: rgba(0, 242, 255, 0.1);
            box-shadow: 0 0 10px rgba(0, 242, 255, 0.2);
        }

        button {
            width: 100%;
            padding: 15px;
            margin-top: 15px;
            background-color: var(--ishimura-cyan);
            color: #000;
            border: none;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.4s;
            clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
        }

        #btnGoogle {
            background: transparent;
            color: var(--ishimura-cyan);
            border: 1px solid var(--ishimura-cyan);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 20px;
        }

        #btnGoogle img {
            width: 18px;
            filter: drop-shadow(0 0 5px var(--ishimura-cyan));
        }

        button:hover {
            background-color: white;
            box-shadow: 0 0 25px var(--ishimura-cyan);
            transform: translateY(-2px);
        }

        .separator {
            margin: 15px 0;
            color: rgba(0, 242, 255, 0.4);
            font-size: 0.6em;
            letter-spacing: 3px;
        }

        /* Línea de escaneo animada */
        .scanline {
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 50%, rgba(0, 242, 255, 0.02) 50%);
            background-size: 100% 4px;
            position: absolute;
            pointer-events: none;
            z-index: 10;
        }
    </style>
</head>
<body>

    <div class="scanline"></div>
    <div class="bg-image"></div>

    <div class="login-container">
        <p style="font-size: 9px; color: #555; margin: 0;">CEC | ACCESS TERMINAL v4.1</p>
        <h2>AUTENTICACIÓN</h2>
        <span class="quote">"Suministrando el futuro de la humanidad"</span>

        <input type="email" id="email" placeholder="ID DE INGENIERO" value="">
        <input type="password" id="pass" placeholder="CÓDIGO DE ACCESO">

        <button id="btnLogin">Iniciando Sesión RIG</button>

        <div class="separator">VERIFICACIÓN EXTERNA</div>

        <button id="btnGoogle">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Logo">
            Acceso vía Red CEC
        </button>

        <div class="separator">¿NUEVO PERSONAL?</div>
        <p style="font-size: 0.8em;">
            <a href="registro.php" style="color: var(--ishimura-orange); text-decoration: none; font-weight: bold; letter-spacing: 1px;">
                SOLICITAR ALTA DE PERSONAL
            </a>
        </p>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import { getAuth, signInWithEmailAndPassword, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyAi43VTq6fiZMrb4__74V0Wpn7we--bIVc",
            authDomain: "manualm.firebaseapp.com",
            projectId: "manualm",
            storageBucket: "manualm.firebasestorage.app",
            messagingSenderId: "853752315860",
            appId: "1:853752315860:web:ab1c18a66c12517c022041"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        const loginExitoso = (user) => {
            const formData = new FormData();
            formData.append('uid', user.uid);
            formData.append('email', user.email);
            fetch('autenticar.php', {method: 'POST', body: formData})
                .then(() => window.location.href = 'index.php');
        };

        document.getElementById('btnLogin').addEventListener('click', () => {
            const email = document.getElementById('email').value;
            const pass = document.getElementById('pass').value;
            signInWithEmailAndPassword(auth, email, pass)
                .then((userCredential) => loginExitoso(userCredential.user))
                .catch((error) => alert("ERROR DE AUTENTICACIÓN: Acceso denegado. " + error.message));
        });

        document.getElementById('btnGoogle').addEventListener('click', () => {
            signInWithPopup(auth, provider)
                .then((result) => loginExitoso(result.user))
                .catch((error) => alert("La Red CEC rechazó la conexión: " + error.message));
        });
    </script>
</body>
</html>