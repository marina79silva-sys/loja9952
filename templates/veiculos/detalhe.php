<?php // templates/veiculos/detalhe.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?> - AutoShop</title>
    <style>
        body { font-family: Arial, sans-serif; max-width:900px; margin:0 auto; padding:20px; }
        .voltar { color:#1565C0; text-decoration:none; display:inline-block; margin:18px 0; }
        .detalhe { display:grid; grid-template-columns:minmax(280px,1fr) 1fr; gap:24px; margin-top:20px; }
        .detalhe img { width:100%; height:auto; max-height:420px; object-fit:cover; background:#eee; border-radius:8px; }
        .info { border:1px solid #ddd; border-radius:8px; padding:18px; }
        .info h1 { margin-top:0; color:#1A237E; }
        .preco { font-size:1.6rem; font-weight:bold; color:#1565C0; margin:16px 0; }
        dl { display:grid; grid-template-columns:120px 1fr; gap:8px 12px; }
        dt { font-weight:bold; color:#555; }
        dd { margin:0; }
        .adicionar { background:#2E7D32; color:#fff; border:0; border-radius:4px; padding:10px 16px; cursor:pointer; margin-top:18px; }
        @media (max-width:700px) { .detalhe { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    <?php require '../templates/header.php'; ?>

    <a class="voltar" href="<?= htmlspecialchars(url()) ?>">Voltar ao catalogo</a>

    <div class="detalhe">
        <img src="<?= $veiculo['imagem'] ? '/uploads/'.htmlspecialchars($veiculo['imagem']) : '/img/placeholder.png' ?>"
             alt="<?= htmlspecialchars($veiculo['marca'].' '.$veiculo['modelo']) ?>">

        <section class="info">
            <h1><?= htmlspecialchars($veiculo['marca'].' '.$veiculo['modelo']) ?></h1>
            <div class="preco"><?= number_format($veiculo['preco'], 2, ',', '.') ?> EUR</div>

            <dl>
                <dt>Ano</dt>
                <dd><?= (int) $veiculo['ano'] ?></dd>

                <dt>Quilometros</dt>
                <dd><?= number_format($veiculo['quilometros'], 0, '.', '.') ?> km</dd>

                <dt>Combustivel</dt>
                <dd><?= htmlspecialchars($veiculo['combustivel']) ?></dd>

                <?php if ($veiculo['cilindrada']): ?>
                    <dt>Cilindrada</dt>
                    <dd><?= htmlspecialchars($veiculo['cilindrada']) ?></dd>
                <?php endif ?>
            </dl>

            <?php if ($veiculo['descricao']): ?>
                <h3>Descricao</h3>
                <p><?= nl2br(htmlspecialchars($veiculo['descricao'])) ?></p>
            <?php endif ?>

            <form method="POST" action="<?= htmlspecialchars(url('carrinho/adicionar')) ?>">
                <input type="hidden" name="veiculo_id" value="<?= (int) $veiculo['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <button class="adicionar" type="submit">Adicionar a lista de reservas</button>
            </form>
        </section>
    </div>
</body>
</html>
