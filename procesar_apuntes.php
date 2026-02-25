<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) exit();
$user_id = $_SESSION['usuario_id'];

// ELIMINAR
if (isset($_GET['id']) && isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
    $stmt = $pdo->prepare("DELETE FROM apuntes WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$_GET['id'], $user_id]);
}

// CREAR
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    
    $stmt = $pdo->prepare("INSERT INTO apuntes (titulo, contenido, usuario_id) VALUES (?, ?, ?)");
    $stmt->execute([$titulo, $contenido, $user_id]);
}

header("Location: apuntes.php");
exit();