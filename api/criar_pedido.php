<?php
session_start();
require_once '../config/conexao.php';

// Headers CORS para funcionar via ngrok/subdomínio
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
header('Access-Control-Allow-Origin: ' . $origin);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Verifica autenticação
if (!isset($_SESSION['cliente'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Não autorizado - sessão não encontrada']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['customer_name'], $data['customer_email'], $data['courses'], $data['total'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos']);
    exit;
}

try {
    // Insere pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, total) VALUES (?, ?)");
    $stmt->execute([$_SESSION['cliente']['id'], $data['total']]);
    $pedidoId = $pdo->lastInsertId();

    // Insere itens
    $stmtItem = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, 1, ?)");
    foreach ($data['courses'] as $course) {
        $stmtItem->execute([$pedidoId, $course['course_id'], $course['price']]);
    }

    // Busca course_file de cada produto para enviar no email
    $coursesWithFiles = [];
    foreach ($data['courses'] as $course) {
        $stmtCourse = $pdo->prepare("SELECT course_file FROM produtos WHERE id = ?");
        $stmtCourse->execute([$course['course_id']]);
        $produto = $stmtCourse->fetch();
        $coursesWithFiles[] = [
            'course_name' => $course['course_name'],
            'course_file' => $produto['course_file'] ?? ''
        ];
    }

    // Chama a função de envio de email
    $appId = getenv('BASE44_APP_ID');
    $emailPayload = json_encode([
        'customerName'  => $data['customer_name'],
        'customerEmail' => $data['customer_email'],
        'courses'       => $coursesWithFiles,
        'total'         => $data['total']
    ]);
    @file_get_contents(
        "https://api.base44.com/api/apps/{$appId}/functions/sendCourseEmail",
        false,
        stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n",
                'content' => $emailPayload,
                'timeout' => 10,
                'ignore_errors' => true,
            ]
        ])
    );

    echo json_encode(['sucesso' => true, 'pedido_id' => $pedidoId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}
