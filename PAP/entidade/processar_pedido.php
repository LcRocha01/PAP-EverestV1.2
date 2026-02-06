<?php
include '../includes/auth_entidade.php';
include '../config/db.php';

// Buscar entidade do utilizador
$stmt = $conn->prepare("
    SELECT id, id_logistica, nome
    FROM entidades
    WHERE usuario_id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$entidade = $stmt->get_result()->fetch_assoc();

if (!$entidade) {
    die("Entidade não encontrada.");
}

// Validar itens do pedido
$quantidades = $_POST['quantidade'] ?? [];
$tem_itens = false;
foreach ($quantidades as $qtd) {
    if ((float)$qtd > 0) {
        $tem_itens = true;
        break;
    }
}

if (!$tem_itens) {
    $_SESSION['erro_pedido'] = 'Seleciona pelo menos um produto com quantidade válida.';
    header("Location: novo_pedido.php");
    exit;
}

// Criar pedido
$stmt = $conn->prepare("
    INSERT INTO pedidos (entidade_id) VALUES (?)
");
$stmt->bind_param("i", $entidade['id']);
$stmt->execute();

$pedido_id = $stmt->insert_id;

// Inserir itens
foreach ($quantidades as $produto_id => $qtd) {
    if ((float)$qtd > 0) {

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

if (!empty($entidade['id_logistica'])) {
    $mensagem = sprintf(
        'Novo pedido #%d registado pela entidade %s.',
        $pedido_id,
        $entidade['nome']
    );
    $stmt = $conn->prepare("
        INSERT INTO notificacoes (id_logistica, mensagem)
        VALUES (?, ?)
    ");
    $stmt->bind_param("is", $entidade['id_logistica'], $mensagem);
    $stmt->execute();
}

header("Location: meus_pedidos.php");
exit;
