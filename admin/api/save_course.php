<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit; }
require_once '../../config/conexao.php';

$id          = intval($_POST['id'] ?? 0);
$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price       = floatval($_POST['price'] ?? 0);
$image_url   = trim($_POST['image_url'] ?? '');
$course_file = trim($_POST['course_file'] ?? '');

if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, image_url=?, course_file=? WHERE id=?");
    $stmt->execute([$name, $description, $price, $image_url, $course_file, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, image_url, course_file) VALUES (?,?,?,?,?)");
    $stmt->execute([$name, $description, $price, $image_url, $course_file]);
}

header('Location: ../index.php');
exit;