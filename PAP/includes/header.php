<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="topo">
    <div class="logo">
    <img src="assets/images/imglogo.png" alt="Logo Plataforma">
    <link rel="stylesheet" href="assets/css/header-footer.css">
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <nav>
        <ul>
            <?php if (($_SESSION['user_role'] ?? $_SESSION['user_tipo']) === 'logistica'): ?>
                <li><a href="../logistica/dashboard.php">Dashboard</a></li>
                <li><a href="../logistica/pedidos.php">Pedidos</a></li>
            <?php else: ?>
                <li><a href="../entidade/dashboard.php">Dashboard</a></li>
                <li><a href="../entidade/novo_pedido.php">Novo Pedido</a></li>
            <?php endif; ?>

            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
    <?php endif; ?>
</header>
