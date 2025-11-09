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
    
    $sql_delete = "DELETE FROM avaliacao WHERE id = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    
    try {
        if ($stmt_delete->execute([$id_para_excluir])) {
            $mensagem_status = '<p style="color:green;">Avaliação excluída com sucesso!</p>';
        } else {
            $mensagem_status = '<p style="color:red;">Erro ao excluir avaliação.</p>';
        }
    } catch (PDOException $e) {
        $mensagem_status = '<p style="color:red;">Erro no banco de dados: ' . $e->getMessage() . '</p>';
    }
}

$sql_select = "SELECT id, nome, estrelas, comentario FROM avaliacao ORDER BY id DESC";
$stmt_select = $pdo->query($sql_select);
$avaliacoes = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Avaliações - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gerenciar Avaliações de Clientes</h1>
    <p><a href="dashboard.php">Voltar para o Dashboard</a></p>

    <?= $mensagem_status ?>

    <?php if (count($avaliacoes) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Estrelas</th>
                    <th>Comentário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avaliacoes as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['id']) ?></td>
                    <td><?= htmlspecialchars($a['nome']) ?></td>
                    <td><?= str_repeat('⭐', $a['estrelas']) ?></td>
                    <td><?= htmlspecialchars($a['comentario']) ?></td>
                    <td>
                        <a href="?delete_id=<?= $a['id'] ?>" 
                           onclick="return confirm('Tem certeza que deseja excluir esta avaliação?');">
                           Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma avaliação cadastrada ainda.</p>
    <?php endif; ?>

</body>
</html>