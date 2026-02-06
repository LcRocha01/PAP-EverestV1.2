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
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Meus Pedidos</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="table-wrapper">
            <?php if ($pedidos->num_rows === 0): ?>
                <p>Sem pedidos registados.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = $pedidos->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($p['id']); ?></td>
                                <td><?php echo htmlspecialchars($p['data_pedido']); ?></td>
                                <td>
                                    <span class="badge-status pendente">
                                        <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $p['estado']))); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</div>

</body>
</html>
