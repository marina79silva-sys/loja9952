<?php // templates/conta/ver.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo ?? 'A minha conta') ?> - AutoShop</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; color: #222; }
        main { margin-top: 32px; }
        .painel { border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: #fff; }
        .boas-vindas { color: #1A237E; margin-top: 0; }
        .dados { margin: 18px 0; line-height: 1.7; }
        .reservas { margin-top: 24px; padding: 18px; border: 1px dashed #cbd5e1; border-radius: 8px; color: #64748b; background: #f8fafc; }
        .reserva-item { background: #fff; border: 1px solid #e2e8f0; padding: 10px; margin-bottom: 10px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; }
        .reserva-info strong { color: #334155; }
        .badge-reservado { background: #fee2e2; color: #b91c1c; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold; }
        .vazio { font-style: italic; }
        .btn-anular { background: #dc3545; color: #fff; padding: 6px 12px; border: 0; border-radius: 4px; cursor: pointer; font-size: 0.9rem; }
        .btn-anular:hover { background: #c82333; }
    </style>
</head>
<body>
    <?php require dirname(__DIR__).'/header.php'; ?>

    <main>
        <?php
            $nome = $_SESSION['cliente_nome'] ?? ($cliente['nome'] ?? 'Cliente');
            $email = $_SESSION['cliente_email'] ?? ($cliente['email'] ?? '');
        ?>

        <section class="painel">
            <h1 class="boas-vindas">Bem-vindo, <?= htmlspecialchars($nome) ?>!</h1>

            <div class="dados">
                <div><strong>Nome:</strong> <?= htmlspecialchars($nome) ?></div>
                <div><strong>Email:</strong> <?= htmlspecialchars($email) ?></div>
            </div>

            <div class="reservas">
                <h3>As Minhas Reservas</h3>
                <?php if (empty($reservas)): ?>
                    <p class="vazio">Ainda não tens veículos reservados.</p>
                <?php else: ?>
                    <?php foreach ($reservas as $res): ?>
                        <div class="reserva-item">
                            <div class="reserva-info">
                                <strong><?= htmlspecialchars($res['marca'] . ' ' . $res['modelo']) ?></strong><br>
                                <small>Ano: <?= $res['ano'] ?> | Preço: <?= number_format($res['preco'], 2, ',', '.') ?>€</small><br>
                                <small>Data: <?= date('d/m/Y H:i', strtotime($res['criado_em'])) ?></small>
                            </div>
                            <div style="display:flex;gap:10px;align-items:center;">
                                <span class="badge-reservado">RESERVADO</span>
                                <form method="POST" action="<?= url('reserva/anular/' . (int) $res['id']) ?>" style="margin:0;">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <button type="submit" class="btn-anular" onclick="return confirm('Tem certeza que deseja anular esta reserva?');">Anular</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
