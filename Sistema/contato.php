<?php
include("conexao.php"); 

$mensagem_status = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($nome && $email && $mensagem) {
        $sql = "INSERT INTO contato (nome, email, mensagem) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$nome, $email, $mensagem]);
            
            $mensagem_status = '<p style="color:green;">Mensagem enviada com sucesso! Logo entraremos em contato.</p>';

        } catch (PDOException $e) {
            $mensagem_status = '<p style="color:red;">Erro ao enviar mensagem. Tente novamente.</p>';
        }

    } else {
        $mensagem_status = '<p style="color:red;">Por favor, preencha todos os campos corretamente.</p>';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Fale Conosco</h1>
    <a href="index.php">Voltar para a Home</a>

    <?= $mensagem_status ?>

    <section id="contato">
        <form action="contato.php" method="POST">
            <input type="text" name="nome" placeholder="Seu nome" required><br>
            <input type="email" name="email" placeholder="Seu e-mail" required><br>
            <textarea name="mensagem" placeholder="Sua mensagem" required></textarea><br>
            <button type="submit">Enviar Mensagem</button>
        </form>
    </section>

</body>
</html>