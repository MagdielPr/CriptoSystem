<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include 'includes/cabecalho.php'; ?>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Relatório de Transações</h1>
        <?php
        require_once 'config.php';
        $conn = getConnection();

        if ($_GET) {
            $data_inicio = $_GET['data_inicio'];
            $data_fim = $_GET['data_fim'];
            $criptomoeda_id = $_GET['criptomoeda_id'];
            
            echo "<p>Período: {$data_inicio} a {$data_fim}</p>";
            echo "<table>";
            echo "<thead><tr><th>Usuário</th><th>Criptomoeda</th><th>Quantidade</th><th>Data</th></tr></thead>";
            echo "<tbody>";
            
            $stmt = $conn->prepare("SELECT u.nome AS usuario, c.nome AS criptomoeda, t.quantidade, t.data 
                                    FROM transacoes t
                                    JOIN usuarios u ON t.de_usuario_id = u.id
                                    JOIN criptomoedas c ON t.criptomoeda_id = c.id
                                    WHERE t.data BETWEEN ? AND ? AND t.criptomoeda_id = ?");
            $stmt->bind_param("ssi", $data_inicio, $data_fim, $criptomoeda_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['usuario']) . "</td>";
                echo "<td>" . htmlspecialchars($row['criptomoeda']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantidade']) . "</td>";
                echo "<td>" . htmlspecialchars($row['data']) . "</td>";
                echo "</tr>";
            }

            $stmt->close();
            
            echo "</tbody></table>";
            
            echo "<a href='gerar_pdf.php?" . http_build_query($_GET) . "' class='btn' target='_blank'>Baixar PDF</a>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>