<?php
$current = basename($_SERVER['PHP_SELF']);
$menu = [
    'dashboard.php' => '🏠 Dashboard',
    'pedidos.php' => '📦 Pedidos',
    'clientes.php' => '🏪 Clientes',
    'produtos.php' => '🥕 Produtos',
    'margem.php' => '💰 Margem Padrão',
    'notificacoes.php' => '🔔 Notificações',
    'relatorios.php' => '📊 Relatórios',
    'perfil.php' => '👤 Meu Perfil',
];
?>

<aside class="sidebar">
    <div class="sidebar-logo">
        <h2>LOGÍSTICA</h2>
        <span>Painel Administrativo</span>
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
