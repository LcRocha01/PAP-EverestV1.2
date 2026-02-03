<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$pedido_id = $_POST['pedido_id'];
$margem_media = $_POST['margem_media'];

// Guardar margem padrão
$conn->query("INSERT INTO margem_padrao (margem_percentual) VALUES ('$margem_media')");

// Atualizar itens
foreach ($_POST['margem_item'] as $item_id => $margem) {

    $preco_final = $_POST['preco_final'][$item_id];

    $stmt = $conn->prepare("
        UPDATE pedido_itens
        SET margem_percentual = ?, preco_final = ?
        WHERE id = ?
    ");
    $stmt->bind_param("ddi", $margem, $preco_final, $item_id);
    $stmt->execute();
}

// Atualizar estado do pedido
$conn->query("
    UPDATE pedidos SET estado = 'registado'
    WHERE id = $pedido_id
");

header("Location: pedidos.php");
exit;
