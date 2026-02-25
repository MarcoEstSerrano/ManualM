<?php
session_start();
require('conexion/conexion.php');

if (!isset($_SESSION['usuario_id'])) exit();

$user_id = $_SESSION['usuario_id'];

// --- Lógica para ELIMINAR y COMPLETAR (vía GET) ---
if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id = $_GET['id'];
    
    if ($_GET['accion'] == 'eliminar') {
        $stmt = $pdo->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $user_id]);
    } 
    elseif ($_GET['accion'] == 'completar') {
        // Cambiar entre Pendiente y Completada (Toggle)
        $stmt = $pdo->prepare("UPDATE tareas SET estado = IF(estado='Pendiente', 'Completada', 'Pendiente') WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $user_id]);
    }
}

// --- Lógica para CREAR (vía POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
    $titulo = $_POST['titulo'];
    $prioridad = $_POST['prioridad'];
    
    $stmt = $pdo->prepare("INSERT INTO tareas (titulo, prioridad, usuario_id) VALUES (?, ?, ?)");
    $stmt->execute([$titulo, $prioridad, $user_id]);
}

header("Location: tareas.php");
exit();