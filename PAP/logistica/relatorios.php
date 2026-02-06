<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$totais = [
    'entidades' => 0,
    'pedidos' => 0,
    'produtos' => 0,
    'pendentes' => 0,
];

$totais['entidades'] = (int)$conn->query("SELECT COUNT(*) AS total FROM entidades WHERE estado = 'ativo'")->fetch_assoc()['total'];
$totais['pedidos'] = (int)$conn->query("SELECT COUNT(*) AS total FROM pedidos")->fetch_assoc()['total'];
$totais['produtos'] = (int)$conn->query("SELECT COUNT(*) AS total FROM produtos WHERE ativo = 1")->fetch_assoc()['total'];
$totais['pendentes'] = (int)$conn->query("SELECT COUNT(*) AS total FROM pedidos WHERE estado = 'pendente'")->fetch_assoc()['total'];

$recentes = $conn->query('
    SELECT p.id, p.data_pedido, p.estado, e.nome AS entidade
    FROM pedidos p
    JOIN entidades e ON p.entidade_id = e.id
    ORDER BY p.data_pedido DESC
    LIMIT 10
');
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatórios</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Relatórios</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="welcome">
            <h2>Resumo da operação</h2>
            <p>Indicadores gerais para acompanhar o desempenho da logística.</p>
        </section>

        <section class="cards">
            <div class="card">
                <h3>Entidades Ativas</h3>
                <span class="numero"><?php echo htmlspecialchars((string)$totais['entidades']); ?></span>
            </div>
            <div class="card">
                <h3>Pedidos Totais</h3>
                <span class="numero"><?php echo htmlspecialchars((string)$totais['pedidos']); ?></span>
            </div>
            <div class="card">
                <h3>Pedidos Pendentes</h3>
                <span class="numero"><?php echo htmlspecialchars((string)$totais['pendentes']); ?></span>
            </div>
            <div class="card">
                <h3>Produtos Ativos</h3>
                <span class="numero"><?php echo htmlspecialchars((string)$totais['produtos']); ?></span>
            </div>
        </section>

        <section class="table-wrapper" style="margin-top: 24px;">
            <h2>Últimos pedidos</h2>

            <?php if ($recentes->num_rows === 0): ?>
                <p>Sem pedidos registados.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Entidade</th>
                            <th>Data</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pedido = $recentes->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($pedido['id']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['entidade']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['data_pedido']); ?></td>
                                <td>
                                    <span class="badge-status pendente">
                                        <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $pedido['estado']))); ?>
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
