<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) exit();
$user_id = $_SESSION['usuario_id'];

// ELIMINAR
if (isset($_GET['id']) && isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
    $stmt = $pdo->prepare("DELETE FROM multimedia WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$_GET['id'], $user_id]);
}

// CREAR
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $url = $_POST['url'];
    
    $stmt = $pdo->prepare("INSERT INTO multimedia (titulo, url_youtube, usuario_id) VALUES (?, ?, ?)");
    $stmt->execute([$titulo, $url, $user_id]);
}

header("Location: multimedia.php");
exit();