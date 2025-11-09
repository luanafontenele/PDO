CREATE DATABASE IF NOT EXISTS valhala_db;
USE valhala_db;

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    foto VARCHAR(255),

    tipo ENUM('admin','usuario') NOT NULL DEFAULT 'usuario'
);


INSERT INTO usuario (nome, email, senha, tipo, foto) VALUES
('Ellen', 'ellenadmin@gmail.com', '$2y$10$OiacEBNxZWy6VnBJsdYp2OGlI7OAYx4CO3j/HrUu2qHTRtN8k5Rwy', 'admin', 'imagens/logo.jpg');

INSERT INTO usuario (nome, email, senha, tipo, foto) VALUES
('Luana', 'luana@gmail.com', '$2y$10$THtaDPYzzauYv.q20ZQuB.m75tAc2xCiIHn156EL8oYuACe4XCN.q', 'usuario', 'images/users/icone.jpg');


CREATE TABLE IF NOT EXISTS produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL, 
    foto VARCHAR(255)
);

INSERT INTO produto (titulo, descricao, preco, foto) VALUES
('Cavalo de Tróia Burguer', 'O mito do sabor em um pão.', 45.00, 'arquivos/arquivos/Image (3).jpg'),
('Néctar de Zeus', 'A bebida dos Deuses, refrescante e divina.', 22.00, NULL),
('Costelas Vikings', 'Carne saborosa para um verdadeiro guerreiro.', 60.00, 'arquivos/arquivos/Image (15).jpg');


CREATE TABLE IF NOT EXISTS avaliacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    estrelas INT(11) NOT NULL, 
    comentario TEXT NOT NULL
);

INSERT INTO avaliacao (nome, estrelas, comentario) VALUES
('Cliente Satisfeito', 5, 'As Costelas Vikings me levaram a Valhala!'),
('Mito da Comida', 4, 'O Néctar de Zeus é realmente delicioso, mas o burguer é pequeno.');

CREATE TABLE IF NOT EXISTS contato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);