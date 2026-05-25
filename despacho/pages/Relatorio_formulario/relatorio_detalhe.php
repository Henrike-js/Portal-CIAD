
<?php
include "../conexao.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("Chamada inválida.");
}

$sql = "SELECT * FROM registros_chamadas WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Registro não encontrado.");
}

$d = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Relatório da Chamada Nº <?= (int)$d['id']; ?></title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:'Source Sans Pro',sans-serif;
    background:#F4F5F8;
    color:#16325C;
}

.page{max-width:1100px;margin:0 auto}

.topbar{
    background:#fff;
    border-bottom:6px solid #C63232;
}
.topbar-inner{
    max-width:1100px;
    margin:0 auto;
    padding:20px;
}
.logo-sisp-img{height:80px}

.page-header{
    margin:30px 0;
}
.page-header h1{
    font-family:'Montserrat',sans-serif;
    font-size:28px;
}
.page-header p{color:#555}

.card{
    background:#fff;
    border-radius:10px;
    margin-bottom:25px;
    box-shadow:0 2px 10px rgba(0,0,0,.06);
}
.card-body{
    padding:25px;
}
.card h3{
    font-family:'Montserrat',sans-serif;
    font-size:18px;
    margin-bottom:15px;
}

.card p{margin-bottom:8px}

.btn-despachar{
    background:#13294B;
    color:#fff;
    padding:12px 36px;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

.btn-despachar:hover{background:#0F1F3A}

.footer{
    margin:40px 0;
    font-size:13px;
    color:#666;
    text-align:center;
}
</style>
</head>

<body>

<div class="page">

<header class="topbar">
    <div class="topbar-inner">
        <img src="img/sisp-logo.png" class="logo-sisp-img" alt="SISP">
    </div>
</header>

<main>

<div class="page-header">
    <h1>RELATÓRIO DE CHAMADA</h1>
    <p>
        Nº <?= (int)$d['id']; ?> •
        <?= date('d/m/Y', strtotime($d['data_atendimento'])); ?>
    </p>
</div>

<!-- ================= TELEATENDENTE ================= -->
<div class="card">
<div class="card-body">
<h3>1. Dados do Teleatendente</h3>

<p><strong>Matrícula:</strong> <?= htmlspecialchars($d['matricula']); ?></p>
<p><strong>Nome:</strong> <?= htmlspecialchars($d['nome_teleatendente']); ?></p>
<p><strong>Data:</strong> <?= date('d/m/Y', strtotime($d['data_atendimento'])); ?></p>
<p><strong>Hora:</strong> <?= htmlspecialchars($d['hora_atendimento']); ?></p>

</div>
</div>

<!-- ================= LOCAL ================= -->
<div class="card">
<div class="card-body">
<h3>2. Local da Chamada</h3>

<p><strong>Destino do Serviço:</strong> <?= htmlspecialchars($d['destino_servico']); ?></p>

<p>
<strong>Endereço:</strong>
<?= htmlspecialchars($d['logradouro_chamada']); ?>,
Nº <?= htmlspecialchars($d['numero_chamada']); ?>
</p>

<p><strong>Complemento:</strong> <?= htmlspecialchars($d['complemento_chamada']); ?></p>
<p><strong>Bairro:</strong> <?= htmlspecialchars($d['bairro_chamada']); ?></p>
<p><strong>Município:</strong> <?= htmlspecialchars($d['municipio_chamada']); ?></p>
<p><strong>Telefone:</strong> <?= htmlspecialchars($d['telefone_chamada']); ?></p>
</div>
</div>

<!-- ================= SOLICITANTE ================= -->
<div class="card">
<div class="card-body">
<h3>3. Dados do Solicitante</h3>

<p><strong>Nome:</strong> <?= htmlspecialchars($d['nome_solicitante']); ?></p>

<p>
<strong>Endereço:</strong>
<?= htmlspecialchars($d['endereco_solicitante']); ?>,
Nº <?= htmlspecialchars($d['numero_solicitante']); ?>
</p>

<p><strong>Complemento:</strong> <?= htmlspecialchars($d['complemento_solicitante']); ?></p>
<p><strong>Bairro:</strong> <?= htmlspecialchars($d['bairro_solicitante']); ?></p>
<p><strong>Município:</strong> <?= htmlspecialchars($d['municipio_solicitante']); ?></p>
<p><strong>Telefone:</strong> <?= htmlspecialchars($d['telefone_solicitante']); ?></p>
</div>
</div>

<!-- ================= NATUREZA ================= -->
<div class="card">
<div class="card-body">
<h3>4. Natureza da Ocorrência</h3>

<p><strong>Código:</strong> <?= htmlspecialchars($d['codigo_natureza']); ?></p>

<p><strong>Descrição:</strong><br>
<?= nl2br(htmlspecialchars($d['descricao_natureza'])); ?>
</p>
</div>
</div>

<!-- ================= BOTÃO DESPACHAR ================= -->
<div style="margin-top:30px; display:flex; justify-content:flex-start;">
    <a href="Despachador.php?chamada=<?= (int)$_GET['id']; ?>" class="btn-despachar">
       🚓 Despachar ocorrência
    </a>
</div>

</main>

<footer class="footer">
Relatório da Chamada Nº <?= (int)$d['id']; ?> •
Gerado em <?= date('d/m/Y H:i'); ?>
</footer>

</div>

</body>
</html>

