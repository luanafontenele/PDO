<?php
session_start();
include("conexao.php"); 

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $preco_limpo = str_replace(['R$', ',', ' '], ['', '.', ''], $_POST['preco']);
    $preco = filter_var($preco_limpo, FILTER_VALIDATE_FLOAT);
    
    $foto_path = filter_input(INPUT_POST, 'foto_path', FILTER_SANITIZE_URL);

    if ($titulo && $descricao && $preco !== false) {
        
        $sql = "INSERT INTO produto (titulo, descricao, preco, foto) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$titulo, $descricao, $preco, $foto_path]);
            
            header("Location: dashboard.php?status=produto_adicionado");
            exit();

        } catch (PDOException $e) {
            $erro = "Erro ao adicionar produto: " . $e->getMessage();
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
    <title>Adicionar Produto - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Adicionar Novo Prato ao Cardápio</h1>
    <a href="dashboard.php">Voltar para o Dashboard</a>
    
    <?php if (isset($erro)): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="titulo">Título do Prato:</label><br><br>
        <input type="text" name="titulo" required><br>

        <label for="descricao">Descrição:</label><br><br>
        <textarea name="descricao" rows="4" required></textarea><br>

        <label for="preco">Preço:</label><br><br>
        <input type="text" name="preco" required> <br>
        
        <label for="foto_path">Foto:</label><br><br>
        <input type="file" name="foto_path" placeholder="Ex: arquivos/foto.jpg"><br>

        <button type="submit">Adicionar Prato</button>
    </form>
</body>
</html>