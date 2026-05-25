<?php
require __DIR__ . "/../../conexao.php";
$busca = isset($_GET['q']) ? trim($_GET['q']) : "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SISP - Chamadas Registradas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2f5dff;
            --primary-hover: #1e46d9;
            --bg: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            background: var(--bg); 
            font-family: 'Inter', sans-serif; 
            color: var(--text-main);
            line-height: 1.5;
        }

        /* Topbar fixa e elegante */
        .topbar { 
            background: var(--white); 
            padding: 1rem 2rem; 
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        
        .header { 
            max-width: 1200px; 
            margin: auto; 
            display: flex; 
            align-items: center; 
            gap: 20px;
        }

        .header img { height: 50px; width: auto; transition: transform 0.2s; }
        .header:hover img { transform: scale(1.05); }

        .title-area h1 { font-size: 1.25rem; font-weight: 700; color: #0f172a; }
        .title-area p { font-size: 0.875rem; color: var(--text-muted); }

        .page { max-width: 1200px; margin: 0 auto; padding: 0 20px 40px; }

        /* Barra de Busca */
        .search-box { 
            margin-bottom: 2rem; 
            display: flex; 
            gap: 12px; 
            background: var(--white);
            padding: 15px;
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .search-box input { 
            flex: 1; 
            padding: 12px 18px; 
            border-radius: 10px; 
            border: 1px solid var(--border); 
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-box input:focus { border-color: var(--primary); }

        .search-box button { 
            padding: 0 25px; 
            border-radius: 10px; 
            border: none; 
            background: var(--primary); 
            color: white; 
            font-weight: 600; 
            cursor: pointer; 
            transition: background 0.2s;
        }

        .search-box button:hover { background: var(--primary-hover); }

        /* Tabela */
        .table-wrapper { 
            background: var(--white); 
            border-radius: 16px; 
            box-shadow: var(--shadow); 
            overflow: hidden; 
            border: 1px solid var(--border);
        }

        table { width: 100%; border-collapse: collapse; }
        
        thead { background: #f1f5f9; }
        
        th { 
            padding: 16px; 
            text-align: left; 
            color: var(--text-muted); 
            font-weight: 600; 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.05em;
        }

        td { padding: 16px; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        
        tr.row-link { cursor: pointer; transition: background 0.2s; }
        tr.row-link:hover { background: #f8fafc; }
        tr.row-link:hover td { color: var(--primary); }

        /* Localização */
        .loc-municipio { display: block; font-weight: 600; color: var(--text-main); }
        .loc-logradouro { display: block; font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }

        /* Status Pills (Melhorados) */
        .status-pill { 
            display: inline-flex; 
            align-items: center; 
            gap: 6px; 
            padding: 4px 12px; 
            border-radius: 99px; 
            font-weight: 600; 
            font-size: 0.75rem; 
        }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; }

        .status-aberta { background: #fef2f2; color: #991b1b; }
        .status-aberta .status-dot { background: #ef4444; }

        .status-encaminhada { background: #f0fdf4; color: #166534; }
        .status-encaminhada .status-dot { background: #22c55e; }

        .status-fechada { background: #f1f5f9; color: #475569; }
        .status-fechada .status-dot { background: #94a3b8; }

        .atendimento-info { font-size: 0.85rem; font-weight: 500; }
        .atendimento-info small { color: var(--text-muted); display: block; }
    </style>
</head>

<body>

<div class="topbar">
    <a href="../menudes.php" style="text-decoration: none; color: inherit; display: block;">
        <div class="header">
            <img src="logo.png" alt="SISP Logo">
            <div class="title-area">
                <h1>Chamadas Registradas</h1>
                <p>Sistema Integrado de Segurança e Monitoramento</p>
            </div>
        </div>
    </a>
</div>

<div class="page">
    <form class="search-box" method="GET">
        <input type="text" name="q" placeholder="Pesquisar por ID, nome, telefone ou local..." value="<?= htmlspecialchars($busca) ?>">
        <button type="submit">Filtrar</button>
    </form>

    <div class="table-wrapper">
        <?php
        // Query preparada
        $sql = "SELECT id, data_atendimento, hora_atendimento, nome_solicitante, telefone_chamada, 
                       municipio_chamada, logradouro_chamada, numero_chamada, codigo_natureza, status 
                FROM registros_chamadas";

        if ($busca !== "") {
            $sql .= " WHERE id = ? OR nome_solicitante LIKE ? OR telefone_chamada LIKE ? OR municipio_chamada LIKE ? OR codigo_natureza LIKE ? OR status LIKE ?";
        }
        $sql .= " ORDER BY id DESC LIMIT 200";

        $stmt = $conn->prepare($sql);
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
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Localização</th>
                    <th>Natureza</th>
                    <th>Status</th>
                    <th>Data/Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($c = $resultado->fetch_assoc()): ?>
                        <tr class="row-link" onclick="window.location='relatorio.php?id=<?= $c['id'] ?>'">
                            <td><strong>#<?= $row['id'] ?? $c['id'] ?></strong></td>
                            <td>
                                <strong><?= htmlspecialchars($c['nome_solicitante']) ?></strong><br>
                                <small style="color:var(--text-muted)"><?= htmlspecialchars($c['telefone_chamada']) ?></small>
                            </td>
                            <td>
                                <span class="loc-municipio"><?= htmlspecialchars($c['municipio_chamada']) ?></span>
                                <span class="loc-logradouro"><?= htmlspecialchars($c['logradouro_chamada']) ?>, <?= htmlspecialchars($c['numero_chamada']) ?></span>
                            </td>
                            <td><span style="font-weight: 500;"><?= htmlspecialchars($c['codigo_natureza']) ?></span></td>
                            <td>
                                <?php
                                    $s = strtolower($c['status']);
                                    if ($s == 'aberto') echo '<span class="status-pill status-aberta"><span class="status-dot"></span> Aberta</span>';
                                    elseif ($s == 'encaminhada') echo '<span class="status-pill status-encaminhada"><span class="status-dot"></span> Encaminhada</span>';
                                    elseif ($s == 'encerrada') echo '<span class="status-pill status-fechada"><span class="status-dot"></span> Fechada</span>';
                                ?>
                            </td>
                            <td class="atendimento-info">
                                <?= date("d/m/Y", strtotime($c['data_atendimento'])) ?>
                                <small>às <?= substr($c['hora_atendimento'], 0, 5) ?></small>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="padding:40px; text-align:center; color: var(--text-muted);">Nenhum registro encontrado para a busca atual.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>