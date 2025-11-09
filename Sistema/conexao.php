<?php

$host = 'localhost';
$banco = 'valhala_db'; 
$usuario = 'root';
$senha = ''; 

try{

    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e){
    die("Erro na conexão: " . $e->getMessage());
}
?>