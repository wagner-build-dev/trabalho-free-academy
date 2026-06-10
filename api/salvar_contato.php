<?php
session_start();
require_once '../config/conexao.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Aceita tanto POST form quanto JSON
if (!$data) {
    $data = $_POST;
}

if (empty($data['nome']) || empty($data['email']) || empty($data['mensagem'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Campos obrigatórios faltando']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO contatos (nome, email, mensagem) VALUES (?, ?, ?)");
    $stmt->execute([
        trim($data['nome']),
        trim($data['email']),
        trim($data['mensagem'])
    ]);
    echo json_encode(['sucesso' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}