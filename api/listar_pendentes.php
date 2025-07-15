<?php
require 'conexao.php';

header('Content-Type: application/json');

$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

try {
    $materiais_pendentes_stmt = $pdo->prepare("SELECT id, nome, quantidade, unidade, preco_unitario FROM materiais WHERE status = 'Pendente' AND usuario_id = ? ORDER BY data_cadastro DESC");
    $materiais_pendentes_stmt->execute([$usuario_id]);
    $materiais_pendentes = $materiais_pendentes_stmt->fetchAll(PDO::FETCH_ASSOC);

    $servicos_pendentes_stmt = $pdo->prepare("SELECT id, descricao_servico, custo, detalhes_servico FROM mao_de_obra WHERE status = 'Pendente' AND usuario_id = ? ORDER BY data_cadastro DESC");
    $servicos_pendentes_stmt->execute([$usuario_id]);
    $servicos_pendentes = $servicos_pendentes_stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'materiais' => $materiais_pendentes,
        'servicos' => $servicos_pendentes
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor ao buscar listas pendentes: ' . $e->getMessage()]);
}
?>