<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $margem = trim($_POST['margem_percentual'] ?? '');

    if ($margem === '' || !is_numeric($margem)) {
        $erro = 'Indica um valor de margem válido.';
    } elseif ((float)$margem < 0) {
        $erro = 'A margem não pode ser negativa.';
    } else {
        $stmt = $conn->prepare('
            INSERT INTO margem_padrao (margem_percentual)
            VALUES (?)
        ');
        $stmt->bind_param('d', $margem);
        $stmt->execute();
        $sucesso = 'Margem padrão atualizada com sucesso.';
    }
}

$ultima = $conn->query('
    SELECT margem_percentual, atualizado_em
    FROM margem_padrao
    ORDER BY atualizado_em DESC
    LIMIT 1
')->fetch_assoc();

$historico = $conn->query('
    SELECT margem_percentual, atualizado_em
    FROM margem_padrao
    ORDER BY atualizado_em DESC
    LIMIT 10
');
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Margem Padrão</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Margem Padrão</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="welcome">
            <h2>Configuração de margem</h2>
            <p>Define a percentagem padrão aplicada aos novos pedidos.</p>
        </section>

        <section class="table-wrapper" style="margin-bottom: 24px;">
            <h2>Atualizar margem</h2>

            <?php if ($sucesso): ?>
                <div class="alert success"><?php echo htmlspecialchars($sucesso); ?></div>
            <?php endif; ?>
            <?php if ($erro): ?>
                <div class="alert error"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="margem_percentual">Margem (%) *</label>
                    <input type="number" id="margem_percentual" name="margem_percentual" step="0.01" min="0" required>
                </div>

                <button class="btn primary" type="submit">Guardar Margem</button>
            </form>
        </section>

        <section class="table-wrapper" style="margin-bottom: 24px;">
            <h2>Margem atual</h2>
            <?php if (!$ultima): ?>
                <p>Sem margem definida.</p>
            <?php else: ?>
                <p>
                    <strong><?php echo htmlspecialchars(number_format((float)$ultima['margem_percentual'], 2, ',', '.')); ?>%</strong>
                    — atualizada em <?php echo htmlspecialchars($ultima['atualizado_em']); ?>
                </p>
            <?php endif; ?>
        </section>

        <section class="table-wrapper">
            <h2>Histórico recente</h2>

            <?php if ($historico->num_rows === 0): ?>
                <p>Sem histórico disponível.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Margem</th>
                            <th>Atualizado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($linha = $historico->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(number_format((float)$linha['margem_percentual'], 2, ',', '.')); ?>%</td>
                                <td><?php echo htmlspecialchars($linha['atualizado_em']); ?></td>
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
