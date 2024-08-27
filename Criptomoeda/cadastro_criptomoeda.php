<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include 'includes/cabecalho.php'; ?>
    <title>Cadastro de Criptomoeda</title>
</head>
<body>
    <header>
        <?php include 'includes/menu.php'; ?>
    </header>
    <main class="container">
        <?php
        require_once __DIR__ . '/actions/criptomoeda_acao.php';

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $criptomoeda = array('id' => '', 'nome' => '', 'sigla' => '');

        if ($id > 0) {
            $criptomoeda = carregarCriptomoeda($id);
            if (!$criptomoeda) {
                $_SESSION['erro'] = "Criptomoeda não encontrada.";
                header('Location: index.php');
                exit();
            }
        }

        if (isset($_SESSION['erro'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['erro']) . '</div>';
            unset($_SESSION['erro']);
        }
        ?>
        <h2><?= $id == 0 ? 'Cadastro' : 'Edição' ?> de Criptomoeda</h2>
        <form action="actions/criptomoeda_acao.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($criptomoeda['id']) ?>">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" value="<?= htmlspecialchars($criptomoeda['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label for="sigla">Sigla</label>
                <input type="text" class="form-control" name="sigla" id="sigla" value="<?= htmlspecialchars($criptomoeda['sigla']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </main>
</body>
</html>
