<?php
include __DIR__ . "/../../conexao.php";

/**
 * Lógica de Exclusão Segura
 * 1. Verifica se o ID foi enviado via POST
 * 2. Valida se o registro existe e se o status permite exclusão
 * 3. Executa o DELETE e redireciona com mensagem
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        header("Location: listagem.php?erro=id_invalido");
        exit();
    }

    // Verificação de segurança: O registro realmente está 'encerrada'?
    $stmtCheck = $conn->prepare("SELECT status FROM registros_chamadas WHERE id = ?");
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $res = $stmtCheck->get_result();
    $dados = $res->fetch_assoc();

    if (!$dados) {
        header("Location: listagem.php?erro=nao_encontrado");
        exit();
    }

    if ($dados['status'] !== 'encerrada') {
        header("Location: relatorio.php?id=$id&erro=status_nao_permite");
        exit();
    }

    // Execução da exclusão permanente
    $stmtDel = $conn->prepare("DELETE FROM registros_chamadas WHERE id = ?");
    $stmtDel->bind_param("i", $id);

    if ($stmtDel->execute()) {
        // Redireciona para a lista com alerta de sucesso
        header("Location: listagem.php?msg=excluido_sucesso");
        exit();
    } else {
        die("Erro crítico ao excluir: " . $conn->error);
    }

} else {
    // Se tentarem acessar este arquivo via URL diretamente, redireciona para a lista
    header("Location: listagem.php");
    exit();
}