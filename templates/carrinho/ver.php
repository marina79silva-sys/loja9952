<?php // templates/carrinho/ver.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?> - AutoShop</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; color: #222; }
        a { color: #1565C0; }
        .topo { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; }
        .topo h1 { margin: 0; color: #1A237E; }
        .alerta { padding: 14px 16px; border: 1px solid #d9e2ec; border-radius: 8px; background: #f8fbff; color: #52606d; }
        .lista { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .lista th, .lista td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: middle; }
        .lista th { color: #52606d; font-size: .9rem; }
        .veiculo { display: flex; align-items: center; gap: 12px; }
        .veiculo img { width: 92px; height: 64px; object-fit: cover; border-radius: 6px; background: #eee; flex: 0 0 auto; }
        .marca { font-weight: bold; color: #1A237E; }
        .modelo { color: #52606d; margin-top: 2px; }
        .preco { font-weight: bold; color: #1565C0; white-space: nowrap; }
        .remover { background: #b91c1c; color: #fff; border: 0; border-radius: 4px; padding: 8px 12px; cursor: pointer; }
        .resumo { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-top: 22px; padding-top: 18px; border-top: 2px solid #e5e7eb; }
        .total { font-size: 1.05rem; font-weight: bold; }
        .prosseguir { background: #2E7D32; color: #fff; border: 0; border-radius: 4px; padding: 10px 16px; cursor: pointer; text-decoration: none; font-weight: bold; display: inline-block; }
        .prosseguir.vazio { background: #9ca3af; cursor: not-allowed; opacity: .75; }
        @media (max-width: 700px) {
            .topo, .resumo { align-items: flex-start; flex-direction: column; }
            .lista thead { display: none; }
            .lista tr { display: block; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
            .lista td { display: block; border: 0; padding: 6px 0; }
        }
    </style>
</head>
<body>
    <?php require dirname(__DIR__).'/header.php'; ?>

    <div class="topo">
        <h1><?= htmlspecialchars($titulo) ?></h1>
        <a href="/">Voltar ao catálogo</a>
    </div>

    <?php if (empty($veiculos)): ?>
        <div class="alerta">
            A tua lista de reservas está vazia.
        </div>
    <?php else: ?>
        <table class="lista">
            <thead>
                <tr>
                    <th>Veículo</th>
                    <th>Preço</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($veiculos as $veiculo): ?>
                    <tr>
                        <td>
                            <div class="veiculo">
                                <img src="<?= htmlspecialchars($veiculo['imagem'] ? url('uploads/'.$veiculo['imagem']) : url('img/placeholder.png')) ?>"
                                     alt="<?= htmlspecialchars($veiculo['marca'].' '.$veiculo['modelo']) ?>">
                                <div>
                                    <div class="marca"><?= htmlspecialchars($veiculo['marca']) ?></div>
                                    <div class="modelo"><?= htmlspecialchars($veiculo['modelo']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="preco"><?= number_format($veiculo['preco'], 2, ',', '.') ?> €</td>
                        <td>
                            <form method="POST" action="<?= htmlspecialchars(function_exists('url') ? url('carrinho/remover') : '/carrinho/remover') ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="veiculo_id" value="<?= (int) $veiculo['id'] ?>">
                                <button class="remover" type="submit">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>

    <div class="resumo">
        <div class="total">Total de veículos na lista: <?= count($veiculos) ?></div>
        <?php if (!empty($veiculos)): ?>
            <a href="<?= htmlspecialchars(function_exists('url') ? url('checkout') : '/checkout') ?>" class="prosseguir">Continuar para fechar a reserva</a>
        <?php else: ?>
            <button class="prosseguir vazio" type="button" disabled>Continuar para fechar a reserva</button>
        <?php endif; ?>
    </div>
</body>
</html>
