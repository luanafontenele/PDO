<?php
session_start();
include("conexao.php"); 

if (!isset($_SESSION['usuario'])) { 
    header("Location: login.php");
    exit();
}

$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome_avaliador = $_SESSION['usuario']; 
    $estrelas = filter_input(INPUT_POST, 'estrelas', FILTER_VALIDATE_INT);
    $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($estrelas >= 1 && $estrelas <= 5 && $comentario) {
        
        $sql = "INSERT INTO avaliacao (nome, estrelas, comentario) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$nome_avaliador, $estrelas, $comentario]);
            
            $sucesso = "Sua avaliação foi enviada com sucesso!";

        } catch (PDOException $e) {
            $erro = "Erro ao enviar avaliação: Tente novamente.";
        }
    } else {
        $erro = "Por favor, selecione de 1 a 5 estrelas e escreva um comentário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Avaliar Produto - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Avaliar o Serviço/Produto</h1>
    <p>Obrigado por nos avaliar, <?= htmlspecialchars($_SESSION['usuario']); ?>!</p>
    <a href="index.php">Voltar para a Home</a>

    <?php if ($sucesso): ?>
        <p style="color:green;"><?= $sucesso ?></p>
    <?php elseif ($erro): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="estrelas">Estrelas (1 a 5):</label>
        <input type="number" name="estrelas" min="1" max="5" required><br><br>

        <label for="comentario">Comentário:</label>
        <textarea name="comentario" rows="4" required></textarea><br><br>

        <button type="submit">Enviar Avaliação</button>
    </form>
</body>
</html>