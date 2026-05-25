<?php

$host   = "localhost:3306";
$user   = "root";
$pass   = "Admin123";
$dbname = "Banco_de_chamadas";


$conn = new mysqli($host, $user , $pass, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>