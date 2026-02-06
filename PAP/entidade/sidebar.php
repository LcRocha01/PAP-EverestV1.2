<?php
$current = basename($_SERVER['PHP_SELF']);
$menu = [
    'dashboard.php' => '🏠 Dashboard',
    'novo_pedido.php' => '📝 Novo Pedido',
    'meus_pedidos.php' => '📦 Meus Pedidos',
    'associar_logistica.php' => '🔗 Associar Logística',
];
?>

<aside class="sidebar">
    <div class="sidebar-logo">
        <h2>ENTIDADE</h2>
        <span>Área do Cliente</span>
    </div>

    <nav class="menu">
        <?php foreach ($menu as $link => $label): ?>
            <a href="<?php echo $link; ?>" class="<?php echo $current === $link ? 'active' : ''; ?>">
                <?php echo $label; ?>
            </a>
        <?php endforeach; ?>

        <a href="../logout.php" class="logout">🚪 Logout</a>
    </nav>
</aside>
