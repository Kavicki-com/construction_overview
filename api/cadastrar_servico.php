<?php
require 'conexao.php';

$descricao = $_POST['descricao_servico'] ?? '';
$detalhes = $_POST['detalhes_servico'] ?? null; // Campo adicionado em index.php
$custo = $_POST['custo_servico'] ?? 0;
$usuario_id = $_SESSION['usuario_id'] ?? null; // Adiciona o ID do usuário logado

if (!$usuario_id) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

if ($descricao && $custo > 0) {
    $sql = "INSERT INTO mao_de_obra (descricao_servico, detalhes_servico, custo, usuario_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$descricao, $detalhes, $custo, $usuario_id])) {
        echo json_encode(['success' => true, 'message' => 'Serviço cadastrado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar serviço.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
}
?>