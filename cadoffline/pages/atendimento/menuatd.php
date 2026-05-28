<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISP - Menu Principal</title>
    <style>
        /* --- ESTILIZAÇÃO GERAL --- */
        * { box-sizing: border-box; }
        body, html { 
            margin: 0; padding: 0; height: 100%; 
            font-family: 'Segoe UI', sans-serif; 
            background: #f0f2f5; 
            display: flex; justify-content: center; align-items: center;
        }

        .dashboard-container {
            width: 100%;
            max-width: 900px;
            padding: 20px;
            text-align: center;
        }

        .header { margin-bottom: 40px; }
        .header img { max-height: 70px; margin-bottom: 15px; }
        .header h1 { color: #1e293b; font-size: 26px; margin: 0; }
        .header p { color: #64748b; margin-top: 8px; }

        /* --- GRID DE BOTÕES --- */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .menu-card {
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            text-decoration: none;
            color: #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid transparent;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.15);
        }

        /* Cores e Ícones específicos */
        .card-new { border-bottom: 5px solid #10b981; } /* Verde */
        .card-new:hover { border-color: #10b981; background: #f0fdf4; }

        .card-view { border-bottom: 5px solid #3b82f6; } /* Azul */
        .card-view:hover { border-color: #3b82f6; background: #eff6ff; }

        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-new .icon-box { background: #d1fae5; }
        .card-view .icon-box { background: #dbeafe; }

        .card-title { font-size: 20px; font-weight: 700; margin-bottom: 10px; }
        .card-desc { font-size: 14px; color: #64748b; line-height: 1.5; }

        /* Rodapé de Status */
        .footer-info { margin-top: 50px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="header">
        <a href="../../index.php" style="text-decoration:none;">
          <img src="../../../img/cadoffline/sisp-logo.png" alt="SISP Logo" onerror="this.style.display='none'">
        </a>
        <h1>Painel de Controle SISP</h1>
        <p>Sistema Integrado de Segurança Pública - Gestão de Atendimentos</p>
    </div>

    

    <div class="menu-grid">
        <a href="atendente_seu_formulario_integrado.php" class="menu-card card-new">
            <div class="icon-box">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            </div>
            <div class="card-title">Nova Ocorrência</div>
            <div class="card-desc">Registrar um novo atendimento, incidente ou chamada de emergência no sistema.</div>
        </a>

        <a href="listarchamadas\lista_chamadas.php" class="menu-card card-view">
            <div class="icon-box">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/><path d="M12 11v6m-3-3h6"/></svg>
            </div>
            <div class="card-title">Registros de Chamadas</div>
            <div class="card-desc">Consultar histórico, triagem de chamadas e despacho por batalhões.</div>
        </a>
    </div>

    <div class="footer-info">
        Servidor: <b><?= $_SERVER['SERVER_NAME'] ?></b> | 
        Data: <?= date('d/m/Y') ?>
    </div>
</div>

</body>
</html>