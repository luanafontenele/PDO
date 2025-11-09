<?php 
session_start();
include("conexao.php"); 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante Valhala</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
</head>
<body>

    <header>
    <h1>Restaurante Valhala</h1>

        <nav>
            <ul>
                <li><a href="#inicio">Início</a></li>
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="#produtos">Produtos</a></li> <li><a href="#avaliacoes">Avaliações</a></li> <li><a href="#contato">Contato</a></li>                 <?php if (isset($_SESSION['usuario'])): ?>
                    <li><span>Olá, <?= htmlspecialchars($_SESSION['usuario']); ?>!</span></li>
                    <li><a href="logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section id="inicio" class="carrossel">
    <h2>Cardápio Mitológico</h2>
    <div class="slides">
        <img src="Imagens/hamburguer.jpg" alt="Cavalo de Tróia Burguer" class="imagens">
        <img src="Imagens/taco.jpg" alt="Tacos da Llorona" class="imagens">
        <img src="Imagens/feijão.jpg" alt="Risoto das fadas" class="imagens">
        <img src="Imagens/carne.jpg" alt="Costelas Vikings" class="imagens">
    </div>

    </section>

    <section id="sobre">
        <h2>Sobre o Restaurante (Quem Somos)</h2>
        <p>O <strong>Valhala</strong> une culinária, cultura e mitologia em um só lugar. 
        Cada sala representa uma lenda de um país: Grécia, México, França e Noruega. 
        Os clientes vivem uma experiência imersiva com pratos e bebidas temáticas.</p>
    </section>
    
    <section id="produtos">
        <h2>Diferencial</h2>
        <ul>
            <li>Gastronomia imersiva e temática</li>
            <li>Garçons caracterizados de deuses e criaturas mitológicas</li>
            <li>Decoração inspirada nas culturas de cada região</li>
            <li>Localização em <strong>Praia Grande - SP</strong>, polo turístico</li>
        </ul>

       <div class="cardapio">
            <h3>Cardápio (Produtos/Serviços Dinâmico)</h3>
            <table>
            <tr><th>Prato</th><th>Preço</th><th>Ação</th></tr> 
                <?php
                $sql_produtos = "SELECT id, titulo, preco FROM produto"; 
                $stmt_produtos = $pdo->query($sql_produtos); 
                
                if ($stmt_produtos->rowCount() > 0) {
                    while($row = $stmt_produtos->fetch(PDO::FETCH_ASSOC)) { 
                        echo "<tr>"; 
                        
                        echo "<td>" . htmlspecialchars($row['titulo']) . "</td>"; 
                        
                        echo "<td>R$" . number_format($row['preco'], 2, ',', '.') . "</td>"; 
                        
                        echo "<td>"; 
                        if (isset($_SESSION['usuario'])) {
                            echo '<a href="checkout.php?produto_id=' . $row['id'] . '">Comprar</a>';
                        } else {
                            echo '<a href="login.php">Logar para Comprar</a>';
                        }
                        echo "</td>";
                        
                        echo "</tr>"; 
                    }
                } else {
                    echo "<tr><td colspan='3'>Nenhum prato encontrado no cardápio.</td></tr>"; 
                }
                ?>
            </table>
        </div>
    </section>
    
    <section id="avaliacoes">
        <h2>O que nossos clientes dizem (Avaliações Dinâmicas)</h2>
        <?php
       $sql_aval = "SELECT nome, estrelas, comentario FROM avaliacao ORDER BY id DESC";
        $stmt_aval = $pdo->query($sql_aval); 

        if ($stmt_aval->rowCount() > 0) {
            while($row = $stmt_aval->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='avaliacao-card'>";
                echo "<h4>" . htmlspecialchars($row['nome']) . "</h4>";
                echo "<p>" . str_repeat('⭐', $row['estrelas']) . "</p>"; // Gera as estrelas
                echo "<p>" . htmlspecialchars($row['comentario']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Seja o primeiro a avaliar!</p>";
        }
        ?>

        <hr>
        
        <?php if (isset($_SESSION['usuario'])): ?>
            <p>
                <a href="create_avaliacao.php">Deixe sua própria avaliação!</a>
            </p>
        <?php else: ?>
            <p>
                <a href="login.php">Faça login</a> para deixar sua avaliação.
            </p>
        <?php endif; ?>
    </section>

    <section id="contato"> <h2>Fale Conosco</h2>
        <p><a href="contato.php">Clique aqui para enviar uma mensagem.</a></p>
    </section>

    <footer>
        <p>© 2025 Restaurante Valhala - Inspirado nas mitologias do mundo</p>
    </footer>

</body>
</html>