<?php
include '../includes/auth_logistica.php';

include '../config/db.php';

$id_logistica = $_SESSION['user_id'];

$clientes = $conn->query("SELECT * FROM entidades WHERE id_logistica = $id_logistica AND ativa = 1");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<main class="content">

<header class="topbar">
    <h1>Clientes Associados</h1>
</header>

<section class="welcome">
    <p>Aqui podes ver todas as entidades que utilizam os teus serviços logísticos.</p>
</section>

<section class="cards">

<?php while($c = $clientes->fetch_assoc()): ?>

<div class="card">
    <h3><?php echo $c['nome']; ?></h3>
    <p><?php echo $c['email']; ?></p>
    <p><strong>NIF:</strong> <?php echo $c['nif']; ?></p>
</div>

<?php endwhile; ?>

</section>

</main>
</div>

</body>
</html>
