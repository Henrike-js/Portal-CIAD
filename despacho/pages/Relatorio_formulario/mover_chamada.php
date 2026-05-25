<?php
include "conexao.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = (int)$data['id'];
$batalhao = $data['batalhao'];

$stmt = $conexao->prepare("UPDATE registros_chamadas SET batalhao=? WHERE id=?");
$stmt->bind_param("si", $batalhao, $id);
$stmt->execute();
