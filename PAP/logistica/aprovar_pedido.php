<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("
        UPDATE pedidos
        SET estado = 'registado'
        WHERE id = ?
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header('Location: pedidos.php');
exit;
