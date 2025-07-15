<?php
require 'conexao.php';

header('Content-Type: application/json');

try {
    // Seleciona todos os serviços, ordenando pelos mais recentes primeiro
    $stmt = $pdo->query("SELECT id, descricao_servico, custo, status, data_cadastro FROM mao_de_obra ORDER BY data_cadastro DESC");
    $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($servicos);
} catch (PDOException $e) {
    // Em caso de erro, retorna uma resposta de erro em JSON
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor ao buscar serviços.']);
}
?>
