<?php
require_once 'C:/xampp/htdocs/Treinamento/Criptomoeda/config.php';
session_start();

function atualizarUsuario($id, $nome, $email) {
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nome, $email, $id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function salvarUsuario($nome, $email) {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $email);
    $result = $stmt->execute();
    $id = $stmt->insert_id; // Obtém o ID do usuário recém-criado
    $stmt->close();
    $conn->close();
    return $id;
}

function carregar($id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $usuario;
}

function excluir($id) {
    $conn = getConnection();

    // Verifica se o usuário tem criptomoedas com quantidade maior que 0
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM carteiras WHERE usuario_id = ? AND quantidade > 0");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];

    if ($count > 0) {
        $stmt->close();
        $conn->close();
        return "Não é possível excluir o usuário pois ele possui criptomoedas em sua carteira.";
    }

    // Se não houver criptomoedas, prosseguir com a exclusão
    $conn->begin_transaction();

    try {
        // Excluir todas as carteiras associadas com quantidade 0
        $stmt = $conn->prepare("DELETE FROM carteiras WHERE usuario_id = ? AND quantidade = 0");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Excluir o usuário
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $conn->commit();
        $stmt->close();
        $conn->close();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        $stmt->close();
        $conn->close();
        return "Erro ao excluir o usuário: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        // Atualizar usuário existente
        if (atualizarUsuario($id, $nome, $email)) {
            $_SESSION['mensagem'] = "Usuário atualizado com sucesso.";
        } else {
            $_SESSION['erro'] = "Erro ao atualizar usuário.";
        }
    } else {
        // Criar novo usuário
        $newId = salvarUsuario($nome, $email);
        if ($newId) {
            $_SESSION['mensagem'] = "Usuário criado com sucesso.";
        } else {
            $_SESSION['erro'] = "Erro ao criar usuário.";
        }
    }
    header('Location: ../index.php');
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['acao']) && $_GET['acao'] == 'excluir') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        $result = excluir($id);
        if ($result === true) {
            $_SESSION['mensagem'] = "Usuário excluído com sucesso.";
        } else {
            $_SESSION['erro'] = $result;
        }
    } else {
        $_SESSION['erro'] = "ID de usuário inválido.";
    }
    header('Location: ../index.php');
    exit();
}
