<?php
require_once '../config.php';

function realizarTransacao($deUsuarioId, $paraUsuarioId, $criptomoedaId, $quantidade) {
    $conn = getConnection();
    $conn->begin_transaction();

    try {
        // Verificar saldo do usuário
        $stmt = $conn->prepare("SELECT quantidade FROM carteiras WHERE usuario_id = ? AND criptomoeda_id = ?");
        $stmt->bind_param("ii", $deUsuarioId, $criptomoedaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $saldoAtual = $result->fetch_assoc()['quantidade'] ?? 0;

        if ($saldoAtual < $quantidade) {
            throw new Exception("Saldo insuficiente");
        }

        // Atualizar carteira do remetente
        $stmt = $conn->prepare("UPDATE carteiras SET quantidade = quantidade - ? WHERE usuario_id = ? AND criptomoeda_id = ?");
        $stmt->bind_param("dii", $quantidade, $deUsuarioId, $criptomoedaId);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao atualizar carteira do remetente: " . $stmt->error);
        }

        // Atualizar ou criar carteira do destinatário
        $stmt = $conn->prepare("INSERT INTO carteiras (usuario_id, criptomoeda_id, quantidade) VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE quantidade = quantidade + ?");
        $stmt->bind_param("iidd", $paraUsuarioId, $criptomoedaId, $quantidade, $quantidade);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao atualizar carteira do destinatário: " . $stmt->error);
        }

        // Registrar a transação
        $stmt = $conn->prepare("INSERT INTO transacoes (usuario_id, criptomoeda_id, quantidade, data, de_usuario_id, para_usuario_id) 
                                VALUES (?, ?, ?, NOW(), ?, ?)");
        $stmt->bind_param("iidii", $deUsuarioId, $criptomoedaId, $quantidade, $deUsuarioId, $paraUsuarioId);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao registrar a transação: " . $stmt->error);
        }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Erro na transação: " . $e->getMessage());
        return $e->getMessage();
    } finally {
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deUsuarioId = $_POST['de_usuario_id'];
    $paraUsuarioId = $_POST['para_usuario_id'];
    $criptomoedaId = $_POST['criptomoeda_id'];
    $quantidade = $_POST['quantidade'];

    $result = realizarTransacao($deUsuarioId, $paraUsuarioId, $criptomoedaId, $quantidade);
    if ($result === true) {
        header('Location: ../carteira.php?mensagem=Transação realizada com sucesso');
    } else {
        header('Location: ../cadastro_transacao.php?erro=' . urlencode($result));
    }
    exit();
}
?>