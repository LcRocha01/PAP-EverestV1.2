<?php
session_start();
include '../config/db.php';

// Verificar se é logística
if (empty($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'logistica') {
    header("Location: ../login.php");
    exit;
}

// Buscar entidades pendentes
$stmt = $conn->prepare("
    SELECT id, nome, email, created_at 
    FROM usuarios 
    WHERE tipo = 'entidade' AND aprovado = 0
    ORDER BY id DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Clientes Pendentes</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="dashboard-container">

    <h2>Clientes Pendentes de Aprovação</h2>

    <?php if ($result->num_rows === 0): ?>
        <p>Não existem clientes pendentes.</p>
    <?php else: ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Data Registo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>

            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['nome']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $user['created_at'] ?? '-'; ?></td>
                    <td>
                        <a href="aprovar_cliente.php?id=<?php echo $user['id']; ?>" class="btn-approve">Aprovar</a>
                        <a href="rejeitar_cliente.php?id=<?php echo $user['id']; ?>" class="btn-reject">Rejeitar</a>
                    </td>
                </tr>
            <?php endwhile; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>

</body>
</html>
