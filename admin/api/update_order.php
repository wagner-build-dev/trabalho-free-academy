<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit; }
require_once '../../config/conexao.php';

$id     = intval($_POST['id'] ?? 0);
$status = in_array($_POST['status'] ?? '', ['completed', 'pending']) ? $_POST['status'] : 'pending';

if ($id > 0) {
    $pdo->prepare("UPDATE pedidos SET status=? WHERE id=?")->execute([$status, $id]);
}

header('Location: ../index.php');
exit;