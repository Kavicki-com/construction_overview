<?php
require 'conexao.php';

// O status inicial é sempre 'Pendente'
$nome = $_POST['nome_material'] ?? '';
$unidade = $_POST['unidade_material'] ?? '';
$preco = $_POST['preco_material'] ?? 0;
$quantidade = $_POST['quantidade_material'] ?? 0;

if ($nome && $unidade && $preco > 0 && $quantidade > 0) {
    $sql = "INSERT INTO materiais (nome, unidade, preco_unitario, quantidade) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nome, $unidade, $preco, $quantidade])) {
        echo json_encode(['success' => true, 'message' => 'Material cadastrado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar material.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
}
?>