<?php // templates/auth/login.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo ?? 'Iniciar sessao') ?> - AutoShop</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; color: #222; }
        main { max-width: 420px; margin: 32px auto; }
        label { display: block; font-weight: bold; margin-top: 14px; }
        input { width: 100%; box-sizing: border-box; padding: 9px; border: 1px solid #ccc; border-radius: 4px; margin-top: 5px; }
        button { background: #1565C0; color: #fff; border: 0; border-radius: 4px; padding: 10px 16px; margin-top: 18px; cursor: pointer; }
        .erro { color: #b91c1c; margin: 0 0 18px; }
        .sucesso { color: #166534; margin: 0 0 18px; }
        .alternar { margin-top: 18px; }
        .alternar a { color: #1565C0; }
    </style>
</head>
<body>
    <?php require dirname(__DIR__).'/header.php'; ?>

    <main>
        <h1>Iniciar sessao</h1>

        <?php if (!empty($_SESSION['msg_ok'])): ?>
            <p class="sucesso"><?= htmlspecialchars($_SESSION['msg_ok']) ?></p>
            <?php unset($_SESSION['msg_ok']); ?>
        <?php endif ?>

        <?php if (!empty($erro)): ?>
            <p class="erro"><?= htmlspecialchars($erro) ?></p>
        <?php endif ?>

        <form method="POST" action="<?= htmlspecialchars(function_exists('url') ? url('login') : '/login') ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <button type="submit">Entrar</button>
        </form>

        <p class="alternar">
            Ainda nao tens conta?
            <a href="<?= htmlspecialchars(function_exists('url') ? url('registar') : '/registar') ?>">Criar conta</a>
        </p>
    </main>
</body>
</html>
