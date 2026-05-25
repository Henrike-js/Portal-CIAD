<?php
include __DIR__ . "/../../conexao.php";

// Validação de entrada
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Chamada inválida.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM registros_chamadas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Registro não encontrado.");
}

$c = $result->fetch_assoc();

function campo($valor) {
    $valor_exibir = ($valor !== null && $valor !== "") ? $valor : "---";
    return '<div class="value copy-box"><span class="copy-text">' . htmlspecialchars($valor_exibir) . '</span><button class="copy-btn" onclick="copiarTexto(this)">📋</button></div>';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Completo — ID <?= $c['id'] ?></title>
    <style>
        :root {
            --primary: #1e3a8a;
            --accent: #c63232;
            --bg-card: #ffffff;
            --bg-page: #f3f4f6;
            --border: #e5e7eb;
            --text-label: #6b7280;
        }

        body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg-page); margin: 0; padding: 20px; color: #111827; }
        .page { max-width: 1100px; margin: auto; }
        .report-card { background: var(--bg-card); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); padding: 30px; }
        .rep-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--bg-page); padding-bottom: 20px; margin-bottom: 25px; }
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; text-transform: uppercase; }
        .status-aberto { background: #fef3c7; color: #92400e; }
        .status-encaminhada { background: #dbeafe; color: #1e40af; }
        .status-encerrada { background: #d1fae5; color: #065f46; }
        .section-title { font-size: 14px; font-weight: 700; color: var(--primary); text-transform: uppercase; margin: 30px 0 15px; display: flex; align-items: center; gap: 10px; }
        .section-title::after { content: ""; flex: 1; height: 1px; background: var(--border); }
        .data-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
        .data-item { border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; background: #fff; }
        .label { font-size: 11px; font-weight: 600; color: var(--text-label); text-transform: uppercase; margin-bottom: 4px; }
        .full-width { grid-column: 1 / -1; }
        .text-block { background: #f9fafb; border: 1px solid var(--border); border-radius: 8px; padding: 15px; font-size: 14px; line-height: 1.6; white-space: pre-wrap; }
        .highlight-text { background: #fffbeb; border-color: #fef3c7; color: #92400e; }
        .copy-box { display: flex; justify-content: space-between; align-items: center; }
        .copy-btn { border: none; background: none; cursor: pointer; opacity: 0.3; transition: 0.2s; font-size: 12px; }
        .copy-btn:hover { opacity: 1; color: var(--primary); }
        .value { font-size: 14px; font-weight: 600; }
        .actions { margin-top: 40px; padding-top: 20px; border-top: 2px solid var(--bg-page); display: flex; justify-content: space-between; }
        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration: none; border: none; font-size: 14px; display: inline-flex; align-items: center; }
        .btn-back { background: #eee; color: #444; }
        .btn-delete { background: #dc2626; color: white; }
        .btn-delete:hover { background: #b91c1c; }
        @media print { .copy-btn, .actions { display: none; } body { padding: 0; } .report-card { box-shadow: none; border: 1px solid #eee; } }
        
        .btn-detalhe {
        background: #059669; /* Verde Esmeralda */
        color: white;
        margin-left: 10px;
}
.btn-detalhe:hover {
    background: #047857;
}
    </style>
</head>
<body>

<div class="page">
    <div class="report-card">
        
        <header class="rep-header">
            <div>
                <span class="label">Protocolo de Emergência</span>
                <h1 style="margin:0">Ocorrência #<?= $c['id'] ?></h1>
            </div>
            <span class="badge-status status-<?= $c['status'] ?>"><?= $c['status'] ?></span>
        </header>

        <div class="section-title">1. Dados do Atendimento Inicial</div>
        <div class="data-grid">
            <div class="data-item"><div class="label">Teleatendente</div><?= campo($c['nome_teleatendente']) ?></div>
            <div class="data-item"><div class="label">Matrícula</div><?= campo($c['matricula']) ?></div>
            <div class="data-item"><div class="label">Data</div><?= campo(date('d/m/Y', strtotime($c['data_atendimento']))) ?></div>
            <div class="data-item"><div class="label">Hora</div><?= campo($c['hora_atendimento']) ?></div>
            <div class="data-item"><div class="label">Destino Serviço</div><?= campo($c['destino_servico']) ?></div>
        </div>

        <div class="section-title">2. Localização do Fato</div>
        <div class="data-grid">
            <div class="data-item" style="grid-column: span 2;"><div class="label">Logradouro</div><?= campo($c['logradouro_chamada']) ?></div>
            <div class="data-item"><div class="label">Nº</div><?= campo($c['numero_chamada']) ?></div>
            <div class="data-item"><div class="label">Complemento</div><?= campo($c['complemento_chamada']) ?></div>
            <div class="data-item"><div class="label">Bairro</div><?= campo($c['bairro_chamada']) ?></div>
            <div class="data-item"><div class="label">Município</div><?= campo($c['municipio_chamada']) ?></div>
            <div class="data-item"><div class="label">Telefone Local</div><?= campo($c['telefone_chamada']) ?></div>
        </div>

        <div class="section-title">3. Dados do Solicitante</div>
        <div class="data-grid">
            <div class="data-item" style="grid-column: span 2;"><div class="label">Nome Solicitante</div><?= campo($c['nome_solicitante']) ?></div>
            <div class="data-item"><div class="label">Telefone</div><?= campo($c['telefone_solicitante']) ?></div>
            <div class="data-item" style="grid-column: span 2;"><div class="label">Endereço Solicitante</div><?= campo($c['endereco_solicitante']) ?></div>
            <div class="data-item"><div class="label">Nº</div><?= campo($c['numero_solicitante']) ?></div>
            <div class="data-item"><div class="label">Bairro</div><?= campo($c['bairro_solicitante']) ?></div>
            <div class="data-item"><div class="label">Município</div><?= campo($c['municipio_solicitante']) ?></div>
        </div>

        <div class="section-title">4. Natureza e Relato Inicial</div>
        <div class="data-grid">
            <div class="data-item"><div class="label">Cód. Natureza</div><?= campo($c['codigo_natureza']) ?></div>
            <div class="data-item" style="grid-column: span 2;"><div class="label">Batalhão Área</div><?= campo($c['batalhao']) ?></div>
            <div class="full-width">
                <div class="label">Histórico (Relato do Atendente)</div>
                <div class="text-block highlight-text"><?= htmlspecialchars($c['historico']) ?></div>
            </div>
        </div>

        <div class="section-title">5. Informações de Despacho</div>
        <div class="data-grid">
            <div class="data-item"><div class="label">Despachador</div><?= campo($c['despachador_nome']) ?></div>
            <div class="data-item"><div class="label">Matrícula</div><?= campo($c['despachador_matricula']) ?></div>
            <div class="data-item"><div class="label">Data Despacho</div><?= campo($c['data_despacho']) ?></div>
            <div class="data-item"><div class="label">Recurso/Viatura</div><?= campo($c['recurso']) ?></div>
            <div class="data-item"><div class="label">Unidade</div><?= campo($c['unidade']) ?></div>
            <div class="data-item"><div class="label">Hora Despacho</div><?= campo($c['hora_despacho']) ?></div>
            <div class="data-item"><div class="label">Saída Vtr</div><?= campo($c['hora_despachada']) ?></div>
            <div class="data-item"><div class="label">A Caminho</div><?= campo($c['hora_a_caminho']) ?></div>
            <div class="data-item"><div class="label">No Local</div><?= campo($c['hora_no_local']) ?></div>
        </div>

        <div class="section-title">6. Encerramento e Classificação Final</div>
        <div class="data-grid">
            <div class="data-item"><div class="label">Tipo Encerramento</div><?= campo($c['encerramento']) ?></div>
            <div class="data-item"><div class="label">Classificação</div><?= campo($c['classificacao']) ?></div>
            <div class="data-item"><div class="label">Cód. Natureza Final</div><?= campo($c['codigo_natureza_final']) ?></div>
            <div class="data-item"><div class="label">NR PM</div><?= campo($c['nr_pm']) ?></div>
            <div class="data-item" style="grid-column: span 3;"><div class="label">Obs. Classificação</div><?= campo($c['observacao_classificacao']) ?></div>
            <div class="full-width">
                <div class="label">Descrição Natureza Final</div>
                <div class="text-block"><?= htmlspecialchars($c['descricao_natureza_final'] ?? '---') ?></div>
            </div>
            <div class="full-width">
                <div class="label">Comentários Adicionais</div>
                <div class="text-block" style="background: #f1f5f9;"><?= htmlspecialchars($c['comentarios'] ?? '---') ?></div>
            </div>
        </div>
<div class="actions">
    <div style="display: flex; gap: 10px;">
        <a href="listagem.php" class="btn btn-back">
            ← Voltar para Lista
        </a>
        
        <a href="../relatorio_formulario/relatorio_detalhe.php?id=<?= $c['id'] ?>" class="btn btn-back">
            ← Voltar para o Despacho
        </a>
    </div>
    
    <?php if ($c['status'] == 'encerrada'): ?>
        <form id="formDeletar" action="excluir_chamada.php" method="POST">
            <input type="hidden" name="id" value="<?= $c['id'] ?>">
            <button type="button" class="btn btn-delete" onclick="solicitarConfirmacao()">
                🗑️ Excluir Ocorrência
            </button>
        </form>
    <?php endif; ?>
</div>
<script>
// Função para copiar texto
function copiarTexto(btn){
    const text = btn.closest('.copy-box').querySelector('.copy-text').innerText;
    navigator.clipboard.writeText(text).then(()=>{
        const originalText = btn.innerText;
        btn.innerText = "✔";
        setTimeout(()=>{ btn.innerText = "📋"; }, 1500);
    });
}

// Função para confirmar exclusão
function solicitarConfirmacao() {
    const pergunta = "Deseja realmente excluir esta chamada?\n\n⚠️ ATENÇÃO: Isto é IRREVERSÍVEL e todos os dados desta ocorrência serão apagados permanentemente!";
    if (confirm(pergunta)) {
        document.getElementById('formDeletar').submit();
    }
}
</script>

</body>
</html>