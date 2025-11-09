<?php
session_start();
include("conexao.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    
    $sql = "SELECT * FROM usuario WHERE email = ?"; 
    $stmt = $pdo->prepare($sql); 
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC); 
    if ($user) {
        if (password_verify($senha, $user["senha"])) {
            
            $_SESSION["usuario"] = $user["nome"];
            
            $_SESSION["tipo"] = $user["tipo"]; 
            
            header("Location: " . ($user["tipo"] == "admin" ? "dashboard.php" : "index.php"));
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Login - Valhala</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Login</h2>
<form method="POST">
<input type="email" name="email" placeholder="E-mail" required><br><br>
<input type="password" name="senha" placeholder="Senha" required><br><br>
<button type="submit">Entrar</button>
</form>
<p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
<p style="color:red;"><?= $erro ?? '' ?></p>
</body>
</html>