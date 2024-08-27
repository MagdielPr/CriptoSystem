<?php
require_once '../config.php';

if (isset($_GET['usuario_id'])) {
    $usuario_id = $_GET['usuario_id'];
    $conn = getConnection();
    
    echo "<h4>Transações do Usuário</h4>";
    echo "<table class='table'>";
    echo "<thead><tr><th>ID</th><th>Criptomoeda</th><th>Quantidade</th><th>Data</th></tr></thead>";
    echo "<tbody>";
    
    $stmt = $conn->prepare("SELECT t.id, c.nome as criptomoeda, t.quantidade, t.data 
                            FROM transacoes t 
                            JOIN criptomoedas c ON t.criptomoeda_id = c.id 
                            WHERE t.usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['criptomoeda']}</td><td>{$row['quantidade']}</td><td>{$row['data']}</td></tr>";
    }
    
    echo "</tbody></table>";
    $stmt->close();
    $conn->close();
}
?>