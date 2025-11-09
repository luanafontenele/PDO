<?php
session_start();
include("conexao.php"); 

// if (isset($_SESSION['usuario'])) {
//     header("Location: index.php");
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha_clara = $_POST['senha'] ?? ''; 
    
    if ($nome && $email && strlen($senha_clara) >= 5) { 
        
        $senha_hash = password_hash($senha_clara, PASSWORD_DEFAULT);
        
        $tipo = 'usuario';
        
        $sql = "INSERT INTO usuario (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
           
            $stmt->execute([$nome, $email, $senha_hash, $tipo]);
            
           
            header("Location: login.php?status=cadastro_sucesso");
            exit();

        } catch (PDOException $e) {
           
            if ($e->getCode() == '23000') {
                $erro = "Este e-mail já está cadastrado.";
            } else {
                $erro = "Erro ao cadastrar: Tente novamente.";
            }
        }
    } else {
        $erro = "Por favor, preencha todos os campos e use uma senha com no mínimo 5 caracteres.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Cadastro de Cliente</h2>
    
    <?php if (isset($erro)): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <label for="nome">Nome Completo:</label><br><br>
        <input type="text" name="nome" required><br>
        
        <label for="email">E-mail:</label><br><br>
        <input type="email" name="email" required><br>
        
        <label for="senha">Senha:</label><br><br>
        <input type="password" name="senha" required minlength="5"><br>
        
        <button type="submit">Cadastrar</button>
    </form>
    <p>Já tem conta? <a href="login.php">Faça login</a></p>
</body>
</html>