<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include 'includes/cabecalho.php'; ?>
    <title>Nova Transação</title>
</head>
<body>
    <header>
        <?php include 'includes/menu.php'; ?>
    </header>
    <main class="container">
        <h2>Nova Transação</h2>
        <?php
        if (isset($_GET['erro'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['erro']) . '</div>';
        }
        require_once 'config.php';
        $conn = getConnection();
        ?>
        <form action="actions/transacao_acao.php" method="post">
            <div class="form-group">
                <label for="de_usuario_id">De Usuário</label>
                <select class="form-control" name="de_usuario_id" id="de_usuario_id" required>
                    <?php
                    $stmt = $conn->prepare("SELECT id, nome FROM usuarios");
                    if (!$stmt->execute()) {
                        die("Erro na consulta: " . $conn->error);
                    }
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nome']) . "</option>";
                    }
                    $stmt->close();
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="para_usuario_id">Para Usuário</label>
                <select class="form-control" name="para_usuario_id" id="para_usuario_id" required>
                    <?php
                    $stmt = $conn->prepare("SELECT id, nome FROM usuarios");
                    if (!$stmt->execute()) {
                        die("Erro na consulta: " . $conn->error);
                    }
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nome']) . "</option>";
                    }
                    $stmt->close();
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="criptomoeda_id">Criptomoeda</label>
                <select class="form-control" name="criptomoeda_id" id="criptomoeda_id" required>
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

            <div class="form-group">
                <label for="quantidade">Quantidade</label>
                <input type="number" step="0.00000001" class="form-control" name="quantidade" id="quantidade" required>
            </div>

            <button type="submit" class="btn btn-primary">Realizar Transação</button>
        </form>
    </main>
    <?php $conn->close(); ?>
</body>
</html>