<?php
require 'conexao.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$nome = $data['nome'] ?? '';
$email = $data['email'] ?? '';
$telefone = $data['telefone'] ?? '';
$senha = $data['senha'] ?? '';

if (empty($nome) || empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Formato de e-mail inválido.']);
    exit;
}

// Verifica se o e-mail já existe
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
    exit;
}

// Criptografa a senha
$senhaHash = password_hash($senha, PASSWORD_BCRYPT);

$sql = "INSERT INTO usuarios (nome, email, telefone, senha) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$nome, $email, $telefone, $senhaHash])) {
    echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao realizar o cadastro.']);
}
?>
