<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$pedidos = $conn->query("
    SELECT p.id, p.data_pedido, p.estado, e.nome AS entidade
    FROM pedidos p
    JOIN entidades e ON p.entidade_id = e.id
    WHERE p.estado IN ('pendente','em_analise')
    ORDER BY p.data_pedido ASC
");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Logística</title>
    <link rel="stylesheet" href="../css/pedidos.css">
</head>
<body>

<h1>Pedidos Recebidos</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Entidade</th>
        <th>Data</th>
        <th>Estado</th>
        <th>Ação</th>
    </tr>

    <?php while ($p = $pedidos->fetch_assoc()): ?>
    <tr>
        <td>#<?php echo $p['id']; ?></td>
        <td><?php echo $p['entidade']; ?></td>
        <td><?php echo $p['data_pedido']; ?></td>
        <td><?php echo ucfirst($p['estado']); ?></td>
        <td>
            <a href="definir_precos.php?pedido_id=<?php echo $p['id']; ?>">
                Analisar
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="dashboard.php">Voltar</a>

</body>
</html>
