<?php
require 'conexao.php';

header('Content-Type: application/json');

try {
    // 1. Cálculos de Materiais
    $total_materiais_query = $pdo->query("SELECT SUM(preco_unitario * quantidade) as total FROM materiais");
    $total_materiais = $total_materiais_query->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    $pago_materiais_query = $pdo->query("SELECT SUM(preco_unitario * quantidade) as total FROM materiais WHERE status = 'Comprado'");
    $pago_materiais = $pago_materiais_query->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // 2. Cálculos de Mão de Obra
    $total_servicos_query = $pdo->query("SELECT SUM(custo) as total FROM mao_de_obra");
    $total_servicos = $total_servicos_query->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    $pago_servicos_query = $pdo->query("SELECT SUM(custo) as total FROM mao_de_obra WHERE status = 'Pago'");
    $pago_servicos = $pago_servicos_query->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // 3. Cálculos Gerais da Obra
    $total_obra = $total_materiais + $total_servicos;
    $pago_obra = $pago_materiais + $pago_servicos;

    // 4. Monta a resposta
    $response = [
        'success' => true,
        'totais' => [
            'obra' => [
                'total' => (float)$total_obra,
                'pago' => (float)$pago_obra
            ],
            'materiais' => [
                'total' => (float)$total_materiais,
                'pago' => (float)$pago_materiais
            ],
            'servicos' => [
                'total' => (float)$total_servicos,
                'pago' => (float)$pago_servicos
            ]
        ]
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
