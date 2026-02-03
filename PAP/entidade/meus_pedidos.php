<?php
include '../includes/auth_entidade.php';
include '../config/db.php';

$stmt = $conn->prepare("
    SELECT p.id, p.data_pedido, p.estado
    FROM pedidos p
    JOIN entidades e ON p.entidade_id = e.id
    WHERE e.usuario_id = ?
    ORDER BY p.data_pedido DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$pedidos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="../css/pedidos.css">
</head>
<body>

<h2>Meus Pedidos</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Data</th>
        <th>Estado</th>
    </tr>

    <?php while ($p = $pedidos->fetch_assoc()): ?>
    <tr>
        <td>#<?php echo $p['id']; ?></td>
        <td><?php echo $p['data_pedido']; ?></td>
        <td><?php echo ucfirst($p['estado']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="dashboard.php">Voltar</a>

</body>
</html>
