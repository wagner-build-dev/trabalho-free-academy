<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$quantidadeCarrinho = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $quantidade) {
        $quantidadeCarrinho += $quantidade;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Academy</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="header">
    <a class="logo-link" href="index.php">
        <img class="logo-img" src="https://media.base44.com/images/public/6a28c8df52b234e765db2246/a1172fc07_Gemini_Generated_Image_kahyi2kahyi2kahy.png" alt="Free Academy" />
    </a>
    <nav class="nav">
        <a class="nav-link" href="index.php">Home</a>
        <a class="nav-link" href="loja.php">Loja</a>
        <a class="nav-link" href="quem-somos.php">Quem Somos</a>
        <a class="nav-link" href="contato.php">Contato</a>
        <a class="nav-link" href="meus_pedidos.php">Pedidos</a>
        <a class="btn-cart" href="carrinho.php">🛒 Carrinho
            <?php if ($quantidadeCarrinho > 0): ?>
                <span class="cart-badge"><?php echo $quantidadeCarrinho; ?></span>
            <?php endif; ?>
        </a>
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
            <a class="btn-admin" href="admin/index.php">Admin</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['cliente'])): ?>
            <div class="user-info">
                <span class="btn-user">👤 <?php echo htmlspecialchars(explode(' ', $_SESSION['cliente']['name'] ?? $_SESSION['cliente']['nome'] ?? 'Usuário')[0]); ?></span>
                <a class="btn-logout" href="logout.php">↩ Sair</a>
            </div>
        <?php else: ?>
            <a class="nav-link" href="login.php">Login / Cadastro</a>
        <?php endif; ?>
    </nav>
</header>
<main>