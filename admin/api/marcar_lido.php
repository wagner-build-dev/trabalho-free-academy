<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: /login.php');
    exit;
}
require_once '../../config/conexao.php';

$id = intval($_POST['id'] ?? 0);
if ($id) {
    $pdo->prepare("UPDATE contatos SET lido = 1 WHERE id = ?")->execute([$id]);
}

header('Location: /free-academy/admin/');
exit;