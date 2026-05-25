<?php
include __DIR__ . "/../../conexao.php";

// 1. Pega o ID da URL e valida se é um número
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("ID inválido ou não fornecido.");
}

// 2. Consulta ao banco de dados
$sql = "SELECT * FROM registros_chamadas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$registro = $result->fetch_assoc();

if (!$registro) {
    die("Registro não localizado no banco de dados.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Chamada #<?= $registro['id'] ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7f6; padding: 20px; color: #333; }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header-flex { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; margin-bottom: 20px; padding-bottom: 10px; }
        h2 { margin: 0; color: #2c3e50; }
        .section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            background: #fafbfc;
        }
        .section h3 {
            margin: 0 0 12px 0;
            font-size: 18px;
            color: #2f5dff;
            border-bottom: 1px solid #e1e8ed;
            padding-bottom: 5px;
        }
        .row { margin-bottom: 8px; line-height: 1.5; }
        .label { font-weight: bold; color: #555; width: 150px; display: inline-block; }
        pre {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: inherit;
            color: #444;
        }
        .btn-voltar {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #2f5dff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .btn-voltar:hover { background: #1e40af; }
        .badge { background: #e2e8f0; padding: 4px 8px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h2>Chamada #<?= $registro['id'] ?></h2>
        <span class="badge">SISP - CIAD</span>
    </div>

    <div class="section">
        <h3>Dados do Atendimento</h3>
        <div class="row"><span class="label">Matrícula:</span> <?= htmlspecialchars($registro['matricula']) ?></div>
        <div class="row"><span class="label">Nome Atendente:</span> <?= htmlspecialchars($registro['nome_teleatendente']) ?></div>
        <div class="row">
            <span class="label">Data / Hora:</span> 
            <?= date('d/m/Y', strtotime($registro['data_atendimento'])) ?> às 
            <?= substr($registro['hora_atendimento'], 0, 5) ?>h
        </div>
    </div>

    <div class="section">
        <h3>Destino e Local da Chamada</h3>
        <div class="row"><span class="label">Serviço:</span> <strong><?= htmlspecialchars($registro['destino_servico']) ?></strong></div>
        <div class="row">
            <span class="label">Endereço:</span>
            <?= htmlspecialchars($registro['logradouro_chamada']) ?><?= $registro['numero_chamada'] ? ', ' . htmlspecialchars($registro['numero_chamada']) : ' S/N' ?>
            <br><span class="label"></span><?= htmlspecialchars($registro['bairro_chamada']) ?> - <?= htmlspecialchars($registro['municipio_chamada']) ?>
            <?php if(!empty($registro['complemento_chamada'])): ?>
                <br><span class="label">Complemento:</span> <?= htmlspecialchars($registro['complemento_chamada']) ?>
            <?php endif; ?>
        </div>
        <div class="row"><span class="label">Telefone Local:</span> <?= htmlspecialchars($registro['telefone_chamada']) ?></div>
    </div>

    <div class="section">
        <h3>Dados do Solicitante</h3>
        <div class="row"><span class="label">Nome:</span> <?= htmlspecialchars($registro['nome_solicitante']) ?></div>
        <div class="row"><span class="label">Telefone:</span> <?= htmlspecialchars($registro['telefone_solicitante']) ?></div>
        <div class="row">
            <span class="label">Endereço:</span>
            <?= htmlspecialchars($registro['endereco_solicitante'] ?: 'Não informado') ?>
        </div>
    </div>

    <div class="section">
        <h3>Histórico / Natureza</h3>
        <div class="row"><span class="label">Código Natureza:</span> <span class="badge"><?= htmlspecialchars($registro['codigo_natureza']) ?></span></div>
        <p class="label" style="margin-top:10px;">Relato do Atendimento:</p>
        <pre><?= htmlspecialchars($registro['historico']) ?></pre>
    </div>

    <a href="lista_chamadas.php" class="btn-voltar">⬅ Voltar para a Lista</a>
</div>

</body>
</html>
<?php
$conn->close();
?>
