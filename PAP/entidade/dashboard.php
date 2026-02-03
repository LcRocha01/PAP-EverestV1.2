<?php
include '../includes/auth_entidade.php';
include '../config/db.php';

if (empty($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'entidade') {
    header("Location: ../login.php");
    exit;
}

// Verificar se ainda está aprovado
$stmt = $conn->prepare("SELECT aprovado FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ((int)$user['aprovado'] !== 1) {
    die("A sua conta ainda não foi aprovada pela logística.");
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Entidade</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<h1>Área da Entidade</h1>
<p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_nome']); ?> 👋</p>

<ul>
    <li><a href="novo_pedido.php">Novo Pedido</a></li>
    <li><a href="meus_pedidos.php">Meus Pedidos</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>

</body>
</html>
