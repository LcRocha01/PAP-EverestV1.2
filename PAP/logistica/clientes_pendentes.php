<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

// Buscar entidades pendentes
$stmt = $conn->prepare("
    SELECT id, nome, email, criado_em 
    FROM usuarios 
    WHERE tipo = 'entidade' AND aprovado = 0
    ORDER BY id DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Clientes Pendentes</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Clientes Pendentes</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="table-wrapper">
            <h2>Clientes Pendentes de Aprovação</h2>

            <?php if ($result->num_rows === 0): ?>
                <p>Não existem clientes pendentes.</p>
            <?php else: ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Data Registo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['nome']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['criado_em'] ?? '-'); ?></td>
                            <td>
                                <a href="aprovar_cliente.php?id=<?php echo urlencode($user['id']); ?>" class="btn link">Aprovar</a>
                                <a href="rejeitar_cliente.php?id=<?php echo urlencode($user['id']); ?>" class="btn link">Rejeitar</a>
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
