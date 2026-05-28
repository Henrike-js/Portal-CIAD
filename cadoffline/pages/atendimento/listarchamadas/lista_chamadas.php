<?php
include __DIR__ . "/../../conexao.php";

$busca = $_GET['q'] ?? "";

$sql = "
SELECT 
    id,
    matricula,
    nome_teleatendente,
    data_atendimento,
    hora_atendimento,
    destino_servico,
    logradouro_chamada,
    numero_chamada,
    bairro_chamada,
    municipio_chamada
FROM registros_chamadas
WHERE
    id LIKE '%$busca%' OR
    matricula LIKE '%$busca%' OR
    nome_teleatendente LIKE '%$busca%' OR
    destino_servico LIKE '%$busca%' OR
    logradouro_chamada LIKE '%$busca%' OR
    municipio_chamada LIKE '%$busca%'
ORDER BY id DESC
LIMIT 200
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Lista de Chamadas</title>

<style>

/* ==== BASE ==== */

body{margin:0;background:#f3f4f6;font-family:Arial}

.page{max-width:1200px;margin:0 auto}

.topbar{
  background:white;
  box-shadow:0 2px 10px rgba(0,0,0,.06);
}

.topbar-inner{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:10px 18px;
}

.logo-sisp-img{
  height:50px;
  width:auto;
}

.main{padding:16px}

.page-header h1{margin:0}
.page-header p{margin:6px 0 16px;color:#6b7280}

/* ==== BUSCA ==== */

.search-box{display:flex;gap:10px;margin:10px 0}
.search-box input{
  width:100%;
  padding:10px;
  border-radius:10px;
  border:1px solid #d1d5db;
}
.search-box button{
  padding:10px 18px;
  border-radius:10px;
  border:none;
  background:#2f5dff;
  color:white;
  font-weight:600;
  cursor:pointer;
}
.search-box button:hover{background:#254bdd}

/* ==== TABELA ==== */

.table-wrapper{
  margin-top:12px;
  background:white;
  border-radius:12px;
  box-shadow:0 8px 20px rgba(0,0,0,.05);
overflow-x:hidden;
}

table{
  width:100%;
  border-collapse:collapse;
  table-layout:auto;
  font-size: 13px;
}

th,td{
  padding:10px 12px;
  border-bottom:2px solid #d1d5db;
  font-size:14px;
  vertical-align:middle;
  text-align:center;     /* <<< CENTRALIZA TUDO */
}

th{
  background:#2f5dff;   /* azul do botão */
  color:white;          /* texto branco */
  font-weight:700;
}


.small-sub{
  font-size:12px;
  color:#6b7280;
}

/* RESPONSIVO */

@media(max-width:760px){
  table, thead, tbody, th, td, tr{display:block}
  thead{display:none}

  tr{
    margin-bottom:10px;
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:8px;
  }

  td{
    border:none !important;
    padding:8px 6px;
    text-align:left;
  }

  td::before{
    content:attr(data-label);
    display:block;
    color:#6b7280;
    font-size:12px;
    margin-bottom:2px;
  }
}

.footer{
  text-align:center;
  padding:12px;
  color:#6b7280;
}

td{
  word-wrap: break-word;
  white-space: normal;
}

</style>

</head>

<body>

<div class="page">

 <div class="topbar">
  <div class="topbar-inner">
    <a href="..\menuatd.php">
      <img src="../../../../img/cadoffline/logo.png" class="logo-sisp-img">
    </a>
    <div></div>
  </div>
</div>

  <main class="main">
    <div class="main-inner">

      <div class="page-header">
        <h1>Chamadas Registradas</h1>
        <p>Consulta geral das chamadas cadastradas</p>
      </div>

      <form class="search-box" method="GET">
        <input 
          type="text" 
          name="q"
          placeholder="Buscar por ID, atendente, endereço, destino..."
          value="<?= htmlspecialchars($busca) ?>"
        >
        <button type="submit">Buscar</button>
      </form>

      <div class="table-wrapper">

        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Atendente</th>
              <th>Destino</th>
              <th>Local</th>
              <th>Data/Hora</th>
              <th>Ver</th>
              <th>Editar</th>
            </tr>
          </thead>

          <tbody>

          <?php if ($result && $result->num_rows > 0): ?>
            <?php while($c = $result->fetch_assoc()): ?>

              <tr>

                <td><?= $c['id'] ?></td>

                <td>
                  <?= htmlspecialchars($c['nome_teleatendente']) ?><br>
                  <span class="small-sub">
                    <?= htmlspecialchars($c['matricula']) ?>
                  </span>
                </td>

                <td><?= htmlspecialchars($c['destino_servico'] ?? '') ?></td>

                <td>
                  <?= htmlspecialchars($c['logradouro_chamada']) ?>
                  <?= $c['numero_chamada'] ? ', '.htmlspecialchars($c['numero_chamada']) : '' ?><br>
                  <span class="small-sub">
                    <?= htmlspecialchars($c['bairro_chamada']) ?> -
                    <?= htmlspecialchars($c['municipio_chamada']) ?>
                  </span>
                </td>

                <td>
                  <?= htmlspecialchars($c['data_atendimento']) ?><br>
                  <span class="small-sub">
                    <?= substr($c['hora_atendimento'],0,5) ?>
                  </span>
                </td>

                <td>
                  <a href="visualizar_chamada.php?id=<?= $c['id'] ?>">Visualizar</a>
                </td>

                <td>
                  <a href="editar_historico.php?id=<?= $c['id'] ?>">Alterar</a>
                </td>

              </tr>

            <?php endwhile; ?>

          <?php else: ?>
            <tr>
              <td colspan="7">Nenhum registro encontrado.</td>
            </tr>
          <?php endif; ?>

          </tbody>

        </table>

      </div>

    </div>
  </main>

  <footer class="footer">
    Sistema de Chamadas — CIAD
  </footer>

</div>

</body>
</html>

<?php
$conn->close();
?>
