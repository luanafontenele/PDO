<?php
session_start();

include("conexao.php");

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$nome_usuario = htmlspecialchars($_SESSION['usuario']);
$tipo_usuario = htmlspecialchars($_SESSION['tipo']); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Dashboard - Valhala</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <header style="background-color: #4B2E05; padding: 20px;">
        <h1 style="color: #C8A87A; margin: 0;">Restaurante Valhala</h1>
        <div class="header-info">
            <span><?= $nome_usuario ?> (<?= $tipo_usuario ?>)</span> 
            <a href="logout.php" style="color: #fff; text-decoration: none;">Sair</a>
        </div>
    </header>

    <h1 style="text-align: center; margin-top: 40px;">Dashboard do Administrador</h1>
      <center>
    <div class="dashboard-grid">
        
        <div class="card">
            <h2>Avaliações</h2>
            <p>Gerencie feedback dos clientes</p>
            <a href="admin_avaliacoes.php">Acessar</a>
        </div>
        
        <div class="card">
            <h2>Serviços/Produtos</h2>
            <p>Gerencie o cardápio e estoque</p>
            <a href="admin_produtos.php">Acessar</a>
        </div>

      

        <div class="card">
            <h2>Contato</h2>
            <p>Visualize e exclua mensagens</p>
            <a href="admin_contatos.php">Acessar</a>
        </div>

        <div class="card">
            <h2>Usuários</h2>
            <p>Cadastre, edite e exclua contas</p>
            <a href="admin_usuarios.php">Acessar</a>
        </div>

    </div>
</center>
    </body>
</html>