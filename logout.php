<?php
session_start();
session_destroy(); // Esto limpia la sesión de PHP
?>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
  import { getAuth, signOut } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";

  // Tus mismas credenciales
  const firebaseConfig = {
    apiKey: "AIzaSyAi43VTq6fiZMrb4--_74V0Wpn7we--bIVc",
    authDomain: "manualm.firebaseapp.com",
    projectId: "manualm",
    storageBucket: "manualm.firebasestorage.app",
    messagingSenderId: "853752315860",
    appId: "1:853752315860:web:ab1c18a66c12517c022041"
  };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);

  // Cerramos sesión en Firebase y mandamos al login
  signOut(auth).then(() => {
    window.location.href = "login.php";
  });
</script>