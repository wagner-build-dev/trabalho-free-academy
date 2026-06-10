<?php
include "includes/proteger.php";
include "config/conexao.php";
include "includes/header.php";

$clienteId = $_SESSION['cliente_id'];
$sql = $conexao->prepare("SELECT * FROM pedidos WHERE cliente_id = ? ORDER BY data_pedido DESC");
$sql->bind_param("i", $clienteId);
$sql->execute();
$pedidos = $sql->get_result();
?>

<section class="secao">
    <span class="kicker">Consulta de pedidos</span>
    <h2>Meus pedidos</h2>

    <div class="tabela-box">
        <table>
            <tr>
                <th>Número</th>
                <th>Data</th>
                <th>Total</th>
                <th>Itens</th>
            </tr>
            <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $pedido['id']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                    <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                    <td>
                        <?php
                        $itensSql = $conexao->prepare("SELECT produtos.nome, itens_pedido.quantidade FROM itens_pedido INNER JOIN produtos ON produtos.id = itens_pedido.produto_id WHERE itens_pedido.pedido_id = ?");
                        $itensSql->bind_param("i", $pedido['id']);
                        $itensSql->execute();
                        $itens = $itensSql->get_result();
                        while ($item = $itens->fetch_assoc()) {
                            echo htmlspecialchars($item['nome']) . " (" . $item['quantidade'] . ")<br>";
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</section>

<?php include "includes/footer.php"; ?>