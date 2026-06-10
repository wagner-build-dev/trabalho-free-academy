<?php
include "includes/proteger.php";
include "config/conexao.php";

if (empty($_SESSION['carrinho'])) {
    header("Location: carrinho.php");
    exit;
}

$total = 0;
$itens = [];

foreach ($_SESSION['carrinho'] as $produtoId => $quantidade) {
    $sql = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
    $sql->bind_param("i", $produtoId);
    $sql->execute();
    $produto = $sql->get_result()->fetch_assoc();

    if ($produto) {
        $subtotal = $produto['preco'] * $quantidade;
        $total += $subtotal;
        $itens[] = [
            'produto' => $produto,
            'quantidade' => $quantidade,
            'subtotal' => $subtotal
        ];
    }
}

$clienteId = $_SESSION['cliente_id'];
$sqlPedido = $conexao->prepare("INSERT INTO pedidos (cliente_id, total) VALUES (?, ?)");
$sqlPedido->bind_param("id", $clienteId, $total);
$sqlPedido->execute();
$pedidoId = $conexao->insert_id;

$mensagemEmail = "Olá, " . $_SESSION['cliente_nome'] . "!\n\nSua compra na Free Academy foi finalizada.\n\nCursos adquiridos:\n";

foreach ($itens as $item) {
    $produtoId = $item['produto']['id'];
    $quantidade = $item['quantidade'];
    $preco = $item['produto']['preco'];

    $sqlItem = $conexao->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    $sqlItem->bind_param("iiid", $pedidoId, $produtoId, $quantidade, $preco);
    $sqlItem->execute();

    $mensagemEmail .= "- " . $item['produto']['nome'] . " | Arquivo: http://localhost/free-academy-xampp/" . $item['produto']['arquivo_curso'] . "\n";
}

$mensagemEmail .= "\nTotal: R$ " . number_format($total, 2, ',', '.') . "\n\nObrigado por estudar com a Free Academy!";

$para = $_SESSION['cliente_email'];
$assunto = "Seu curso Free Academy";
$cabecalho = "From: freeacademy@localhost";
@mail($para, $assunto, $mensagemEmail, $cabecalho);

$_SESSION['carrinho'] = [];

include "includes/header.php";
?>

<section class="secao">
    <span class="kicker">Pedido finalizado</span>
    <h2>Compra realizada com sucesso.</h2>
    <p class="texto-grande">O pedido número <strong>#<?php echo $pedidoId; ?></strong> foi salvo no banco de dados. O sistema também tentou enviar os links dos cursos para o e-mail cadastrado.</p>
    <p class="texto-grande">Observação para XAMPP: para o envio real funcionar, o recurso de e-mail do PHP precisa estar configurado no computador.</p>
    <div class="acoes">
        <a class="botao" href="meus_pedidos.php">Ver meus pedidos</a>
        <a class="botao secundario" href="loja.php">Comprar mais cursos</a>
    </div>
</section>

<?php include "includes/footer.php"; ?>