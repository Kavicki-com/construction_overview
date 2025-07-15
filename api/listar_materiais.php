<?php
require 'conexao.php';

header('Content-Type: application/json');

try {
    // Seleciona todos os materiais, ordenando pelos mais recentes primeiro
    $stmt = $pdo->query("SELECT id, nome, unidade, preco_unitario, quantidade, status, data_cadastro FROM materiais ORDER BY data_cadastro DESC");
    $materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($materiais);
} catch (PDOException $e) {
    // Em caso de erro, retorna uma resposta de erro em JSON
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor ao buscar materiais.']);
}
?>
