<?php // templates/veiculos/detalhe.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width:900px; margin:0 auto; padding:20px; }
        .voltar { color:#1565C0; text-decoration:none; }
        .detalhe { display:grid; grid-template-columns:minmax(280px,1fr) 1fr; gap:24px; margin-top:20px; }
        .detalhe img { width:100%; height:auto; max-height:420px; object-fit:cover; background:#eee; border-radius:8px; }
        .info { border:1px solid #ddd; border-radius:8px; padding:18px; }
        .info h1 { margin-top:0; color:#1A237E; }
        .preco { font-size:1.6rem; font-weight:bold; color:#1565C0; margin:16px 0; }
        dl { display:grid; grid-template-columns:120px 1fr; gap:8px 12px; }
        dt { font-weight:bold; color:#555; }
        dd { margin:0; }
        @media (max-width:700px) { .detalhe { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    <a class="voltar" href="/">Voltar ao catálogo</a>

    <div class="detalhe">
        <img src="<?= $veiculo['imagem'] ? '/uploads/'.htmlspecialchars($veiculo['imagem']) : '/img/placeholder.png' ?>"
             alt="<?= htmlspecialchars($veiculo['marca'].' '.$veiculo['modelo']) ?>">

        <section class="info">
            <h1><?= htmlspecialchars($veiculo['marca'].' '.$veiculo['modelo']) ?></h1>
            <div class="preco"><?= number_format($veiculo['preco'], 2, ',', '.') ?> €</div>

            <dl>
                <dt>Ano</dt>
                <dd><?= htmlspecialchars((string) $veiculo['ano']) ?></dd>

                <dt>Quilómetros</dt>
                <dd><?= number_format($veiculo['quilometros'], 0, '.', '.') ?> km</dd>

                <dt>Combustível</dt>
                <dd><?= htmlspecialchars($veiculo['combustivel']) ?></dd>
            </dl>
        </section>
    </div>
</body>
</html>
