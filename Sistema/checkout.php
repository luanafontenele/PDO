<?php
session_start();
include("conexao.php"); 

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$produto_id = filter_input(INPUT_GET, 'produto_id', FILTER_VALIDATE_INT);

if (!$produto_id) {
    header("Location: index.php");
    exit;
}

$sql_select = "SELECT titulo, descricao, preco FROM produto WHERE id = ?";
$stmt_select = $pdo->prepare($sql_select);
$stmt_select->execute([$produto_id]);
$produto = $stmt_select->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalizar'])) {
    header("Location: create_avaliacao.php?produto_id=" . $produto_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Checkout do Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Checkout do Curso/Produto</h1>
    <a href="index.php">Voltar ao Cardápio</a>

    <div class="checkout-box">
        <h2>Confirmação de Compra</h2>
        
        <p><strong>Produto:</strong> <?= htmlspecialchars($produto['titulo']) ?></p>
        <p><strong>Descrição:</strong> <?= htmlspecialchars($produto['descricao']) ?></p>
        <p><strong>Preço Total:</strong> R$<?= number_format($produto['preco'], 2, ',', '.') ?></p>
        
        <hr>
        
        <form method="POST">
            <label for="pagamento">Selecione a Forma de Pagamento:</label>
            <select name="pagamento" required style="width: 100%; padding: 8px; margin-bottom: 20px;">
                <option value="">Selecione...</option>
                <option value="cartao">Cartão de Crédito/Débito </option>
                <option value="pix">PIX </option>
                <option value="boleto">Boleto Bancário </option>
            </select>
            
            <button type="submit" name="finalizar" style="background-color: #4B2E05; color: #fff; padding: 12px; border: none; width: 100%; border-radius: 5px; cursor: pointer;">
                Finalizar Compra
            </button>
        </form>
    </div>
</body>
</html>