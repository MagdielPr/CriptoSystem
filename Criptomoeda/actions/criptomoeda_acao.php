<?php
require_once __DIR__ . '/../config.php';
session_start(); // Iniciar a sessão

function salvarCriptomoeda($nome, $sigla) {
    $conn = getConnection();

    if (!$conn) {
        error_log("Erro ao conectar ao banco de dados.");
        return false;
    }

    $stmt = $conn->prepare("INSERT INTO criptomoedas (nome, sigla) VALUES (?, ?)");
    if (!$stmt) {
        error_log("Erro na preparação da declaração: " . $conn->error);
        $conn->close();
        return false;
    }

    $stmt->bind_param("ss", $nome, $sigla);
    $result = $stmt->execute();

    if ($result) {
        if ($stmt->affected_rows > 0) {
            $id = $stmt->insert_id;
            error_log("Criptomoeda salva com sucesso: ID " . $id);
        } else {
            error_log("Nenhuma linha afetada ao salvar criptomoeda");
            $result = false;
        }
    } else {
        error_log("Erro ao salvar criptomoeda: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
    return $result;
}

function atualizarCriptomoeda($id, $nome, $sigla) {
    $conn = getConnection();

    if (!$conn) {
        error_log("Erro ao conectar ao banco de dados.");
        return false;
    }

    $stmt = $conn->prepare("UPDATE criptomoedas SET nome = ?, sigla = ? WHERE id = ?");
    if (!$stmt) {
        error_log("Erro na preparação da declaração: " . $conn->error);
        $conn->close();
        return false;
    }

    $stmt->bind_param("ssi", $nome, $sigla, $id);
    $result = $stmt->execute();
    
    if ($result && $stmt->affected_rows > 0) {
        error_log("Criptomoeda atualizada com sucesso: ID " . $id);
    } else {
        error_log("Erro ao atualizar criptomoeda ou nenhuma linha afetada: " . $stmt->error);
        $result = false;
    }

    $stmt->close();
    $conn->close();
    return $result;
}

function carregarCriptomoeda($id) {
    $conn = getConnection();

    if (!$conn) {
        error_log("Erro ao conectar ao banco de dados.");
        return false;
    }

    $stmt = $conn->prepare("SELECT id, nome, sigla FROM criptomoedas WHERE id = ?");
    if (!$stmt) {
        error_log("Erro na preparação da declaração: " . $conn->error);
        $conn->close();
        return false;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $criptomoeda = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
    return $criptomoeda;
}

function excluirCriptomoeda($id) {
    $conn = getConnection();

    if (!$conn) {
        error_log("Erro ao conectar ao banco de dados.");
        return "Erro ao conectar ao banco de dados.";
    }

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM carteiras WHERE criptomoeda_id = ? AND quantidade > 0");
    if (!$stmt) {
        error_log("Erro na preparação da declaração: " . $conn->error);
        $conn->close();
        return "Erro na consulta de carteiras.";
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        $stmt->close();
        $conn->close();
        return "Não é possível excluir esta criptomoeda pois existem carteiras com saldo positivo associadas a ela.";
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM carteiras WHERE criptomoeda_id = ? AND quantidade = 0");
        if (!$stmt) {
            throw new Exception("Erro na preparação da declaração: " . $conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM criptomoedas WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Erro na preparação da declaração: " . $conn->error);
        }
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
        return "Erro ao excluir a criptomoeda: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sigla = $_POST['sigla'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if (empty($nome) || empty($sigla)) {
        $_SESSION['erro'] = "Nome e Sigla são obrigatórios";
        header('Location: ../cadastro_criptomoeda.php');
        exit();
    }

    if ($id == 0) {
        $result = salvarCriptomoeda($nome, $sigla);
        if ($result) {
            $_SESSION['mensagem'] = "Criptomoeda cadastrada com sucesso";
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar criptomoeda";
        }
    } else {
        $result = atualizarCriptomoeda($id, $nome, $sigla);
        if ($result) {
            $_SESSION['mensagem'] = "Criptomoeda atualizada com sucesso";
        } else {
            $_SESSION['erro'] = "Erro ao atualizar criptomoeda";
        }
    }

    header('Location: ../index.php');
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['acao']) && $_GET['acao'] == 'excluir') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        $result = excluirCriptomoeda($id);
        if ($result === true) {
            $_SESSION['mensagem'] = "Criptomoeda excluída com sucesso";
        } else {
            $_SESSION['erro'] = $result;
        }
    } else {
        $_SESSION['erro'] = "ID inválido para exclusão";
    }

    header('Location: ../index.php');
    exit();
}
