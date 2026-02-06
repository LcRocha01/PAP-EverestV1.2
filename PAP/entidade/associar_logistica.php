<?php
include '../includes/auth_entidade.php';
include '../config/db.php';

$id_entidade = $_SESSION['user_id'];
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = trim($_POST['key'] ?? '');

    if ($key === '') {
        $erro = "Indica uma key válida.";
    } else {
        // Procurar logística com essa key
        $stmt = $conn->prepare("
            SELECT id 
            FROM usuarios 
            WHERE key_logistica = ?
              AND (role = 'logistica' OR tipo = 'logistica')
            LIMIT 1
        ");
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $logistica = $res->fetch_assoc();
            $id_logistica = $logistica['id'];

            // Associar entidade
            $update = $conn->prepare("
                UPDATE entidades 
                SET id_logistica = ?, verificada = 1 
                WHERE usuario_id = ?
            ");
            $update->bind_param("ii", $id_logistica, $id_entidade);
            $update->execute();

            $sucesso = "Associação realizada com sucesso! Já podes fazer pedidos.";
        } else {
            $erro = "Key inválida. Verifica com a empresa de logística.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Associar à Logística</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <main class="content">
        <header class="topbar">
            <h1>Associar à Logística</h1>
            <div class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?></div>
        </header>

        <section class="welcome">
            <p>Insere a key fornecida pela empresa de logística para ativar a tua conta.</p>

            <?php if ($erro): ?>
                <div class="alert error"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>
            <?php if ($sucesso): ?>
                <div class="alert success"><?php echo htmlspecialchars($sucesso); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label>Key de Associação</label>
                    <input type="text" name="key" required>
                </div>

                <button class="btn primary" type="submit">Associar</button>
            </form>
        </section>
    </main>
</div>

</body>
</html>
