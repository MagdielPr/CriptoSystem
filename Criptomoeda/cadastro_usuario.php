<!DOCTYPE html>
<html lang="pt-BR">
<?php include 'includes/cabecalho.php'; ?>
<?php
require_once "actions/usuario_acao.php";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$dados = array('id' => '', 'nome' => '', 'email' => '');
if ($id != 0) {
    $dados = carregar($id);
}
?>

<body>
    <?php include 'includes/menu.php'; ?>
    <main class="container">
    <form action="actions/usuario_acao.php" method="post" class="mt-4">
    <div class="card">
        <div class="card-header">
            <h3><?= $id ? 'Editar' : 'Cadastro de' ?> Usuário</h3>
        </div>
        <div class="card-body">
            <input type="hidden" name="id" value="<?= $dados['id'] ?>">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($dados['email']) ?>" required>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
    </main>
</body>
</html>
