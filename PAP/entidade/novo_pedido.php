<?php
include '../includes/auth_entidade.php';
include '../config/db.php';

/*  VERIFICAR SE A ENTIDADE ESTÁ VALIDADA */
$stmt = $conn->prepare("
    SELECT verificada 
    FROM entidades 
    WHERE usuario_id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$entidade = $stmt->get_result()->fetch_assoc();

if (!$entidade || $entidade['verificada'] == 0) {
    die("Esta entidade ainda não foi validada pela logística.");
}

/*  BUSCAR PRODUTOS ATIVOS */
$produtos = $conn->query("
    SELECT * FROM produtos 
    WHERE ativo = 1
");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Pedido</title>
    <link rel="stylesheet" href="../assets/css/pedidos.css">
</head>
<body>

<h2>Novo Pedido</h2>

<form method="post" action="processar_pedido.php">

<table>
    <tr>
        <th>Produto</th>
        <th>Unidade</th>
        <th>Quantidade</th>
    </tr>

    <?php while ($p = $produtos->fetch_assoc()): ?>
    <tr>
        <td><?php echo $p['nome']; ?></td>
        <td><?php echo $p['unidade']; ?></td>
        <td>
            <input 
                type="number" 
                name="quantidade[<?php echo $p['id']; ?>]" 
                step="0.01" 
                min="0"
            >
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<button type="submit">Enviar Pedido</button>

</form>

</body>
</html>
