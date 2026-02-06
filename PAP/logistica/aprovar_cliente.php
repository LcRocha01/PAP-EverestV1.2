<?php
session_start();
include '../config/db.php';

// Segurança: só logística
$role = $_SESSION['user_role'] ?? $_SESSION['user_tipo'] ?? null;
if (empty($_SESSION['user_id']) || $role !== 'logistica') {
    header("Location: ../login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {

    $stmt = $conn->prepare("
        UPDATE usuarios 
        SET aprovado = 1 
        WHERE id = ? AND tipo = 'entidade'
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: clientes_pendentes.php");
exit;
