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
    
    $sql_delete = "DELETE FROM produto WHERE id = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    
    try {
        if ($stmt_delete->execute([$id_para_excluir])) {
            header("Location: admin_produtos.php?status=excluido");
            exit();
        } else {
            $mensagem_status = '<p style="color:red;">Erro ao excluir produto.</p>';
        }
    } catch (PDOException $e) {
        $mensagem_status = '<p style="color:red;">Erro no banco de dados: ' . $e->getMessage() . '</p>';
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'excluido') {
        $mensagem_status = '<p style="color:green;">Produto excluído com sucesso!</p>';
    } elseif ($_GET['status'] == 'produto_adicionado') {
        $mensagem_status = '<p style="color:green;">Novo produto adicionado com sucesso!</p>';
    }
    // Adicionar mais tarde: if ($_GET['status'] == 'editado')
}


// 3. CONSULTA PRINCIPAL (READ - LISTAGEM)
$sql_select = "SELECT id, titulo, descricao, preco, foto FROM produto ORDER BY id DESC";
$stmt_select = $pdo->query($sql_select);
$produtos = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos - Valhala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gerenciar Produtos/Serviços</h1>
    <p><a href="dashboard.php">Voltar para o Dashboard</a></p>

    <p><a href="adicionar_produto.php" class="btn-add">Adicionar Novo Produto</a></p>

    <?= $mensagem_status ?>

    <?php if (count($produtos) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td>
                        <?php if ($p['foto']): ?>
                            <img src="<?= htmlspecialchars($p['foto']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>" style="width: 50px; height: auto;">
                        <?php else: ?>
                            Sem Foto
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['titulo']) ?></td>
                    <td><?= htmlspecialchars(substr($p['descricao'], 0, 50)) ?>...</td> <td>R$<?= number_format($p['preco'], 2, ',', '.') ?></td>
                    <td>
                        <a href="editar_produto.php?id=<?= $p['id'] ?>">Editar</a>
                        |
                        <a href="?delete_id=<?= $p['id'] ?>" 
                           onclick="return confirm('Tem certeza que deseja excluir o produto: <?= htmlspecialchars($p['titulo']) ?>?');">
                           Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum produto cadastrado no cardápio. <a href="adicionar_produto.php">Adicionar agora.</a></p>
    <?php endif; ?>

</body>
</html>