<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['carteira'])) {
    $conn = getConnection();
    $conn->begin_transaction();

    try {
        foreach ($_POST['carteira'] as $usuario_id => $criptomoedas) {
            foreach ($criptomoedas as $criptomoeda_id => $quantidade) {
                $stmt = $conn->prepare("INSERT INTO carteiras (usuario_id, criptomoeda_id, quantidade) 
                                        VALUES (?, ?, ?) 
                                        ON DUPLICATE KEY UPDATE quantidade = ?");
                $stmt->bind_param("iidd", $usuario_id, $criptomoeda_id, $quantidade, $quantidade);
                $stmt->execute();
            }
        }

        $conn->commit();
        header('Location: carteira.php?mensagem=Carteiras atualizadas com sucesso');
    } catch (Exception $e) {
        $conn->rollback();
        header('Location: carteira.php?erro=Erro ao atualizar carteiras: ' . $e->getMessage());
    } finally {
        $conn->close();
    }
} else {
    header('Location: carteira.php?erro=Dados inválidos');
}
exit();
?>