<?php
require 'conexao.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'message' => 'E-mail e senha são obrigatórios.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($senha, $usuario['senha'])) {
    // Login bem-sucedido: armazena dados na sessão
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso!']);
} else {
    // Falha no login
    echo json_encode(['success' => false, 'message' => 'E-mail ou senha inválidos.']);
}
?>
