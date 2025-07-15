<?php
require 'conexao.php';

header('Content-Type: application/json');

$materiais_pendentes = $pdo->query("SELECT id, nome, quantidade, unidade, preco_unitario FROM materiais WHERE status = 'Pendente' ORDER BY data_cadastro DESC")->fetchAll(PDO::FETCH_ASSOC);

$servicos_pendentes = $pdo->query("SELECT id, descricao_servico, custo FROM mao_de_obra WHERE status = 'Pendente' ORDER BY data_cadastro DESC")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'materiais' => $materiais_pendentes,
    'servicos' => $servicos_pendentes
]);
?>