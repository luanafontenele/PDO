<?php
session_start();
include("conexao.php"); 

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$erro = '';
$sucesso = '';

$id_usuario = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_usuario) {
    header("Location: admin_usuarios.php");
    exit;
}

$sql_select = "SELECT id, nome, email, tipo FROM usuario WHERE id = ?";
$stmt_select = $pdo->prepare($sql_select);
$stmt_select->execute([$id_usuario]);
$usuario = $stmt_select->fetch(PDO::FETCH_ASSOC);


if (!$usuario) {
    die("Usuário não encontrado!");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($nome && $email && ($tipo === 'admin' || $tipo === 'usuario')) {
        
      
        $sql_update = "UPDATE usuario SET nome = ?, email = ?, tipo = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        
        try {
            $stmt_update->execute([$nome, $email, $tipo, $id_usuario]);
            
           
            header("Location: admin_usuarios.php?status=editado");
            exit();

        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                 $erro = "Erro: Este e-mail já pertence a outra conta.";
            } else {
                 $erro = "Erro ao atualizar usuário: " . $e->getMessage();
            }
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
    <title>Editar Usuário - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Editar Usuário: <?= htmlspecialchars($usuario['nome']) ?></h1>
    <a href="admin_usuarios.php">Voltar para a Lista de Usuários</a>
    
    <?php if ($erro): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nome">Nome:</label><br><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required><br>

        <label for="email">E-mail:</label><br><br>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br>
        
        <label for="tipo">Nível de Acesso:</label><br><br>
        <select name="tipo" required>
            <option value="usuario" <?= ($usuario['tipo'] == 'usuario') ? 'selected' : '' ?>>Usuário (Cliente)</option>
            <option value="admin" <?= ($usuario['tipo'] == 'admin') ? 'selected' : '' ?>>Admin (Gerente)</option>
        </select>
        
        <p style="margin-top: 20px; color: #816c6cff;">* A senha não é editada nesta tela por segurança.</p>

        <button type="submit">Atualizar Usuário</button>
    </form>
</body>
</html>