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
    $stmt = $pdo->prepare("SELECT id, nome, unidade, preco_unitario, quantidade, status, data_cadastro FROM materiais WHERE usuario_id = ? ORDER BY data_cadastro DESC");
    $stmt->execute([$usuario_id]);
    $materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($materiais);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor ao buscar materiais.']);
}
?>