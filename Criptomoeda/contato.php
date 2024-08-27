<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include 'includes/cabecalho.php'; ?>
</head>
<body>
    <header>
        <?php include 'includes/menu.php'; ?>
    </header>
    <main class="container">
        <form action="actions/contato_acao.php" method="post">
            <legend>Formulário de Contato</legend>

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="mensagem">Mensagem</label>
                <textarea class="form-control" name="mensagem" id="mensagem" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="anexo">Anexo</label>
                <input type="file" class="form-control-file" name="anexo" id="anexo">
            </div>
            
            <button type="submit" class="btn btn-primary">Enviar</button>
            <button type="reset" class="btn btn-secondary">Cancelar</button>
        </form>
    </main>
</body>
</html>