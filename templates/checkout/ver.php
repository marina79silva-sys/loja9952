<?php // templates/checkout/ver.php ?>
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
        .alerta { padding: 14px 16px; border: 1px solid #f5c542; border-radius: 8px; background: #fff8db; color: #6b4f00; margin-bottom: 18px; }
        .lista { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .lista th, .lista td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: middle; }
        .lista th { color: #52606d; font-size: .9rem; }
        .veiculo { font-weight: bold; color: #1A237E; }
        .modelo { color: #52606d; margin-top: 2px; }
        .preco { font-weight: bold; color: #1565C0; white-space: nowrap; }
        .campo { margin-top: 22px; }
        .campo label { display: block; margin-bottom: 8px; font-weight: bold; color: #1A237E; }
        .campo textarea { width: 100%; min-height: 110px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font: inherit; resize: vertical; box-sizing: border-box; }
        .resumo { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-top: 22px; padding-top: 18px; border-top: 2px solid #e5e7eb; }
        .total { font-size: 1.05rem; font-weight: bold; }
        .acoes { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .confirmar { background: #2E7D32; color: #fff; border: 0; border-radius: 4px; padding: 10px 16px; cursor: pointer; font-weight: bold; }
        .voltar { display: inline-block; padding: 10px 0; }
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
        <a href="<?= htmlspecialchars(function_exists('url') ? url('carrinho') : '/carrinho') ?>">Voltar ao carrinho</a>
    </div>

    <div class="alerta">
        Aviso: Esta &eacute; uma reserva simulada &mdash; sem pagamento online.
    </div>

    <form method="POST" action="<?= htmlspecialchars(function_exists('url') ? url('checkout/confirmar') : '/checkout/confirmar') ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

        <table class="lista">
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Pre&ccedil;o</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($veiculos as $veiculo): ?>
                    <tr>
                        <td class="veiculo"><?= htmlspecialchars($veiculo['marca']) ?></td>
                        <td class="modelo"><?= htmlspecialchars($veiculo['modelo']) ?></td>
                        <td class="preco"><?= number_format((float) $veiculo['preco'], 2, ',', '.') ?> EUR</td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <div class="campo">
            <label for="mensagem">Informa&ccedil;&otilde;es adicionais para o vendedor</label>
            <textarea id="mensagem" name="mensagem" placeholder="Opcional"></textarea>
        </div>

        <div class="resumo">
            <div class="total">Total de ve&iacute;culos a reservar: <?= count($veiculos) ?></div>
            <div class="acoes">
                <a class="voltar" href="<?= htmlspecialchars(function_exists('url') ? url('carrinho') : '/carrinho') ?>">Voltar ao carrinho</a>
                <button class="confirmar" type="submit">Confirmar reserva</button>
            </div>
        </div>
    </form>
</body>
</html>
