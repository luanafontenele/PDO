<?php
session_start();
include("conexao.php"); 

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$erro = '';
$sucesso = '';

$id_produto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_produto) {
    header("Location: admin_produtos.php");
    exit;
}

$sql_select = "SELECT titulo, descricao, preco, foto FROM produto WHERE id = ?";
$stmt_select = $pdo->prepare($sql_select);
$stmt_select->execute([$id_produto]);
$produto = $stmt_select->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado!");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $preco_limpo = str_replace(['R$', ',', ' '], ['', '.', ''], $_POST['preco']);
    $preco = filter_var($preco_limpo, FILTER_VALIDATE_FLOAT);
    
    $foto_path = filter_input(INPUT_POST, 'foto_path', FILTER_SANITIZE_URL);

    if ($titulo && $descricao && $preco !== false) {
        
        $sql_update = "UPDATE produto SET titulo = ?, descricao = ?, preco = ?, foto = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        
        try {
            $stmt_update->execute([$titulo, $descricao, $preco, $foto_path, $id_produto]);
            
            header("Location: admin_produtos.php?status=editado");
            exit();

        } catch (PDOException $e) {
            $erro = "Erro ao atualizar produto: " . $e->getMessage();
        }
    } else {
        $erro = "Por favor, preencha todos os campos corretamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Editar Prato: <?= htmlspecialchars($produto['titulo']) ?></h1>
    <a href="admin_produtos.php">Voltar para a Lista de Produtos</a>
    
    <?php if ($erro): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php elseif ($sucesso): ?>
        <p style="color:green;"><?= $sucesso ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="titulo">Título do Prato:</label><br><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($produto['titulo']) ?>" required><br>

        <label for="descricao">Descrição:</label><br><br>
        <textarea name="descricao" rows="4" required><?= htmlspecialchars($produto['descricao']) ?></textarea><br>

        <label for="preco">Preço:</label><br><br>
        <input type="text" name="preco" value="<?= number_format($produto['preco'], 2, '.', '') ?>" required><br> 
        
        <label for="foto_path">Foto:</label>
        <input type="file" name="foto_path" placeholder="Ex: arquivos/foto.jpg" value="<?= htmlspecialchars($produto['foto']) ?>">

        <button type="submit">Atualizar Produto</button>
    </form>
</body>
</html>