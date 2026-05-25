<?php
// ===== CONFIGURAÇÃO DO BANCO =====


$host   = 
$user   = 
$pass   = 
$dbname = 


$conn = new mysqli($host, $user , $pass, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>