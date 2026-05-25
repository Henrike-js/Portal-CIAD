<?php
$host = "localhost:3306";
$usuario = "root";        // altere se necessário
$senha = "Admin123";              // altere se necessário
$banco = "Banco_de_chamadas";     // nome do banco de dados

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}
?>