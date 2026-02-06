<?php
include '../includes/auth_entidade.php';
include '../config/db.php';

/*  VERIFICAR SE A ENTIDADE ESTÁ VALIDADA */
$stmt = $conn->prepare("
    SELECT verificada 
    FROM entidades 
    WHERE usuario_id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$entidade = $stmt->get_result()->fetch_assoc();

if (!$entidade || $entidade['verificada'] == 0) {
    die("Esta entidade ainda não foi validada pela logística.");
}

/*  BUSCAR PRODUTOS ATIVOS */
$produtos = $conn->query("
    SELECT * FROM produtos 
    WHERE ativo = 1
");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Pedido</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Novo Pedido</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="welcome">
            <p>Seleciona os produtos e as quantidades para criares um novo pedido.</p>
        </section>

        <section class="table-wrapper">
            <?php if (!empty($_SESSION['erro_pedido'])): ?>
                <div class="alert error"><?php echo htmlspecialchars($_SESSION['erro_pedido']); ?></div>
                <?php unset($_SESSION['erro_pedido']); ?>
            <?php endif; ?>

            <form method="post" action="processar_pedido.php">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Unidade</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = $produtos->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['nome']); ?></td>
                                <td><?php echo htmlspecialchars($p['unidade']); ?></td>
                                <td>
                                    <input
                                        type="number"
                                        name="quantidade[<?php echo (int)$p['id']; ?>]"
                                        step="0.01"
                                        min="0"
                                    >
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div style="margin-top: 16px;">
                    <button class="btn primary" type="submit">Enviar Pedido</button>
                </div>
            </form>
        </section>
    </main>
</div>

</body>
</html>
