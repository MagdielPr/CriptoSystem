<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include 'includes/cabecalho.php'; ?>
    <title>Carteiras</title>
</head>
<body>
    <header>
        <?php include 'includes/menu.php'; ?>
    </header>
    <main class="container">
        <?php
        require_once 'config.php';
        $conn = getConnection();

        // Pega todos os usuários
        $stmt = $conn->prepare("SELECT id, nome FROM usuarios ORDER BY nome");
        $stmt->execute();
        $usuarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Pega todas as criptomoedas
        $stmt = $conn->prepare("SELECT id, nome, sigla FROM criptomoedas ORDER BY nome");
        $stmt->execute();
        $criptomoedas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Pega saldo de todas as carteiras
        $stmt = $conn->prepare("SELECT ca.usuario_id, ca.criptomoeda_id, ca.quantidade
                                FROM carteiras ca
                                ORDER BY ca.usuario_id, ca.criptomoeda_id");
        $stmt->execute();
        $saldos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Organiza saldos por usuário e criptomoeda
        $carteiras = [];
        foreach ($saldos as $saldo) {
            $carteiras[$saldo['usuario_id']][$saldo['criptomoeda_id']] = $saldo['quantidade'];
        }

        // Pega últimas transações
        $stmt = $conn->prepare("SELECT t.*, c.nome as criptomoeda_nome, c.sigla as criptomoeda_sigla,
                                u1.nome as de_usuario_nome, u2.nome as para_usuario_nome
                                FROM transacoes t
                                JOIN criptomoedas c ON t.criptomoeda_id = c.id
                                JOIN usuarios u1 ON t.de_usuario_id = u1.id
                                JOIN usuarios u2 ON t.para_usuario_id = u2.id
                                ORDER BY t.data DESC LIMIT 10");
        $stmt->execute();
        $transacoes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        ?>

        <h2>Carteiras</h2>

        <form action="atualizar_carteira.php" method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <?php foreach ($criptomoedas as $criptomoeda): ?>
                            <th><?php echo htmlspecialchars($criptomoeda['nome']) . ' (' . htmlspecialchars($criptomoeda['sigla']) . ')'; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                        <?php foreach ($criptomoedas as $criptomoeda): ?>
                            <td>
                                <input type="number" step="0.00000001" name="carteira[<?php echo $usuario['id']; ?>][<?php echo $criptomoeda['id']; ?>]" 
                                       value="<?php echo isset($carteiras[$usuario['id']][$criptomoeda['id']]) ? htmlspecialchars($carteiras[$usuario['id']][$criptomoeda['id']]) : '0'; ?>">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Atualizar Carteiras</button>
        </form>

        <h3>Últimas Transações</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>De</th>
                    <th>Para</th>
                    <th>Criptomoeda</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transacoes as $transacao): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transacao['data']); ?></td>
                    <td><?php echo htmlspecialchars($transacao['de_usuario_nome']); ?></td>
                    <td><?php echo htmlspecialchars($transacao['para_usuario_nome']); ?></td>
                    <td><?php echo htmlspecialchars($transacao['criptomoeda_nome']) . ' (' . htmlspecialchars($transacao['criptomoeda_sigla']) . ')'; ?></td>
                    <td><?php echo htmlspecialchars($transacao['quantidade']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="cadastro_transacao.php" class="btn btn-primary">Nova Transação</a>
    </main>
    <?php $conn->close(); ?>
</body>
</html>