<?php
require 'conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$tipo = $data['tipo'] ?? null;

if (!$id || !$tipo) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID ou Tipo não fornecido.']);
    exit;
}

try {
    if ($tipo === 'material') {
        $sql = "UPDATE materiais SET nome = ?, unidade = ?, preco_unitario = ?, quantidade = ? WHERE id = ?";
        $params = [
            $data['nome_material'],
            $data['unidade_material'],
            $data['preco_material'],
            $data['quantidade_material'],
            $id
        ];
    } elseif ($tipo === 'servico') {
        $sql = "UPDATE mao_de_obra SET descricao_servico = ?, detalhes_servico = ?, custo = ? WHERE id = ?";
        $params = [
            $data['descricao_servico'],
            $data['detalhes_servico'],
            $data['custo_servico'],
            $id
        ];
    } else {
        throw new Exception("Tipo de item inválido.");
    }

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        echo json_encode(['success' => true, 'message' => 'Item atualizado com sucesso!']);
    } else {
        throw new Exception("Falha ao executar a atualização.");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>
