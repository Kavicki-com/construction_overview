<?php
require 'conexao.php';

// Pega os dados da requisição (enviados como JSON)
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;
$status = $data['status'] ?? null;

// Lista de status permitidos foi ajustada
$statusPermitidos = ['Pendente', 'Comprado'];

if ($id && $status && in_array($status, $statusPermitidos)) {
    try {
        $sql = "UPDATE materiais SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$status, $id])) {
            echo json_encode(['success' => true, 'message' => 'Status do material atualizado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o status do material.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos ou status não permitido.']);
}
?>
