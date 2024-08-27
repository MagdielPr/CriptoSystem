<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include 'includes/cabecalho.php'; ?>
    <script>
        function abrirRelatorio() {
            var form = document.querySelector('form');
            var url = 'gerar_pdf.php?' + new URLSearchParams(new FormData(form)).toString();
            window.open(url, '_blank');
            return false;
        }
    </script>
</head>
<body>
    <header>
        <?php include 'includes/menu.php'; ?>
    </header>
    <main class="container">
        <h2>Relatório de Transações</h2>
        <?php
        require_once 'config.php';
        $conn = getConnection();
        ?>
        <form method="GET" action="" onsubmit="return abrirRelatorio();">
            <div class="form-group">
                <label for="data_inicio">Data Início:</label>
                <input type="date" name="data_inicio" id="data_inicio" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="data_fim">Data Fim:</label>
                <input type="date" name="data_fim" id="data_fim" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="criptomoeda_id">Criptomoeda:</label>
                <select name="criptomoeda_id" id="criptomoeda_id" class="form-control" required>
                    <?php
                    $stmt = $conn->prepare("SELECT id, nome, sigla FROM criptomoedas");
                    if (!$stmt->execute()) {
                        die("Erro na consulta: " . $conn->error);
                    }
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        echo "<option value=''>Nenhuma criptomoeda encontrada</option>";
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nome']) . " (" . htmlspecialchars($row['sigla']) . ")</option>";
                        }
                    }
                    $stmt->close();
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
        </form>
    </main>
    <?php $conn->close(); ?>
</body>
</html>