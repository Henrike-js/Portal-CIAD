<?php
  
include __DIR__ . "/../../conexao.php";

// --- CONFIGURAÇÃO DOS BATALHÕES ---
$batalhoes = [
    //--- CONFIGURAÇÃO POLICIA MILITAR ---
    "33º BPM /66º BPM","25º BPM 11° CIA ind",
    "35º BPM/61º BPM","36º BPM/1ªCIA/8ª CIA","ATENDIMENTO REMOTO REDS",
    "1º BPM - A","40º BPM/6ªCIA IND","5º BPM","49º BPM - A",
    "1ª RPM - CPE","7 RPM B","PMMG/BPMRV/BPGD","52º BPM 1° CIA Ind",
    "SUP DESP - 2ª/3ª RPM","22º BPM - A","22º BPM - B","BTL METROPOLE",
    "41º BPM","CPE / BPTRAN","34º BPM - A","16º BPM - A","16º BPM - B",
    "34º BPM - B","13º BPM","49º BPM - B","48º BPM/7 Cia",
    "39º BPM - A","18º BPM - A","7 RPM A",
    //--- CONFIGURAÇÃO BOMBEIROS ---
    "1°BBM","2°BBM","3°BBM",
    //--- CONFIGURAÇÃO POLÍCIA CIVIL ---
    "Policia Civil 1","Policia Civil 2","Policia Civil 3"
];

$batalhaoSelecionado = filter_input(INPUT_GET, 'batalhao');

// --- CONSULTA SQL ---
if ($batalhaoSelecionado) {
    $sql = "SELECT * FROM registros_chamadas WHERE batalhao=?";
    $tituloView = "Pasta: " . $batalhaoSelecionado;
} else {
    $sql = "SELECT * FROM registros_chamadas WHERE batalhao IS NULL OR batalhao=''";
    $tituloView = "Triagem (Sem Batalhão)";
}

$sql .= " ORDER BY data_atendimento DESC";

$stmt = $conn->prepare($sql);
if ($batalhaoSelecionado) {
    $stmt->bind_param("s", $batalhaoSelecionado);
}
$stmt->execute();
$result = $stmt->get_result();

