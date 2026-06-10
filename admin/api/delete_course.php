<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit; }
require_once '../../config/conexao.php';

$id = intval($_POST['id'] ?? 0);
if ($id > 0) {
    $pdo->prepare("DELETE FROM produtos WHERE id=?")->execute([$id]);
}

header('Location: ../index.php');
exit;
