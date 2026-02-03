<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $unidade = trim($_POST['unidade'] ?? '');
    $preco_base = trim($_POST['preco_base'] ?? '');

    if ($nome === '' || $unidade === '' || $preco_base === '') {
        $erro = 'Preenche os campos obrigatórios.';
    } elseif (!is_numeric($preco_base) || (float)$preco_base < 0) {
        $erro = 'Indica um preço base válido.';
    } else {
        $stmt = $conn->prepare('
            INSERT INTO produtos (nome, categoria, unidade, preco_base, ativo)
            VALUES (?, ?, ?, ?, 1)
        ');
        $stmt->bind_param('sssd', $nome, $categoria, $unidade, $preco_base);
        $stmt->execute();
        $sucesso = 'Produto registado com sucesso.';
    }
}

$produtos = $conn->query('
    SELECT id, nome, categoria, unidade, preco_base, ativo, criado_em
    FROM produtos
    ORDER BY criado_em DESC
');
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Produtos</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="welcome">
            <h2>Gestão de Produtos</h2>
            <p>Regista novos produtos e acompanha os itens disponíveis para pedidos.</p>
        </section>

        <section class="table-wrapper" style="margin-bottom: 24px;">
            <h2>Novo Produto</h2>

            <?php if ($sucesso): ?>
                <div class="alert success"><?php echo htmlspecialchars($sucesso); ?></div>
            <?php endif; ?>
            <?php if ($erro): ?>
                <div class="alert error"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <input type="text" id="categoria" name="categoria" placeholder="Ex.: Legumes">
                </div>

                <div class="form-group">
                    <label for="unidade">Unidade *</label>
                    <input type="text" id="unidade" name="unidade" required placeholder="Ex.: kg">
                </div>

                <div class="form-group">
                    <label for="preco_base">Preço base (€) *</label>
                    <input type="number" id="preco_base" name="preco_base" step="0.01" min="0" required>
                </div>

                <button class="btn primary" type="submit">Guardar Produto</button>
            </form>
        </section>

        <section class="table-wrapper">
            <h2>Produtos Registados</h2>

            <?php if ($produtos->num_rows === 0): ?>
                <p>Sem produtos registados.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Unidade</th>
                            <th>Preço Base</th>
                            <th>Estado</th>
                            <th>Registo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($produto = $produtos->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                <td><?php echo htmlspecialchars($produto['categoria'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($produto['unidade']); ?></td>
                                <td>€ <?php echo htmlspecialchars(number_format((float)$produto['preco_base'], 2, ',', '.')); ?></td>
                                <td>
                                    <span class="badge-status <?php echo $produto['ativo'] ? 'ativo' : 'inativo'; ?>">
                                        <?php echo $produto['ativo'] ? 'ativo' : 'inativo'; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($produto['criado_em'] ?? '-'); ?></td>
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
