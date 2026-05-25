<?php
require "conexao.php";

$busca = isset($_GET['q']) ? trim($_GET['q']) : "";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Chamadas Registradas</title>

<link rel="stylesheet" href="chamadas.css">

<style>
body{
  background:#f6f7fb;
}

.page{
  max-width:1200px;
  margin:auto;
  padding:20px;
}

h1{
  margin-bottom:4px;
}

p{
  color:#6b7280;
  margin-bottom:20px;
}

.search-box {
  margin: 10px 0 20px 0;
  display: flex;
  gap: 10px;
}

.search-box input{
  width: 100%;
  padding: 12px 14px;
  border-radius: 12px;
  border: 1px solid #d1d5db;
  font-size: 14px;
}

.search-box button{
  padding: 12px 22px;
  border-radius: 12px;
  border: none;
  background: #2f5dff;
  color: white;
  font-weight: 700;
  cursor: pointer;
}

.search-box button:hover{
  background:#254bdd;
}

.table-wrapper {
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
  overflow: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: #f3f4f6;
}

th, td {
  padding: 14px 12px;
  border-bottom: 1px solid #e5e7eb;
  font-size: 14px;
}

th {
  text-align: left;
  color: #374151;
  font-weight: 700;
}

tr.row-link {
  cursor: pointer;
}

tr.row-link:hover td {
  background:#eef2ff;
}

/* Status pill */
.status-pill{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:6px 12px;
  border-radius:20px;
  font-weight:700;
  font-size:12px;
}

.status-dot{
  width:10px;
  height:10px;
  border-radius:50%;
}

/* cores */
.status-aberta{
  background:#fdecea;
  color:#c62828;
}
.status-aberta .status-dot{background:#e53935;}

.status-encaminhada{
  background:#e8f5e9;
  color:#2e7d32;
}
.status-encaminhada .status-dot{background:#43a047;}

.status-fechada{
  background:#eeeeee;
  color:#212121;
}
.status-fechada .status-dot{background:#000;}

/* Mobile */
@media(max-width: 760px){
  table, thead, tbody, th, td, tr { display:block; }
  thead{ display:none; }

  tr {
    margin-bottom: 14px;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 10px;
    background:#fff;
  }

  td{
    border:none;
    padding:8px 6px;
  }

  td::before {
    content: attr(data-label);
    display:block;
    color:#6b7280;
    font-size:12px;
    margin-bottom:2px;
  }
}

.header {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 25px;
}

.header img {
  height: 120px;
  width: auto;
}

.header .title-area {
  display: flex;
  flex-direction: column;
}

.header .title-area h1 {
  margin: 0;
  font-size: 26px;
}

.header .title-area p {
  margin: 0;
  color: #6b7280;
}

.topbar {
  background: #ffffff;
  padding: 16px 24px;
  border-bottom: 1px solid #e5e7eb;
  margin: -20px -20px 20px -20px; /* encosta nas bordas da página */
}

.header {
  display: flex;
  align-items: center;
  gap: 16px;
  max-width: 1200px;
  margin: auto;
}

.header img {
  height: 75px; /* ajustar tamanho icone*/
}

.header .title-area h1 {
  font-size: 22px;
}

.header .title-area p {
  font-size: 13px;
}
</style>
</head>

<body>

<div class="page">

<div class="topbar">
  <div class="header">
    <img src="logo.png" alt="Logo">

    <div class="title-area">
      <h1>Chamadas Registradas</h1>
      <p>Lista geral das chamadas no sistema</p>
    </div>
  </div>
</div>

<form class="search-box" method="GET">
    <input 
      type="text" 
      name="q" 
      placeholder="Buscar por ID, solicitante, telefone, município ou natureza"
      value="<?= htmlspecialchars($busca) ?>"
    >
    <button type="submit">Buscar</button>
</form>

<div class="table-wrapper">

<?php
$sql = "
SELECT 
    id,
    data_atendimento,
    hora_atendimento,
    nome_solicitante,
    telefone_chamada,
    municipio_chamada,
    codigo_natureza,
    status
FROM registros_chamadas
";

if ($busca !== "") {
    $sql .= "
      WHERE 
        id = ? OR
        nome_solicitante LIKE ? OR
        telefone_chamada LIKE ? OR
        municipio_chamada LIKE ? OR
        codigo_natureza LIKE ? OR
        status LIKE ?
    ";
}

$sql .= " ORDER BY id DESC LIMIT 200";

$stmt = $conexao->prepare($sql);

if ($busca !== "") {
    $idBusca = is_numeric($busca) ? (int)$busca : 0;
    $like = "%$busca%";
    $stmt->bind_param("isssss", $idBusca, $like, $like, $like, $like, $like);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<table>
<thead>
<tr>
  <th>#</th>
  <th>Solicitante</th>
  <th>Telefone</th>
  <th>Município</th>
  <th>Natureza</th>
  <th>Status</th>
  <th>Atendimento</th>
</tr>
</thead>

<tbody>

<?php if ($resultado && $resultado->num_rows > 0): ?>
<?php while ($c = $resultado->fetch_assoc()): ?>

<tr class="row-link" onclick="window.location='relatorio.php?id=<?= $c['id'] ?>'">

  <td data-label="#"><?= $c['id'] ?></td>
  <td data-label="Solicitante"><?= htmlspecialchars($c['nome_solicitante']) ?></td>
  <td data-label="Telefone"><?= htmlspecialchars($c['telefone_chamada']) ?></td>
  <td data-label="Município"><?= htmlspecialchars($c['municipio_chamada']) ?></td>
  <td data-label="Natureza"><?= htmlspecialchars($c['codigo_natureza']) ?></td>

  <td data-label="Status">
    <?php
      if ($c['status'] == 'aberto') {
        echo '<span class="status-pill status-aberta"><span class="status-dot"></span> Aberta</span>';
      }
      elseif ($c['status'] == 'encaminhada') {
        echo '<span class="status-pill status-encaminhada"><span class="status-dot"></span> Encaminhado</span>';
      }
      elseif ($c['status'] == 'encerrada') {
        echo '<span class="status-pill status-fechada"><span class="status-dot"></span> Fechada</span>';
      } else {
        echo '-';
      }
    ?>
  </td>

  <td data-label="Atendimento">
    <?= date("d/m/Y", strtotime($c['data_atendimento'])) ?>
    às <?= substr($c['hora_atendimento'],0,5) ?>
  </td>

</tr>

<?php endwhile; ?>
<?php else: ?>
<tr>
  <td colspan="7" style="padding:20px;text-align:center;">
    Nenhum registro encontrado.
  </td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>

</div>

</body>
</html>
