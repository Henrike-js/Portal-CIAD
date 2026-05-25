<?php

include __DIR__ . "/../conexao.php";

// ===== RECEBENDO DADOS =====
$matricula             = $_POST['matricula'] ?? null;
$nome_teleatendente    = $_POST['nome_teleatendente'] ?? null;
$data_atendimento      = $_POST['data_atendimento'] ?? null;
$hora_atendimento      = $_POST['hora_atendimento'] ?? null;

// CHECKBOX → vira string "190,197,193"
$destino_servico = null;
if (!empty($_POST['destino_servico']) && is_array($_POST['destino_servico'])) {
    $destino_servico = implode(",", $_POST['destino_servico']);
}

$logradouro_chamada    = $_POST['logradouro_chamada'] ?? null;
$numero_chamada        = $_POST['numero_chamada'] ?? null;
$complemento_chamada   = $_POST['complemento_chamada'] ?? null;
$bairro_chamada        = $_POST['bairro_chamada'] ?? null;
$municipio_chamada     = $_POST['municipio_chamada'] ?? null;
$telefone_chamada      = $_POST['telefone_chamada'] ?? null;

$nome_solicitante      = $_POST['nome_solicitante'] ?? null;
$endereco_solicitante  = $_POST['endereco_solicitante'] ?? null;
$numero_solicitante    = $_POST['numero_solicitante'] ?? null;
$complemento_solicitante = $_POST['complemento_solicitante'] ?? null;
$bairro_solicitante    = $_POST['bairro_solicitante'] ?? null;
$municipio_solicitante = $_POST['municipio_solicitante'] ?? null;
$telefone_solicitante  = $_POST['telefone_solicitante'] ?? null;

$historico    = $_POST['historico'] ?? null;
$codigo_natureza       = $_POST['codigo_natureza'] ?? null;


// ===== INSERT COM PREPARED STATEMENT =====
$sql = "INSERT INTO registros_chamadas (
            matricula,
            nome_teleatendente,
            data_atendimento,
            hora_atendimento,
            destino_servico,
            logradouro_chamada,
            numero_chamada,
            complemento_chamada,
            bairro_chamada,
            municipio_chamada,
            telefone_chamada,
            nome_solicitante,
            endereco_solicitante,
            numero_solicitante,
            complemento_solicitante,
            bairro_solicitante,
            municipio_solicitante,
            telefone_solicitante,
            historico,
            codigo_natureza
        )
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar SQL: " . $conn->error);
}

// 20 parâmetros (20 's')
$stmt->bind_param(
    "ssssssssssssssssssss",
    $matricula,
    $nome_teleatendente,
    $data_atendimento,
    $hora_atendimento,
    $destino_servico,
    $logradouro_chamada,
    $numero_chamada,
    $complemento_chamada,
    $bairro_chamada,
    $municipio_chamada,
    $telefone_chamada,
    $nome_solicitante,
    $endereco_solicitante,
    $numero_solicitante,
    $complemento_solicitante,
    $bairro_solicitante,
    $municipio_solicitante,
    $telefone_solicitante,
    $historico,
    $codigo_natureza
);

if ($stmt->execute()) { 

    $id_chamada = mysqli_insert_id($conn);

    echo "
    <div style='
        font-family: Arial;
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 5px;
        width: 350px;
        margin: 40px auto;
        text-align: center;
        font-size: 18px;'>

        <strong>Chamada nº {$id_chamada}</strong> criada com sucesso!<br><br>

        Redirecionando em <span id=\"contador\">10</span> segundos...
    </div>

    <script>
        let tempo = 10;
        let contador = document.getElementById('contador');
        
        setInterval(() => {
            tempo--;
            contador.textContent = tempo;
            if (tempo <= 0) {
                window.location.href = 'atendente_seu_formulario_integrado.php';
            }
        }, 1000);
    </script>
    ";

} else { 
    echo "Erro ao salvar ocorrência: " . $stmt->error; 
}

$stmt->close();
$conn->close();
?>

