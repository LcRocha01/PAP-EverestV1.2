<?php
session_start();
include '../config/db.php';

$role = $_SESSION['user_role'] ?? $_SESSION['user_tipo'] ?? null;
if (empty($_SESSION['user_id']) || $role !== 'entidade') {
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT verificada, estado 
    FROM entidades 
    WHERE usuario_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$entidade = $result->fetch_assoc();

// Se ainda estiver pendente
if ($entidade['estado'] == 'pendente') {
    header("Location: aguarda_aprovacao.php");
    exit;
}

// Se foi rejeitada
if ($entidade['estado'] == 'rejeitado') {
    echo "A sua conta foi rejeitada pela empresa de logística.";
    exit;
}

// Se não estiver ativa
if ($entidade['estado'] != 'ativo') {
    echo "Conta suspensa. Contacte a empresa de logística.";
    exit;
}
