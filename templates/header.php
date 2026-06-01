<?php // templates/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$total_carrinho = count($_SESSION['carrinho'] ?? []);
$home_url = function_exists('url') ? url() : '/';
$carrinho_url = function_exists('url') ? url('carrinho') : '/carrinho';
?>
<header style="background:#1A237E;color:#fff;padding:14px 24px;display:flex;justify-content:space-between;align-items:center;">
    <a href="<?= htmlspecialchars($home_url) ?>" style="color:#fff;font-size:1.3rem;font-weight:bold;text-decoration:none;">AutoShop</a>
    <nav style="display:flex;gap:20px;align-items:center;">
        <a href="<?= htmlspecialchars($home_url) ?>" style="color:#fff;text-decoration:none;">Catalogo</a>
        <a href="<?= htmlspecialchars($carrinho_url) ?>" style="color:#fff;text-decoration:none;">
            Lista (<?= $total_carrinho ?>)
        </a>
        <?php if ($_SESSION['logado'] ?? false): ?>
            <a href="/conta" style="color:#fff;text-decoration:none;">A minha conta</a>
            <a href="/logout" style="color:#ccc;text-decoration:none;">Sair</a>
        <?php else: ?>
            <a href="/login" style="color:#fff;text-decoration:none;">Entrar</a>
        <?php endif ?>
    </nav>
</header>
