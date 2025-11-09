<?php
$senha_clara = '12345'; 

$hash_gerado = password_hash($senha_clara, PASSWORD_DEFAULT);

echo "<h1>Gere a Senha</h1>";
echo "<p>Senha em texto puro: <strong>" . $senha_clara . "</strong></p>";
echo "<p>HASH GERADO (Copie o valor abaixo):</p>";
echo "<textarea rows='3' cols='80'>" . $hash_gerado . "</textarea>";
?>