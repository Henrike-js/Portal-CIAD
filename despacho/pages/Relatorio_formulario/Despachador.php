<?php
include "../conexao.php";

if (!isset($conexao)) {
    die("Erro ao carregar conexão com o banco.");
}

$chamada_id = filter_input(INPUT_GET, 'chamada', FILTER_VALIDATE_INT);
if (!$chamada_id) {
    die("Chamada inválida.");
}

$sql = "SELECT * FROM registros_chamadas WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $chamada_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Registro não encontrado.");
}

$dados_chamada = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Despacho da Chamada Nº <?= (int)$dados_chamada['id']; ?></title>

<link href="https://fonts.cdnfonts.com/css/rawline" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Rawline',sans-serif}
body{background:#F4F5F8;color:#16325C}
.page{max-width:1100px;margin:40px auto;padding:0 40px}

h1{font-size:28px}
.sub{margin:10px 0 25px;color:#555}

.card{
    background:#fff;
    padding:25px;
    border-radius:10px;
    margin-bottom:25px;
    box-shadow:0 2px 10px rgba(0,0,0,.06);
}

label{font-weight:600;margin-bottom:5px;display:block}
input,select,textarea{
    width:100%;
    padding:10px;
    border:1px solid #D0D2D6;
    border-radius:6px;
}
textarea{resize:vertical}
input[readonly],textarea[readonly]{background:#F4F5F8}

.row{display:flex;gap:16px;margin-bottom:16px}
.w-25{flex:25%}
.w-33{flex:33%}
.w-50{flex:50%}
.w-100{flex:100%}

.classificacao-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px 20px;
}
.classificacao-grid label{
    display:flex;
    align-items:center;
    gap:8px;
    font-weight:400;
}

input[type="radio"]{width:auto}

button{
    background:#13294B;
    color:#fff;
    padding:14px 32px;
    border:0;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}
button:hover{background:#0F1F3A}
</style>
</head>

<body>

<div class="page">

<h1>Despacho da Chamada</h1>
<p class="sub">
Chamada Nº <?= (int)$dados_chamada['id']; ?> •
<?= date('d/m/Y', strtotime($dados_chamada['data_atendimento'])); ?>
</p>

<!-- ================= RESUMO DA CHAMADA ================= -->
<div class="card">
<h3>Resumo da Chamada</h3>

<div class="row">
    <div class="w-50">
        <label>Solicitante</label>
        <input value="<?= htmlspecialchars($dados_chamada['nome_solicitante']); ?>" readonly>
    </div>
    <div class="w-50">
        <label>Telefone</label>
        <input value="<?= htmlspecialchars($dados_chamada['telefone_chamada']); ?>" readonly>
    </div>
</div>

<div class="row">
    <div class="w-100">
        <label>Endereço</label>
        <input value="<?= htmlspecialchars($dados_chamada['logradouro_chamada']); ?>, Nº <?= htmlspecialchars($dados_chamada['numero_chamada']); ?> - <?= htmlspecialchars($dados_chamada['bairro_chamada']); ?>" readonly>
    </div>
</div>

<div class="row">
    <div class="w-100">
        <label>Historico</label>
        <textarea rows="3" readonly><?= htmlspecialchars($dados_chamada['historico']); ?></textarea>
    </div>
</div>
</div>

<form action="salvar_despachador.php" method="post">

<input type="hidden" name="chamada_id" value="<?= (int)$dados_chamada['id']; ?>">

<div class="row">
    <div class="w-100">
        <label for="status">Status da Chamada</label>
        <select name="status" id="status" class="form-control">
            <option value="aberto" <?= ($dados_chamada['status'] == 'aberto') ? 'selected' : '' ?>>Aberto</option>
            <option value="encaminhada" <?= ($dados_chamada['status'] == 'encaminhada') ? 'selected' : '' ?>>Encaminhada</option>
            <option value="fechada" <?= ($dados_chamada['status'] == 'fechada') ? 'selected' : '' ?>>Fechada</option>
        </select>
    </div>
</div>

<!-- ================= DADOS DO DESPACHADOR ================= -->
<div class="card">
<h3>Dados do Despachador</h3>

<div class="row">
    <div class="w-33"><label>Matrícula</label>
        <input name="matricula" required value="<?= htmlspecialchars($dados_chamada['despachador_matricula'] ?? '') ?>">
    </div>
    <div class="w-50"><label>Nome</label>
        <input name="nome" required value="<?= htmlspecialchars($dados_chamada['despachador_nome'] ?? '') ?>">
    </div>
</div>

<div class="row">
    <div class="w-25"><label>Data</label>
        <input type="date" name="data" required value="<?= $dados_chamada['data_despacho'] ?>">
    </div>
    <div class="w-25"><label>Hora</label>
        <input type="time" name="hora" required value="<?= $dados_chamada['hora_despacho'] ?>">
    </div>
</div>
</div>

<!-- ================= STATUS DO RECURSO ================= -->
<div class="card">
<h3>Status do Recurso</h3>

<div class="row">
    <div class="w-33"><label>Recurso</label>
        <input name="recurso" value="<?= htmlspecialchars($dados_chamada['recurso']) ?>">
    </div>
    <div class="w-33"><label>Unidade</label>
        <input name="unidade" value="<?= htmlspecialchars($dados_chamada['unidade']) ?>">
    </div>
</div>

<div class="row">
    <div class="w-25"><label>Despachada</label>
        <input type="time" name="hora_despachada" value="<?= $dados_chamada['hora_despachada'] ?>">
    </div>
    <div class="w-25"><label>A caminho</label>
        <input type="time" name="hora_a_caminho" value="<?= $dados_chamada['hora_a_caminho'] ?>">
    </div>
    <div class="w-25"><label>No local</label>
        <input type="time" name="hora_no_local" value="<?= $dados_chamada['hora_no_local'] ?>">
    </div>
</div>

<div class="row">
    <div class="w-33">
        <label>Encerramento</label>
        <select name="encerramento">
            <option value="">Selecione</option>
            <?php
            foreach(['Terminado','Suspenso','Disponível','Indisponível'] as $op){
                $sel = ($op == $dados_chamada['encerramento']) ? "selected" : "";
                echo "<option $sel>$op</option>";
            }
            ?>
        </select>
    </div>
</div>
</div>

<!-- ================= CLASSIFICAÇÃO ================= -->
<div class="card">
<h3>Classificação da Chamada</h3>

<div class="classificacao-grid">
<?php
$opcoes = [
"Transmitido a rede","Trote W07.000","Emissão de multa",
"Solicitante não encontrado W01.000","Relatório",
"Cancelada pela coordenação","Boletim de ocorrência",
"Nada constatado W04.000","Atendimento sem BO",
"Dispensado pelo solicitante","Endereço não localizado W02.000",
"Cancelada indisp meios W08.000","Duplicata","Outros"
];
foreach($opcoes as $op){
    $sel = ($op == $dados_chamada['classificacao']) ? "checked" : "";
    echo "<label><input type='radio' name='classificacao' value='$op' $sel> $op</label>";
}
?>
</div>

<div class="row" style="margin-top:15px">
    <div class="w-50">
        <label>Observação / Nº da chamada</label>
        <input name="observacao_classificacao" value="<?= htmlspecialchars($dados_chamada['observacao_classificacao']) ?>">
    </div>
</div>
</div>

<!-- ================= NATUREZA FINAL ================= -->
<div class="card">
<h3>Natureza Final</h3>

<div class="row">
    <div class="w-100">
        <label class="form-control">
            <?= nl2br(htmlspecialchars($dados_chamada['descricao_natureza_final'])) ?>
        </label>
    </div>
</div>

<div class="row">
    <div class="w-33"><label>Código Natureza Final</label>
        <input name="codigo_natureza_final" value="<?= htmlspecialchars($dados_chamada['codigo_natureza_final']) ?>">
    </div>
    <div class="w-33"><label>Número da Chamada</label>
        <input value="<?= $dados_chamada['id'] ?>" readonly>
    </div>
    <div class="w-33"><label>NR PM</label>
        <input name="nr_pm" value="<?= htmlspecialchars($dados_chamada['nr_pm']) ?>">
    </div>
</div>

<div class="row">
    <div class="w-100">
        <label>Comentários</label>
        <textarea name="comentarios"><?= htmlspecialchars($dados_chamada['comentarios']) ?></textarea>
    </div>
</div>
</div>

<button type="submit">Salvar Despacho</button>

</form>

</div>

</body>
</html>



