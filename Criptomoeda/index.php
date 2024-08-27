<?php
session_start(); // Iniciar a sessão
require_once "config.php";

$conn = getConnection();

// Função para buscar dados do banco de dados
function fetchData($conn, $query, $errorMessage) {
    $result = $conn->query($query);
    if (!$result) {
        die($errorMessage . $conn->error);
    }
    return $result;
}

// Busca usuários e criptomoedas
$usuarios_result = fetchData($conn, "SELECT id, nome, email FROM usuarios", "Erro na consulta de usuários: ");
$criptomoedas_result = fetchData($conn, "SELECT id, nome, sigla FROM criptomoedas", "Erro na consulta de criptomoedas: ");

// Função para gerar linhas da tabela
function generateTableRows($result, $columns, $editPage, $deleteAction) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<td>" . htmlspecialchars($row[$column]) . "</td>";
            }
            echo "<td align='center'><a class='btn btn-primary' href='{$editPage}?id={$row['id']}'>Alterar</a></td>";
            echo "<td align='center'><a class='btn btn-danger' href='javascript:excluirRegistro(\"{$deleteAction}?acao=excluir&id={$row['id']}\");'>Excluir</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='" . (count($columns) + 2) . "' class='text-center'>Sem registros a serem exibidos</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<?php include 'includes/cabecalho.php'; ?>

<body>
    <?php include 'includes/menu.php'; ?>
    <main class="container">
        <?php
        // Exibir mensagens de erro e sucesso
        if (isset($_SESSION['mensagem'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['mensagem']) . '</div>';
            unset($_SESSION['mensagem']);
        }
        if (isset($_SESSION['erro'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['erro']) . '</div>';
            unset($_SESSION['erro']);
        }
        ?>

        <h2>Usuários</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Alterar</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php generateTableRows($usuarios_result, ['id', 'nome', 'email'], 'cadastro_usuario.php', 'actions/usuario_acao.php'); ?>
                </tbody>
            </table>
        </div>

        <h2>Criptomoedas</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Sigla</th>
                        <th>Alterar</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php generateTableRows($criptomoedas_result, ['id', 'nome', 'sigla'], 'cadastro_criptomoeda.php', 'actions/criptomoeda_acao.php'); ?>
                </tbody>
            </table>
        </div>
        <a href="cadastro_criptomoeda.php" class="btn btn-success mb-3">Nova Criptomoeda</a>
    </main>
    <script>
        function excluirRegistro(url) {
            if (confirm("Confirmar Exclusão?")) {
                location.href = url;
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
