<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$id = $_SESSION['user_id'];

$res = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $res->fetch_assoc();

$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $conn->query("UPDATE users SET nome='$nome', email='$email' WHERE id=$id");

    $_SESSION['user_nome'] = $nome;
    $sucesso = "Perfil atualizado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<main class="content">

<header class="topbar">
    <h1>Meu Perfil</h1>
</header>

<section class="welcome">

    <?php if($sucesso): ?>
        <div class="mensagem-sucesso"><?php echo $sucesso; ?></div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" value="<?php echo $user['nome']; ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>

        <button class="btn primary">Guardar Alterações</button>

    </form>

</section>

</main>
</div>

</body>
</html>
