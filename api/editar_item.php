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
        $sql = "UPDATE materiais SET nome = ?, unidade = ?, preco_unitario = ?, quantidade = ? WHERE id = ? AND usuario_id = ?";
        $params = [
            $data['nome_material'],
            $data['unidade_material'],
            $data['preco_material'],
            $data['quantidade_material'],
            $id,
            $usuario_id // Adiciona o usuario_id na condição WHERE
        ];
    } elseif ($tipo === 'servico') {
        $sql = "UPDATE mao_de_obra SET descricao_servico = ?, detalhes_servico = ?, custo = ? WHERE id = ? AND usuario_id = ?";
        $params = [
            $data['descricao_servico'],
            $data['detalhes_servico'],
            $data['custo_servico'],
            $id,
            $usuario_id // Adiciona o usuario_id na condição WHERE
        ];
    } else {
        throw new Exception("Tipo de item inválido.");
    }

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        if ($stmt->rowCount() > 0) { // Verifica se alguma linha foi afetada (item encontrado e pertence ao usuário)
            echo json_encode(['success' => true, 'message' => 'Item atualizado com sucesso!']);
        } else {
            throw new Exception("Nenhum item encontrado com o ID fornecido ou você não tem permissão para editá-lo.");
        }
    } else {
        throw new Exception("Falha ao executar a atualização.");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>