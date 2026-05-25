<?php
include "../conexao.php";

$chamada_id = (int)$_POST['chamada_id'];

$stmt = $conexao->prepare("
UPDATE registros_chamadas SET
despachador_matricula = ?,
despachador_nome = ?,
data_despacho = ?,
hora_despacho = ?,
recurso = ?,
unidade = ?,
hora_despachada = ?,
hora_a_caminho = ?,
hora_no_local = ?,
encerramento = ?,
classificacao = ?,
observacao_classificacao = ?,
descricao_natureza_final = ?,
codigo_natureza_final = ?,
nr_pm = ?,
comentarios = ?
WHERE id = ?
");

$stmt->bind_param(
"ssssssssssssssssi",
$_POST['matricula'],
$_POST['nome'],
$_POST['data'],
$_POST['hora'],
$_POST['recurso'],
$_POST['unidade'],
$_POST['hora_despachada'],
$_POST['hora_a_caminho'],
$_POST['hora_no_local'],
$_POST['encerramento'],
$_POST['classificacao'],
$_POST['observacao_classificacao'],
$_POST['descricao_natureza_final'],
$_POST['codigo_natureza_final'],
$_POST['nr_pm'],
$_POST['comentarios'],
$chamada_id
);

$stmt->execute();

/* Atualizar status */
$status = empty($_POST['encerramento']) ? 'encaminhada' : 'encerrada';

$upd = $conexao->prepare("UPDATE registros_chamadas SET status=? WHERE id=?");
$upd->bind_param("si",$status,$chamada_id);
$upd->execute();

/* Redirecionar para tela de sucesso com ID */
header("Location: mensagem_finalizacao.php?chamada=".$chamada_id);
exit;
