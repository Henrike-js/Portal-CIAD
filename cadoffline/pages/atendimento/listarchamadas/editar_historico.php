<?php
include __DIR__ . "/../../conexao.php";

// ---- SALVAR ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $historico_existente = $_POST['historico_existente'];
    $novo_texto = trim($_POST['novo_texto']);
    $responsavel = trim($_POST['responsavel']);
    $matricula = trim($_POST['matricula']);

    if ($novo_texto !== "" && $responsavel !== "" && $matricula !== "") {
        $data = date("d/m/Y H:i");
        $historico_final = $historico_existente . "\n\n---- Registro incluído em $data por $responsavel (Matrícula: $matricula) ----\n" . $novo_texto;

        $sql = "UPDATE registros_chamadas SET historico = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $historico_final, $id);

        if ($stmt->execute()) {
            header("Location: lista_chamadas.php?ok=1");
            exit;
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }
    }
}

// ---- CARREGAR REGISTRO ----
$id = $_GET['id'];
$sql = "SELECT id, historico FROM registros_chamadas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$registro = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Histórico | CIAD</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f1f5f9;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Topbar / Logo Area */
        .header-container {
            width: 100%;
            background: var(--white);
            padding: 20px 0;
            display: flex;
            justify-content: center;
            border-bottom: 1px solid var(--border);
            margin-bottom: 30px;
        }

        .logo-img {
            max-height: 80px; /* Ajuste conforme o tamanho do seu logo */
            width: auto;
        }

        /* Main Card */
        .container {
            width: 95%;
            max-width: 850px;
            background: var(--white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-bottom: 50px;
        }

        h2 {
            margin-top: 0;
            font-size: 1.5rem;
            border-left: 5px solid var(--primary);
            padding-left: 15px;
            color: var(--text-dark);
        }

        .call-number {
            color: var(--primary);
            font-weight: bold;
        }

        .label-block {
            display: block;
            margin: 20px 0 8px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        /* Inputs and Textareas */
        .textarea-wide {
            width: 100%;
            padding: 15px;
            font-size: 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background-color: #f8fafc;
            resize: vertical;
            box-sizing: border-box;
            font-family: inherit;
            line-height: 1.5;
        }

        .textarea-editable {
            background-color: var(--white);
            border: 2px solid var(--border);
            min-height: 120px;
        }

        .textarea-editable:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .row-small {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
        }

        .field-group {
            flex: 1;
        }

        .field-group.small {
            flex: 0 0 200px;
        }

        .input-styled {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-sizing: border-box;
        }

        .input-styled:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Buttons */
        .actions-row {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn {
            padding: 12px 30px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-save {
            background: var(--primary);
            color: #fff;
        }

        .btn-save:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #f1f5f9;
            color: var(--text-dark);
            border: 1px solid var(--border);
        }

        .btn-cancel:hover {
            background: #e2e8f0;
        }

        @media (max-width: 600px) {
            .row-small { flex-direction: column; gap: 0; }
            .field-group.small { flex: 1; }
        }
    </style>
</head>

<body>

    <header class="header-container">
        <img src="../../../../img/cadoffline/logo.png" alt="Logo do Sistema" class="logo-img">
    </header>

    <div class="container">
        <h2>Editar Histórico <span class="call-number">#<?= $registro['id'] ?></span></h2>

        <form method="post" id="formHistorico">
            <input type="hidden" name="id" value="<?= $registro['id'] ?>">
            <input type="hidden" name="historico_existente" value="<?= htmlspecialchars($registro['historico']) ?>">

            <label class="label-block">Histórico Atual (Somente Leitura)</label>
            <textarea readonly class="textarea-wide"><?= htmlspecialchars($registro['historico']) ?></textarea>

            <div class="row-small">
                <div class="field-group">
                    <label class="label-block">Edição Realizada Por</label>
                    <input type="text" name="responsavel" class="input-styled" placeholder="Nome completo do atendente" required>
                </div>

                <div class="field-group small">
                    <label class="label-block">Matrícula</label>
                    <input type="text" name="matricula" class="input-styled" placeholder="Ex: 12345" required>
                </div>
            </div>

            <label class="label-block">Adicionar Novo Complemento</label>
            <textarea name="novo_texto" class="textarea-wide textarea-editable" placeholder="Digite as novas informações detalhadamente..." required></textarea>

            <div class="actions-row">
                <a href="lista_chamadas.php" class="btn btn-cancel">Cancelar</a>
                <button type="submit" class="btn btn-save">Salvar Alterações</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("formHistorico").addEventListener("submit", function(e) {
            const responsavel = document.querySelector("[name='responsavel']").value.trim();
            const matricula = document.querySelector("[name='matricula']").value.trim();
            const texto = document.querySelector("[name='novo_texto']").value.trim();

            if (!responsavel || !matricula || !texto) {
                alert("Por favor, preencha todos os campos obrigatórios.");
                e.preventDefault();
            }
        });
    </script>

</body>
</html>

<?php $conn->close(); ?>