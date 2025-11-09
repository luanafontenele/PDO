<?php
session_start();
include("conexao.php"); 

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$mensagem_status = '';

if (isset($_GET['delete_id'])) {
    $id_para_excluir = $_GET['delete_id'];
    
    if ($id_para_excluir == $_SESSION['user_id']) { 
        $mensagem_status = '<p style="color:red;">Erro: Você não pode excluir sua própria conta!</p>';
    } else {
        $sql_delete = "DELETE FROM usuario WHERE id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        
        try {
            if ($stmt_delete->execute([$id_para_excluir])) {
                header("Location: admin_usuarios.php?status=excluido");
                exit();
            } else {
                $mensagem_status = '<p style="color:red;">Erro ao excluir usuário.</p>';
            }
        } catch (PDOException $e) {
            $mensagem_status = '<p style="color:red;">Erro no banco de dados.</p>';
        }
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'excluido') {
        $mensagem_status = '<p style="color:green;">Usuário excluído com sucesso!</p>';
    } elseif ($_GET['status'] == 'editado') {
        $mensagem_status = '<p style="color:green;">Usuário editado com sucesso!</p>';
    }
}

$sql_select = "SELECT id, nome, email, tipo, foto FROM usuario ORDER BY id ASC";
$stmt_select = $pdo->query($sql_select);
$usuarios = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gerenciar Usuários</h1>
    <p><a href="dashboard.php">Voltar para o Dashboard</a></p>

    <p><a href="cadastro.php" class="btn-add">Adicionar Novo Usuário (Cliente)</a></p> 

    <?= $mensagem_status ?>

    <?php if (count($usuarios) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Nível</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td>
                        <?php if ($u['foto']): ?>
                            <img src="<?= htmlspecialchars($u['foto']) ?>" alt="Foto" style="width: 30px; border-radius: 50%;">
                        <?php else: ?>
                            
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['tipo']) ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $u['id'] ?>">Editar</a>
                        |
                        <a href="?delete_id=<?= $u['id'] ?>" 
                           onclick="return confirm('Excluir <?= htmlspecialchars($u['nome']) ?>?');">
                           Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum usuário cadastrado.</p>
    <?php endif; ?>

</body>
</html>