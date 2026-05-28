<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISP - Painel do Supervisor</title>
    <style>
        /* --- ESTILIZAÇÃO GERAL --- */
        * { box-sizing: border-box; }
        body, html { 
            margin: 0; padding: 0; height: 100%; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f1f5f9; 
            display: flex; justify-content: center; align-items: center;
        }

        .dashboard-container {
            width: 100%;
            max-width: 950px;
            padding: 30px;
            text-align: center;
        }

        .header { margin-bottom: 50px; }
        .header img { max-height: 90px; margin-bottom: 20px; }
        .header h1 { color: #0f172a; font-size: 32px; margin: 0; font-weight: 800; letter-spacing: -1px; }
        .header p { color: #475569; margin-top: 10px; font-size: 18px; }

        /* --- GRID DE BOTÕES --- */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .menu-card {
            background: #ffffff;
            padding: 45px 35px;
            border-radius: 24px;
            text-decoration: none;
            color: #334155;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .menu-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        /* CARD 1: TRIAGEM E ENCAMINHAMENTO (SUPERVISÃO) */
        .card-supervisor { border-top: 8px solid #c63232; } 
        .card-supervisor:hover { background: #fffafb; border-color: #e11d48; }

        /* CARD 2: LISTAGEM E CONSULTA (HISTÓRICO) */
        .card-history { border-top: 8px solid #1e3a8a; }
        .card-history:hover { background: #f8faff; border-color: #2563eb; }

        .icon-box {
            width: 90px;
            height: 90px;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .menu-card:hover .icon-box { transform: scale(1.1); }

        .card-supervisor .icon-box { background: #ffe4e6; color: #c63232; }
        .card-history .icon-box { background: #dbeafe; color: #1e3a8a; }

        .card-title { font-size: 24px; font-weight: 800; margin-bottom: 12px; color: #1e293b; }
        .card-desc { font-size: 15px; color: #64748b; line-height: 1.6; padding: 0 10px; }

        /* Etiquetas de Função */
        .role-tag {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #f1f5f9;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
        }

        .footer-info { margin-top: 60px; font-size: 14px; color: #64748b; font-weight: 500; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="header">
        <a href="../../index.php" style="text-decoration:none;">
          <img src="../../../img/cadoffline/logo.png" alt="SISP Logo" onerror="this.style.display='none'">
        </a>
        <h1>Painel de Gestão SISP</h1>
        <p>Módulo de Supervisão e Controle de Despacho</p>
    </div>

    

    <div class="menu-grid">
        <a href="relatorio_formulario/relatorio.php" class="menu-card card-supervisor">
            <span class="role-tag">Supervisor</span>
            <div class="icon-box">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    <line x1="12" y1="18" x2="12" y2="14"></line>
                </svg>
            </div>
            <div class="card-title">Triagem & Despacho</div>
            <div class="card-desc">Analisar chamadas recebidas, classificar ocorrências e realizar o encaminhamento imediato para os batalhões de área.</div>
        </a>

        <a href="chamadasfinalizadas/listagem.php" class="menu-card card-history">
            <span class="role-tag">Consulta</span>
            <div class="icon-box">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <div class="card-title">Monitor de Registros</div>
            <div class="card-desc">Acesso completo ao banco de dados de chamadas, verificação de status finalizado e auditoria de ocorrências.</div>
        </a>
    </div>

    <div class="footer-info">
        ESTAÇÃO OPERACIONAL: <b><?= gethostbyaddr($_SERVER['REMOTE_ADDR']) ?></b><br>
        Sessão iniciada em: <?= date('d/m/Y') ?> às <?= date('H:i') ?>
    </div>
</div>

</body>
</html>