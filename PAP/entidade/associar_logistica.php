<?php
include '../includes/auth_entidade.php';
include '../config/db.php';

$id_entidade = $_SESSION['user_id'];
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $key = $_POST['key'];

    // Procurar logística com essa key
    $res = $conn->query("SELECT id FROM usuarios WHERE key_logistica = '$key' AND tipo = 'logistica'");

    if ($res->num_rows == 1) {

        $logistica = $res->fetch_assoc();
        $id_logistica = $logistica['id'];

        // Associar entidade
        $conn->query("
            UPDATE entidades 
            SET id_logistica = $id_logistica, verificada = 1 
            WHERE usuario_id = $id_entidade
        ");

        $sucesso = "Associação realizada com sucesso! Já podes fazer pedidos.";

    } else {
        $erro = "KEY inválida. Verifica com a empresa de logística.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Associar à Logística</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="layout">

<main class="content">

<header class="topbar">
    <h1>Associar à Logística</h1>
</header>

<section class="welcome">

<p>Insere a KEY fornecida pela empresa de logística para ativar a tua conta.</p>

<?php if($erro): ?><div class="mensagem-erro"><?php echo $erro; ?></div><?php endif; ?>
<?php if($sucesso): ?><div class="mensagem-sucesso"><?php echo $sucesso; ?></div><?php endif; ?>

<form method="post">

    <div class="form-group">
        <label>KEY de Associação</label>
        <input type="text" name="key" required>
    </div>

    <button class="btn primary">Associar</button>

</form>

</section>

</main>
</div>

</body>
</html>
