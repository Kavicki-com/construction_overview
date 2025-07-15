<?php
require 'conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$tipo = $data['tipo'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

if (!$id || !$tipo) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID ou Tipo não fornecido.']);
    exit;
}

try {
    if ($tipo === 'material') {
        $sql = "DELETE FROM materiais WHERE id = ? AND usuario_id = ?";
    } elseif ($tipo === 'servico') {
        $sql = "DELETE FROM mao_de_obra WHERE id = ? AND usuario_id = ?";
    } else {
        throw new Exception("Tipo de item inválido.");
    }

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$id, $usuario_id])) {
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Item excluído com sucesso!']);
        } else {
            throw new Exception("Nenhum item encontrado com o ID fornecido ou você não tem permissão para excluí-lo.");
        }
    } else {
        throw new Exception("Falha ao executar a exclusão.");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>