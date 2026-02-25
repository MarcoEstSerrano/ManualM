<?php
session_start();
require('conexion/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario_id'])) {
    $user_id = $_SESSION['usuario_id'];
    $tipo = $_POST['tipo'];
    $banco = $_POST['banco'];
    $monto = $_POST['monto'];
    $descripcion = $_POST['descripcion'];

    try {
        $pdo->beginTransaction();

        // 1. Insertar movimiento
        $stmt = $pdo->prepare("INSERT INTO movimientos (tipo, monto, descripcion, banco, usuario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$tipo, $monto, $descripcion, $banco, $user_id]);

        // 2. Actualizar saldo
        $operacion = ($tipo == 'ingreso') ? "+" : "-";
        $sql_update = "UPDATE cuentas SET saldo = saldo $operacion ? WHERE banco = ? AND usuario_id = ?";
        $upd = $pdo->prepare($sql_update);
        $upd->execute([$monto, $banco, $user_id]);

        $pdo->commit();
        header("Location: finanzas.php?res=ok");
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}