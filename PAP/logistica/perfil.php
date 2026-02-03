<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome === '' || $email === '') {
        $erro = "Preenche todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Introduz um email válido.";
    } else {
        $update = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $update->bind_param("ssi", $nome, $email, $id);
        $update->execute();

        $_SESSION['user_nome'] = $nome;
        $sucesso = "Perfil atualizado com sucesso!";
        $user['nome'] = $nome;
        $user['email'] = $email;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<main class="content">

<header class="topbar">
    <h1>Meu Perfil</h1>
</header>

<section class="welcome">

    <?php if ($sucesso): ?>
        <div class="alert success"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>
    <?php if ($erro): ?>
        <div class="alert error"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>

        <button class="btn primary" type="submit">Guardar Alterações</button>

    </form>

</section>

</main>
</div>

</body>
</html>
