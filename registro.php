<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse en el Olimpo</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Reutiliza el estilo de tu login para que se vea igual de épico */
        body { background-color: #0b0d17; color: white; font-family: sans-serif; text-align: center; padding-top: 50px; }
        .reg-container { background: rgba(0,0,0,0.8); padding: 30px; border-radius: 10px; border: 1px solid #00d4ff; display: inline-block; }
        input { display: block; width: 250px; padding: 10px; margin: 10px auto; background: #1a1a1a; border: 1px solid #00d4ff; color: white; }
        button { background: #00d4ff; color: black; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="reg-container">
        <h2>Únete a las Legiones</h2>
        <input type="text" id="nombre" placeholder="Nombre de Guerrero">
        <input type="email" id="email" placeholder="Correo electrónico">
        <input type="password" id="password" placeholder="Contraseña">
        <button id="btnRegistrar">CONSAGRAR REGISTRO</button>
        <p><a href="login.php" style="color: #00d4ff; text-decoration: none; font-size: 0.8em;">YA SOY DIGNO (VOLVER)</a></p>
    </div>

   <script type="module">
            import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
            import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";

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

            document.getElementById('btnRegistrar').addEventListener('click', (e) => {
              e.preventDefault(); // Evita que el formulario se envíe por PHP tradicional
              const email = document.getElementById('email').value;
              const pass = document.getElementById('password').value;
              const nombre = document.getElementById('nombre').value;

              // 1. Crear el usuario en Firebase
              createUserWithEmailAndPassword(auth, email, pass)
                // Dentro del .then de tu registro en Firebase
                .then((userCredential) => {
                    const user = userCredential.user;
                    const formData = new FormData();
                    formData.append('nombre', document.getElementById('nombre').value);
                    formData.append('email', user.email);
                    formData.append('uid', user.uid); // Usamos el UID de Firebase como referencia

                    return fetch('procesar_registro.php', { method: 'POST', body: formData });
                })
                .then(() => {
                    alert("¡Registro completo en el Olimpo y en la PC!");
                    window.location.href = 'login.php';
                });
                })
                .catch((error) => alert("El Olimpo te rechazó: " + error.message));
            });
</script>
</body>
</html>