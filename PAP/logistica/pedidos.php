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
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Pedidos Recebidos</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="table-wrapper">
            <?php if ($pedidos->num_rows === 0): ?>
                <p>Não existem pedidos pendentes.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Entidade</th>
                            <th>Data</th>
                            <th>Estado</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = $pedidos->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($p['id']); ?></td>
                                <td><?php echo htmlspecialchars($p['entidade']); ?></td>
                                <td><?php echo htmlspecialchars($p['data_pedido']); ?></td>
                                <td>
                                    <span class="badge-status pendente">
                                        <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $p['estado']))); ?>
                                    </span>
                                </td>
                                <td>
                                    <a class="btn link" href="definir_precos.php?pedido_id=<?php echo urlencode($p['id']); ?>">
                                        Analisar
                                    </a>
                                    <a class="btn link" href="aprovar_pedido.php?id=<?php echo urlencode($p['id']); ?>">
                                        Aprovar
                                    </a>
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
