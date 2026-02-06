<?php
session_start();
include 'config/db.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {

        // Buscar utilizador
        $stmt = $conn->prepare("
            SELECT id, nome, email, senha, tipo, role, ativo, aprovado 
            FROM usuarios 
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            // Verificar se conta está ativa
            if ((int)$user['ativo'] !== 1) {
                $erro = "Conta desativada. Contacte o administrador.";
            }

            // Verificar se já foi aprovada pela logística
            elseif ((int)$user['aprovado'] !== 1) {
                $erro = "A sua conta ainda está em análise pela logística.";
            }

            // Verificar password (seguro)
            elseif (!password_verify($senha, $user['senha'])) {
                $erro = "Senha incorreta.";
            }

            else {
                // Login válido → proteger sessão
                session_regenerate_id(true);

                $role = $user['role'] ?: $user['tipo'];
                if ($role === 'admin') {
                    $role = 'logistica';
                }

                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['user_tipo'] = $role;
                $_SESSION['user_role'] = $role;

                // Redirecionar conforme tipo
                if ($role === 'logistica') {
                    header("Location: logistica/dashboard.php");
                } else {
                    header("Location: entidade/dashboard.php");
                }
                exit;
            }

        } else {
            $erro = "Email ou senha inválidos.";
        }

    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Entrar • Plataforma Logística</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<div class="login-wrapper">

    <div class="login-header">
        <h1>Bem-vindo de volta</h1>
    </div>

    <form method="post" class="login-card">

        <div class="login-logo">
            <img src="assets/images/imglogo.png" alt="Logo Plataforma">
        </div>

        <?php if ($erro): ?>
            <div class="login-error"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>

    </form>

</div>

</body>
</html>
