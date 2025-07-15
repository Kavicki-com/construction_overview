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
    // Seleciona todos os serviços, ordenando pelos mais recentes primeiro
    $stmt = $pdo->prepare("SELECT id, descricao_servico, detalhes_servico, custo, status, data_cadastro FROM mao_de_obra WHERE usuario_id = ? ORDER BY data_cadastro DESC");
    $stmt->execute([$usuario_id]);
    $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($servicos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor ao buscar serviços: ' . $e->getMessage()]);
}
?>