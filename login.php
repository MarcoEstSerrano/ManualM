<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demuestra que eres digno | Login</title>
    
    <style>
    body, html {
        height: 100%;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: white;
        overflow: hidden;
    }

    .bg-image {
        background-image: url('image/poseidón_kratos.png');
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        filter: brightness(0.5) contrast(1.2);
        position: absolute;
        width: 100%;
        z-index: -1;
    }

    .login-container {
        background: rgba(0, 0, 0, 0.75); 
        padding: 40px;
        border-radius: 10px;
        border: 2px solid #00d4ff; 
        box-shadow: 0 0 30px rgba(0, 212, 255, 0.3);
        width: 320px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        backdrop-filter: blur(5px);
    }

    h2 {
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 5px;
        color: #00d4ff;
    }

    .quote {
        font-style: italic;
        color: #bdc3c7;
        margin-bottom: 25px;
        display: block;
        font-size: 0.9em;
    }

    input {
        width: 90%;
        padding: 12px;
        margin: 10px 0;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #00d4ff;
        color: white;
        border-radius: 5px;
        outline: none;
        transition: 0.3s;
    }

    button {
        width: 100%;
        padding: 15px;
        margin-top: 15px;
        background-color: #00d4ff;
        color: #000;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.4s;
    }

    /* ESTILO PARA EL BOTÓN DE GOOGLE */
    #btnGoogle {
        background: white;
        color: #444;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    button:hover {
        background-color: #fff;
        box-shadow: 0 0 25px #00d4ff;
        transform: scale(1.02);
    }
    
    .separator {
        margin: 15px 0;
        color: #bdc3c7;
        font-size: 0.7em;
        letter-spacing: 2px;
    }
    </style>
</head>
<body>

    <div class="bg-image"></div>

    <div class="login-container">
        <h2>Bienvenido Mortal</h2>
        <span class="quote">"Demuestra que eres digno"</span>
        
        <input type="email" id="email" placeholder="Correo de Guerrero" value="">
        <input type="password" id="pass" placeholder="Contraseña">
        
        <button id="btnLogin">Validar tu destino</button>

        <div class="separator">O TAMBIÉN</div>

        <button id="btnGoogle">
            <img src="icono_google.png" alt="G" style="width: 20px;">
            Acceder como los grandes
        </button>
        <div class="separator">¿NUEVO EN EL OLIMPO?</div>
        <p style="font-size: 0.8em;">
            <a href="registro.php" style="color: #00d4ff; text-decoration: none; font-weight: bold;">
                CREAR CUENTA DE GUERRERO
            </a>
        </p>
        
    </div>

    <script type="module">
      // Importamos también GoogleAuthProvider y signInWithPopup
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

      // FUNCIÓN PARA ENVIAR DATOS A PHP (Para no repetir código)
      const loginExitoso = (user) => {
          const formData = new FormData();
          formData.append('uid', user.uid);
          formData.append('email', user.email);

          fetch('autenticar.php', { method: 'POST', body: formData })
            .then(() => window.location.href = 'index.php');
      };

      // Login normal
      document.getElementById('btnLogin').addEventListener('click', () => {
        const email = document.getElementById('email').value;
        const pass = document.getElementById('pass').value;

        signInWithEmailAndPassword(auth, email, pass)
          .then((userCredential) => loginExitoso(userCredential.user))
          .catch((error) => alert("¡NO ERES DIGNO! " + error.message));
      });

      // LOGIN CON GOOGLE
      document.getElementById('btnGoogle').addEventListener('click', () => {
          signInWithPopup(auth, provider)
            .then((result) => loginExitoso(result.user))
            .catch((error) => alert("El Olimpo rechazó tu conexión: " + error.message));
      });
    </script>
</body>
</html>