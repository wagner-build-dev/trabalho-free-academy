<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cliente'])) {
    header("Location: login.php?mensagem=Faça login para continuar");
    exit;
}
?>