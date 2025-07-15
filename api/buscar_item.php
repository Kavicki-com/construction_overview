<?php
require 'conexao.php';

header('Content-Type: application/json');

$tipo = $_GET['tipo'] ?? null;
$id = $_GET['id'] ?? null;

if (!$tipo || !$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tipo ou ID não fornecido.']);
    exit;
}

if ($tipo === 'material') {
    $stmt = $pdo->prepare("SELECT * FROM materiais WHERE id = ?");
} elseif ($tipo === 'servico') {
    $stmt = $pdo->prepare("SELECT * FROM mao_de_obra WHERE id = ?");
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tipo inválido.']);
    exit;
}

$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    echo json_encode(['success' => true, 'data' => $item]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Item não encontrado.']);
}
?>
