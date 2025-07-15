<?php
require 'conexao.php';

// Pega os dados da requisição (enviados como JSON)
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;
$status = $data['status'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

// Lista de status permitidos foi ajustada
$statusPermitidos = ['Pendente', 'Pago'];

if ($id && $status && in_array($status, $statusPermitidos)) {
    try {
        $sql = "UPDATE mao_de_obra SET status = ? WHERE id = ? AND usuario_id = ?";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$status, $id, $usuario_id])) {
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Status do serviço atualizado com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Serviço não encontrado ou você não tem permissão para alterá-lo.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o status do serviço.']);
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