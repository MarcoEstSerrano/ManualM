<?php
session_start();
require('conexion/conexion.php');

if ($_POST) {
    $titulo = $_POST['titulo'];
    $archivo = $_FILES['mp3']['name'];
    $destino = "musica/" . $archivo;

    if (move_uploaded_file($_FILES['mp3']['tmp_name'], $destino)) {
        $stmt = $pdo->prepare("INSERT INTO musica (titulo, archivo, usuario_id) VALUES (?, ?, ?)");
        $stmt->execute([$titulo, $archivo, $_SESSION['usuario_id']]);
        header("Location: musica.php");
    }
}
?>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="titulo" placeholder="Nombre de la canción" required>
    <input type="file" name="mp3" accept=".mp3" required>
    <button type="submit">Subir al iPod</button>
</form>