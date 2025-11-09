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
    
    $sql_delete = "DELETE FROM contato WHERE id = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    
    try {
        if ($stmt_delete->execute([$id_para_excluir])) {
            header("Location: admin_contatos.php?status=excluido");
            exit();
        } else {
            $mensagem_status = '<p style="color:red;">Erro ao excluir mensagem.</p>';
        }
    } catch (PDOException $e) {
        $mensagem_status = '<p style="color:red;">Erro no banco de dados: ' . $e->getMessage() . '</p>';
    }
}

if (isset($_GET['status']) && $_GET['status'] == 'excluido') {
    $mensagem_status = '<p style="color:green;">Mensagem excluída com sucesso!</p>';
}

$sql_select = "SELECT id, nome, email, mensagem, data_envio FROM contato ORDER BY data_envio DESC";
$stmt_select = $pdo->query($sql_select);
$mensagens = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Contatos - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gerenciar Mensagens de Contato</h1>
    <p><a href="dashboard.php">Voltar para o Dashboard</a></p>

    <?= $mensagem_status ?>

    <?php if (count($mensagens) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Mensagem</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensagens as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['id']) ?></td>
                    <td><?= htmlspecialchars($m['nome']) ?></td>
                    <td><?= htmlspecialchars($m['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($m['mensagem'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($m['data_envio'])) ?></td>
                    <td>
                        <a href="?delete_id=<?= $m['id'] ?>" 
                           onclick="return confirm('Tem certeza que deseja excluir esta mensagem?');">
                           Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma mensagem de contato recebida ainda.</p>
    <?php endif; ?>

</body>
</html>