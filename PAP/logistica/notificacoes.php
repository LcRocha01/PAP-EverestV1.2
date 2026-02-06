<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$id_logistica = $_SESSION['user_id'];

$update = $conn->prepare('
    UPDATE notificacoes
    SET lida = 1
    WHERE id_logistica = ? AND lida = 0
');
$update->bind_param('i', $id_logistica);
$update->execute();

$stmt = $conn->prepare('
    SELECT mensagem, lida, data
    FROM notificacoes
    WHERE id_logistica = ?
    ORDER BY data DESC
    LIMIT 50
');
$stmt->bind_param('i', $id_logistica);
$stmt->execute();
$notificacoes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Notificações</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Notificações</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="table-wrapper">
            <h2>Mensagens recentes</h2>

            <?php if ($notificacoes->num_rows === 0): ?>
                <p>Sem notificações disponíveis.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mensagem</th>
                            <th>Data</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($linha = $notificacoes->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($linha['mensagem']); ?></td>
                                <td><?php echo htmlspecialchars($linha['data']); ?></td>
                                <td>
                                    <span class="badge-status lida">Lida</span>
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
