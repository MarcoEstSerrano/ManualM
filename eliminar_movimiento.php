<?php
session_start();
require('conexion/conexion.php');

if (isset($_GET['id']) && isset($_SESSION['usuario_id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['usuario_id'];

    try {
        $pdo->beginTransaction();

        // 1. Obtener datos del movimiento antes de borrar
        $stmt = $pdo->prepare("SELECT monto, tipo, banco FROM movimientos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $user_id]);
        $mov = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($mov) {
            // 2. Revertir el saldo en la cuenta
            // Si borro un ingreso, resto. Si borro un gasto/capricho, devuelvo el dinero (+).
            $operacion = ($mov['tipo'] == 'ingreso') ? "-" : "+";
            
            $upd = $pdo->prepare("UPDATE cuentas SET saldo = saldo $operacion ? WHERE banco = ? AND usuario_id = ?");
            $upd->execute([$mov['monto'], $mov['banco'], $user_id]);

            // 3. Borrar el movimiento
            $del = $pdo->prepare("DELETE FROM movimientos WHERE id = ?");
            $del->execute([$id]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error al eliminar: " . $e->getMessage());
    }
}
header("Location: finanzas.php");
exit();