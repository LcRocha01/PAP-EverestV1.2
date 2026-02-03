<?php
include '../includes/auth_logistica.php';
include '../config/db.php';

$pedido_id = intval($_GET['pedido_id']);

// Buscar itens do pedido
$stmt = $conn->prepare("
    SELECT pi.*, pr.nome
    FROM pedido_itens pi
    JOIN produtos pr ON pi.produto_id = pr.id
    WHERE pi.pedido_id = ?
");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$itens = $stmt->get_result();

// Buscar margem padrão (se existir)
$margem = $conn->query("SELECT margem_percentual FROM margem_padrao ORDER BY id DESC LIMIT 1");
$margem_padrao = $margem->num_rows ? $margem->fetch_assoc()['margem_percentual'] : 0;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Definir Preços</title>
    <link rel="stylesheet" href="../assets/css/pedidos.css">
</head>
<body>

<h2>Definir Preços – Pedido #<?php echo $pedido_id; ?></h2>

<form method="post" action="guardar_precos.php">

<input type="hidden" name="pedido_id" value="<?php echo $pedido_id; ?>">

<label>Margem média (%)</label>
<input type="number" name="margem_media" value="<?php echo $margem_padrao; ?>" step="0.01">

<table border="1" cellpadding="8">
    <tr>
        <th>Produto</th>
        <th>Qtd</th>
        <th>Preço Base</th>
        <th>Margem (%)</th>
        <th>Preço Final</th>
    </tr>

<?php while ($i = $itens->fetch_assoc()): ?>
<tr>
    <td><?php echo $i['nome']; ?></td>
    <td><?php echo $i['quantidade']; ?></td>
    <td><?php echo $i['preco_base']; ?> €</td>
    <td>
        <input type="number" step="0.01"
               name="margem_item[<?php echo $i['id']; ?>]"
               value="<?php echo $i['margem_percentual'] ?? $margem_padrao; ?>">
    </td>
    <td>
        <input type="number" step="0.01"
               name="preco_final[<?php echo $i['id']; ?>]"
               value="<?php echo $i['preco_final']; ?>">
    </td>
</tr>
<?php endwhile; ?>

</table>

<br>
<button type="submit">Registar Pedido</button>

</form>

</body>
</html>
    
