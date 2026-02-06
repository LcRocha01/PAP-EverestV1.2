<?php
include '../includes/auth_logistica.php';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel da Logística</title>
    <link rel="stylesheet" href="../assets/css/dashboard_log.css">
</head>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="content">

        <header class="topbar">
            <h1>Dashboard</h1>

            <div class="topbar-right">

                <!-- NOTIFICAÇÕES -->
                <div class="notificacao">
                    <span class="sino">🔔</span>
                    <span class="badge">0</span> <!-- depois ligamos à BD -->
                </div>

                <!-- UTILIZADOR -->
                <div class="user-info">
                    👤 <?php echo htmlspecialchars($_SESSION['user_nome']); ?>
                </div>

            </div>
        </header>



        <section class="welcome">
            <h2>Bem-vindo à área da logística</h2>
            <p>
                Aqui podes gerir pedidos, entidades, margens de lucro e acompanhar
                o desempenho da operação logística.
            </p>
        </section>

        <!-- CARDS RESUMO -->
        <section class="cards">

            <div class="card">
                <h3>Pedidos Pendentes</h3>
                <span class="numero">0</span>
            </div>

            <div class="card">
                <h3>Entidades Ativas</h3>
                <span class="numero">0</span>
            </div>

            <div class="card">
                <h3>Produtos</h3>
                <span class="numero">0</span>
            </div>

            <div class="card">
                <h3>Lucro Estimado</h3>
                <span class="numero">€ 0,00</span>
            </div>

        </section>

    </main>
</div>

</body>
</html>
