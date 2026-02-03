<?php
include '../config/db.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $nome_entidade = trim($_POST['nome_entidade']);
    $nif = trim($_POST['nif']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);

    // Avatar opcional
    $avatar = $_POST['avatar'] ?? null;

    // Verificar email duplicado
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $erro = "Este email já está registado.";
    } else {

        // Criar utilizador (role FIXO no servidor)
        $stmt = $conn->prepare("
            INSERT INTO usuarios (nome, email, senha, role) 
            VALUES (?, ?, ?, 'entidade')
        ");
        $stmt->bind_param("sss", $nome, $email, $senha);
        $stmt->execute();

        $usuario_id = $stmt->insert_id;

        // Criar entidade NÃO verificada
            $stmt2 = $conn->prepare("
                INSERT INTO entidades 
                (usuario_id, nome, nif, telefone, endereco, avatar, verificada, estado) 
                VALUES (?, ?, ?, ?, ?, ?, 0, 'pendente')
            ");

        $stmt2->bind_param(
            "isssss",
            $usuario_id,
            $nome_entidade,
            $nif,
            $telefone,
            $endereco,
            $avatar
        );
        $stmt2->execute();

        $sucesso = "Conta criada com sucesso! Insira a chave de associação fornecida pela logística.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo de Entidade</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

<h2>Registo de Entidade</h2>

<?php if ($erro): ?>
    <p style="color:red;"><?php echo $erro; ?></p>
<?php endif; ?>

<?php if ($sucesso): ?>
    <p style="color:green;"><?php echo $sucesso; ?></p>
<?php endif; ?>

<form method="post">

    <h3>Dados de Login</h3>
    <input type="text" name="nome" placeholder="Nome do responsável" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>

    <h3>Dados da Entidade</h3>
    <input type="text" name="nome_entidade" placeholder="Nome da entidade" required>
    <input type="text" name="nif" placeholder="NIF">
    <input type="text" name="telefone" placeholder="Telefone">
    <input type="text" name="endereco" placeholder="Endereço">

    <h3>Escolha um avatar</h3>

    <div class="avatars">
        <?php
        $pasta = __DIR__ . "/../avatars/";

        if (is_dir($pasta)) {
            $files = scandir($pasta);

            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    echo "
                    <label>
                        <input type='radio' name='avatar' value='$file'>
                        <img src='../avatars/$file' style='width:80px;border-radius:50%;cursor:pointer;'>
                    </label>
                    ";
                }
            }
        } else {
            echo "<p style='color:red;'>Pasta de avatares não encontrada.</p>";
        }
        ?>
    </div>

    <br>
    <button type="submit">Criar Conta</button>

</form>

</body>
</html>
