<?php
$termo = strtolower($_GET['q'] ?? '');

$arquivo = fopen('data.csv', 'r');

$resultado = [];

while (($linha = fgetcsv($arquivo, 1000, ",")) !== FALSE) {

    $matricula = strtolower($linha[0]);
    $nome = strtolower($linha[1]);

    if ($termo !== '' && (str_contains($matricula, $termo) || str_contains($nome, $termo))) {
        $resultado[] = [
            "matricula" => $linha[0],
            "nome"      => $linha[1]
        ];
    }
}

fclose($arquivo);

header('Content-Type: application/json');
echo json_encode($resultado);
?>