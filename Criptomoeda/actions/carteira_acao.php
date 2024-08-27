<?php
require_once 'config.php';

function obterSaldoCarteira($usuarioId) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT c.nome, c.sigla, ca.quantidade
                            FROM carteiras ca
                            JOIN criptomoedas c ON ca.criptomoeda_id = c.id
                            WHERE ca.usuario_id = ?");
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();
    $saldos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $saldos;
}

function obterHistoricoTransacoes($usuarioId) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT t.*, c.nome as criptomoeda_nome, c.sigla as criptomoeda_sigla,
                            u1.nome as de_usuario_nome, u2.nome as para_usuario_nome
                            FROM transacoes t
                            JOIN criptomoedas c ON t.criptomoeda_id = c.id
                            JOIN usuarios u1 ON t.de_usuario_id = u1.id
                            JOIN usuarios u2 ON t.para_usuario_id = u2.id
                            WHERE t.de_usuario_id = ? OR t.para_usuario_id = ?
                            ORDER BY t.data DESC");
    $stmt->bind_param("ii", $usuarioId, $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();
    $transacoes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $transacoes;
}

function atualizarCarteira($usuarioId, $criptomoedaId, $quantidade) {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO carteiras (usuario_id, criptomoeda_id, quantidade) 
                            VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE quantidade = ?");
    $stmt->bind_param("iidd", $usuarioId, $criptomoedaId, $quantidade, $quantidade);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}
?>