<?php
include '../includes/auth_entidade.php';
include '../config/db.php';

// Buscar entidade do utilizador
$stmt = $conn->prepare("
    SELECT id FROM entidades WHERE usuario_id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$entidade = $stmt->get_result()->fetch_assoc();

if (!$entidade) {
    die("Entidade não encontrada.");
}

// Criar pedido
$stmt = $conn->prepare("
    INSERT INTO pedidos (entidade_id) VALUES (?)
");
$stmt->bind_param("i", $entidade['id']);
$stmt->execute();

$pedido_id = $stmt->insert_id;

// Inserir itens
foreach ($_POST['quantidade'] as $produto_id => $qtd) {
    if ($qtd > 0) {

        $stmt = $conn->prepare("
            SELECT preco_base FROM produtos WHERE id = ?
        ");
        $stmt->bind_param("i", $produto_id);
        $stmt->execute();
        $produto = $stmt->get_result()->fetch_assoc();

        $preco_base = $produto['preco_base'];

        $stmt = $conn->prepare("
            INSERT INTO pedido_itens 
            (pedido_id, produto_id, quantidade, preco_base, preco_final)
            VALUES (?, ?, ?, ?, ?)
        ");
        $preco_final = $preco_base; // ainda sem margem
        $stmt->bind_param(
            "iiddi",
            $pedido_id,
            $produto_id,
            $qtd,
            $preco_base,
            $preco_final
        );
        $stmt->execute();
    }
}

header("Location: meus_pedidos.php");
exit;