// --- ÍCONES SVG ---
$iconFolder = '<svg width="36" height="36" viewBox="0 0 24 24" fill="#FCD34D" stroke="#d97706" stroke-width="1"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>';
$iconFolderOpen = '<svg width="36" height="36" viewBox="0 0 24 24" fill="#FFE082" stroke="#d97706" stroke-width="1"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>';
$iconBack   = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2"><path d="M11 17l-5-5m0 0l5-5m-5 5h12" /></svg>';
$iconHome = '<svg width="36" height="36" viewBox="0 0 24 24" fill="#FCD34D" stroke="#d97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Painel de Chamadas</title>
<style>
    /* --- RESET E ESTRUTURA GERAL --- */
    * { box-sizing: border-box; }
    body, html { margin: 0; padding: 0; height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; overflow: hidden; }

    .app-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    /* --- BARRA DE FILTRO DE SERVIÇO --- */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0 4px 0;
        background: #fff;
        flex-shrink: 0;
    }

    .filter-bar .filter-label {
        font-size: 11px;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        margin-right: 5px;
        white-space: nowrap;
    }

    .btn-filtro {
        padding: 6px 18px;
        border: 2px solid transparent;
        border-radius: 5px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 1px;
        transition: all 0.15s;
        color: #fff;
        white-space: nowrap;
    }

    .btn-filtro:hover { opacity: 0.85; transform: translateY(-1px); }

    .btn-filtro.active {
        border-color: #333;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .btn-190 { background: #2563eb; }
    .btn-193 { background: #dc2626; }
    .btn-197 { background: #4b5563; }

    .btn-todos {
        padding: 6px 14px;
        background: #fff;
        border: 2px solid #ccc;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        color: #555;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-todos:hover { border-color: #888; color: #222; }
    .btn-todos.active { border-color: #333; color: #111; background: #f0f0f0; }

    /* Pasta oculta pelo filtro */
    .folder-item.hidden-filter {
        display: none !important;
    }

    /* --- PAINEL DE PASTAS (TOPO) --- */
    .folders-pane {
        flex: 0 0 auto;
        background: #fff;
        border-bottom: 2px solid #ccc;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        z-index: 10;
    }

    .sisp-logo {
        display: block;
        max-height: 50px;
        width: auto;
        margin-bottom: 15px;
    }

    .pane-title { font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px; text-transform: uppercase; }

    .folders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 8px;
    }

    .folder-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 8px 5px;
        border: 1px solid transparent;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.2s;
        font-size: 11px;
    }
    .folder-item:hover { background: #eef6ff; border-color: #cce8ff; }
    .folder-item.active { background: #dcefff; border-color: #99d1ff; font-weight: bold; }
    .folder-item.drag-over { background: #b3d7ff; border: 2px dashed #005a9e; transform: scale(1.05); }
    .folder-name { margin-top: 4px; line-height: 1.2; color: #333; }

    /* --- PAINEL DE CHAMADAS (BAIXO) --- */
    .files-pane {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #e5e7eb;
    }

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        color: #333;
        font-size: 13px;
        font-weight: 600;
    }
    .btn-back:hover { background: #eee; }

    .files-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-content: flex-start;
    }

    /* =========================================
       ESTILO CARTÃO COMPLETO
       ========================================= */
    .chamada-card {
        width: 260px;
        min-height: 180px;
        background: #fff;
        border: 3px solid #000;
        padding: 12px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        cursor: pointer;
        position: relative;
        transition: transform 0.1s, box-shadow 0.1s;
        font-family: 'Arial', sans-serif;
        text-align: left;
    }

    .chamada-card:hover { 
        transform: translateY(-3px);
        box-shadow: 5px 5px 0px rgba(0,0,0,0.2);
        z-index: 5;
    }
    
    .chamada-card:active { 
        cursor: grabbing;
        opacity: 0.9;
    }

    .card-row {
        margin-bottom: 6px;
        border-bottom: 1px dashed #eee;
        padding-bottom: 4px;
    }
    .card-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .card-label {
        font-size: 9px;
        color: #777;
        text-transform: uppercase;
        font-weight: 700;
        display: block;
        margin-bottom: 1px;
    }

    .card-value {
        font-size: 13px;
        font-weight: 700;
        color: #000;
        line-height: 1.3;
        word-wrap: break-word;
        text-transform: uppercase;
    }

    /* Status (Bolinha) */
    .status-dot {
        height: 12px;
        width: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        border: 1px solid rgba(0,0,0,0.1);
        vertical-align: middle;
    }
    .dot-aberto    { background-color: #dc2626; box-shadow: 0 0 3px rgba(220,38,38,0.6); }
    .dot-encaminhada { background-color: #16a34a; box-shadow: 0 0 3px rgba(22,163,74,0.6); }
    .dot-encerrada { background-color: #000000; }
    .dot-padrao    { background-color: #9ca3af; }

    .val-natureza { color: #d946ef; }
    .val-municipio { color: #059669; }

    .text-193 { color: #dc2626; }
    .text-190 { color: #2563eb; }
    .text-197 { color: #4b5563; }
</style>
</head>
<body>

<div class="app-container">

    <!-- PAINEL DE PASTAS -->
    <div class="folders-pane">

        <a href="../menudes.php">
            <img src="img/sisp-logo.png" alt="SISP Logo" class="sisp-logo" style="cursor: pointer;">
        </a>

        <!-- BARRA DE FILTRO (abaixo do banner) -->
        <div class="filter-bar">
            <span class="filter-label">Filtrar:</span>
            <button class="btn-todos active" onclick="filtrarPastas('todos', this)">★ Todos</button>
            <button class="btn-filtro btn-190" onclick="filtrarPastas('190', this)">190 — PM</button>
            <button class="btn-filtro btn-193" onclick="filtrarPastas('193', this)">193 — Bombeiros</button>
            <button class="btn-filtro btn-197" onclick="filtrarPastas('197', this)">197 — Pol. Civil</button>
        </div>

        <div class="pane-title">Pastas (Arraste para organizar)</div>
        <div class="folders-grid">

            <!-- Pasta TRIAGEM (sempre visível) -->
            <div class="folder-item <?= !$batalhaoSelecionado ? 'active' : '' ?>"
                 onclick="window.location='?batalhao='"
                 data-batalhao="">
                <?= $iconHome ?>
                <span class="folder-name">TRIAGEM</span>
            </div>

            <?php foreach($batalhoes as $b): ?>
                <div class="folder-item <?= $b == $batalhaoSelecionado ? 'active' : '' ?>"
                     onclick="window.location='?batalhao=<?= urlencode($b) ?>'"
                     data-batalhao="<?= htmlspecialchars($b) ?>">
                    <?= $b == $batalhaoSelecionado ? $iconFolderOpen : $iconFolder ?>
                    <span class="folder-name"><?= htmlspecialchars($b) ?></span>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- PAINEL DE CHAMADAS -->
    <div class="files-pane">
        <div class="toolbar">
            <div style="display:flex; align-items:center; gap:10px;">
                <?php if($batalhaoSelecionado): ?>
                    <a href="?batalhao=" class="btn-back"><?= $iconBack ?> Voltar</a>
                <?php endif; ?>
                <span style="font-weight:700; font-size:16px; color:#333;">
                    <?= $tituloView ?>
                </span>
            </div>
            <div style="font-size:12px; color:#666;">
                Total: <b><?= $result->num_rows ?></b> registros
            </div>
        </div>

        <div class="files-grid">
            <?php if($result->num_rows === 0): ?>
                <div style="width:100%; text-align:center; padding:50px; color:#999;">
                    Nenhum registro encontrado nesta pasta.
                </div>
            <?php endif; ?>

            <?php while($c = $result->fetch_assoc()):
                $id        = $c['id'];
                $destino   = isset($c['destino_servico']) ? trim($c['destino_servico']) : '---';
                $municipio = isset($c['municipio_chamada']) && !empty($c['municipio_chamada']) ? $c['municipio_chamada'] : '---';
                $natureza  = isset($c['codigo_natureza']) && !empty($c['codigo_natureza']) ? $c['codigo_natureza'] : 'Natureza n/inf';
                $logradouro = isset($c['logradouro_chamada']) && !empty($c['logradouro_chamada']) ? $c['logradouro_chamada'] : 'Sem Logradouro';
                $numero    = isset($c['numero_local']) ? $c['numero_local'] : 'S/N';

                $statusDb  = isset($c['status']) ? strtoupper(trim($c['status'])) : '';
                $classDot  = 'dot-padrao';
                if (strpos($statusDb, 'ABERTO') !== false)       $classDot = 'dot-aberto';
                elseif (strpos($statusDb, 'ENCAMINHADA') !== false) $classDot = 'dot-encaminhada';
                elseif (strpos($statusDb, 'ENCERRADA') !== false)   $classDot = 'dot-encerrada';

                $classeCorTexto = '';
                if(strpos($destino, '193') !== false)      $classeCorTexto = 'text-193';
                elseif(strpos($destino, '190') !== false)  $classeCorTexto = 'text-190';
                elseif(strpos($destino, '197') !== false)  $classeCorTexto = 'text-197';
            ?>
                <div class="chamada-card"
                     draggable="true"
                     data-id="<?= $id ?>"
                     onclick="location.href='relatorio_detalhe.php?id=<?= $id ?>'">

                    <div class="card-row" style="display:flex; justify-content:space-between; align-items:flex-end;">
                        <div>
                            <span class="card-label">ID / STATUS</span>
                            <div style="display:flex; align-items:center;">
                                <span class="status-dot <?= $classDot ?>" title="<?= htmlspecialchars($statusDb) ?>"></span>
                                <span class="card-value">#<?= $id ?></span>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <span class="card-label">SERVIÇO</span>
                            <span class="card-value <?= $classeCorTexto ?>"><?= htmlspecialchars($destino) ?></span>
                        </div>
                    </div>

                    <div class="card-row">
                        <span class="card-label">NATUREZA</span>
                        <span class="card-value val-natureza"><?= htmlspecialchars($natureza) ?></span>
                    </div>

                    <div class="card-row">
                        <span class="card-label">MUNICÍPIO</span>
                        <span class="card-value val-municipio"><?= htmlspecialchars($municipio) ?></span>
                    </div>

                    <div class="card-row">
                        <span class="card-label">ENDEREÇO</span>
                        <span class="card-value" style="font-size:11px;">
                            <?= htmlspecialchars($logradouro) ?>
                        </span>
                    </div>

                    <div class="card-row" style="border:none;">
                        <span class="card-label">NÚMERO</span>
                        <span class="card-value"><?= htmlspecialchars($numero) ?></span>
                    </div>

                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
// --- FILTRO DE PASTAS POR SERVIÇO ---
const mapaFiltro = {
    '190': ['bpm', 'reds', 'cpe', 'rpm', 'bptran', 'metropole', 'bpgd', 'bpmrv', 'sup desp'],
    '193': ['bbm'],
    '197': ['policia civil']
};

function filtrarPastas(tipo, botaoClicado) {
    // Atualiza botão ativo
    document.querySelectorAll('.btn-filtro, .btn-todos').forEach(b => b.classList.remove('active'));
    botaoClicado.classList.add('active');

    document.querySelectorAll('.folder-item').forEach(pasta => {
        const nome = pasta.dataset.batalhao.toLowerCase();

        // Pasta TRIAGEM (data-batalhao vazio) sempre visível
        if (nome === '') {
            pasta.classList.remove('hidden-filter');
            return;
        }

        if (tipo === 'todos') {
            pasta.classList.remove('hidden-filter');
            return;
        }

        const palavrasChave = mapaFiltro[tipo];
        const visivel = palavrasChave.some(chave => nome.includes(chave));
        pasta.classList.toggle('hidden-filter', !visivel);
    });
}

// --- DRAG AND DROP ---
document.querySelectorAll('.chamada-card').forEach(card => {
    card.addEventListener('dragstart', e => {
        e.dataTransfer.setData('id', card.dataset.id);
        e.dataTransfer.effectAllowed = 'move';
        card.style.opacity = '0.5';
    });

    card.addEventListener('dragend', e => {
        card.style.opacity = '1';
    });
});

document.querySelectorAll('.folder-item').forEach(folder => {
    if(folder.classList.contains('active')) return;

    folder.addEventListener('dragover', e => {
        e.preventDefault();
        folder.classList.add('drag-over');
    });

    folder.addEventListener('dragleave', e => {
        folder.classList.remove('drag-over');
    });

    folder.addEventListener('drop', e => {
        e.preventDefault();
        folder.classList.remove('drag-over');
        e.stopPropagation();

        const idChamada = e.dataTransfer.getData('id');
        const batalhaoDestino = folder.dataset.batalhao;

        if(!idChamada) return;

        fetch('mover_chamada.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id: idChamada, batalhao: batalhaoDestino })
        })
        .then(res => {
            if(res.ok) window.location.reload();
            else alert("Erro ao mover.");
        });
    });
});
</script>

</body>
</html>