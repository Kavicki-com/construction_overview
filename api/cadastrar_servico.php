<?php
require 'conexao.php';

$descricao = $_POST['descricao_servico'] ?? '';
$custo = $_POST['custo_servico'] ?? 0;

if ($descricao && $custo > 0) {
    $sql = "INSERT INTO mao_de_obra (descricao_servico, custo) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$descricao, $custo])) {
        echo json_encode(['success' => true, 'message' => 'Serviço cadastrado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar serviço.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
}
?>