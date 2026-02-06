<?php
include '../includes/auth_logistica.php';

include '../config/db.php';

$id_logistica = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT e.nome, e.nif, e.estado, e.criado_em, u.email
    FROM entidades e
    JOIN usuarios u ON e.usuario_id = u.id
    WHERE e.id_logistica = ? AND e.estado = 'ativo'
    ORDER BY e.nome ASC
");
$stmt->bind_param("i", $id_logistica);
$stmt->execute();
$clientes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
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

<section class="table-wrapper">
    <?php if ($clientes->num_rows === 0): ?>
        <p>Sem clientes ativos associados.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Entidade</th>
                    <th>Email</th>
                    <th>NIF</th>
                    <th>Estado</th>
                    <th>Registo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = $clientes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['nome']); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo htmlspecialchars($c['nif'] ?? '-'); ?></td>
                        <td>
                            <span class="badge-status ativo"><?php echo htmlspecialchars($c['estado']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($c['criado_em'] ?? '-'); ?></td>
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
