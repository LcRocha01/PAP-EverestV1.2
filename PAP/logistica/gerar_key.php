<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$sucesso = '';

// Gerar nova key
if (isset($_POST['gerar'])) {

    $key = bin2hex(random_bytes(16)); // 32 caracteres seguros
    $id = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        UPDATE usuarios 
        SET chave_sistema = ? 
        WHERE id = ?
    ");
    $stmt->bind_param("si", $key, $id);
    $stmt->execute();

    $sucesso = "Nova chave gerada com sucesso!";
}

// Buscar key atual
$stmt = $conn->prepare("SELECT chave_sistema FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$dados = $result->fetch_assoc();
$key_atual = $dados['chave_sistema'];
?>

<h2>Chave de Associação</h2>

<?php if ($sucesso): ?>
    <p style="color:green;"><?php echo $sucesso; ?></p>
<?php endif; ?>

<form method="post">
    <button type="submit" name="gerar">Gerar Nova Chave</button>
</form>

<?php if ($key_atual): ?>
    <p><strong>Chave atual:</strong></p>
    <input type="text" value="<?php echo $key_atual; ?>" readonly style="width:300px;">
<?php endif; ?>
