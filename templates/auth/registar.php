<?php // templates/auth/registar.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo ?? 'Criar conta') ?> - AutoShop</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; color: #222; }
        main { max-width: 420px; margin: 32px auto; }
        label { display: block; font-weight: bold; margin-top: 14px; }
        input { width: 100%; box-sizing: border-box; padding: 9px; border: 1px solid #ccc; border-radius: 4px; margin-top: 5px; }
        button { background: #1565C0; color: #fff; border: 0; border-radius: 4px; padding: 10px 16px; margin-top: 18px; cursor: pointer; }
        .erros { color: #b91c1c; margin: 0 0 18px; padding-left: 20px; }
        .alternar { margin-top: 18px; }
        .alternar a { color: #1565C0; }
    </style>
</head>
<body>
    <?php require dirname(__DIR__).'/header.php'; ?>

    <main>
        <h1>Criar conta</h1>

        <?php if (!empty($erros)): ?>
            <ul class="erros">
                <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>

        <form method="POST" action="<?= htmlspecialchars(function_exists('url') ? url('registar') : '/registar') ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

            <label for="nome">Nome</label>
            <input id="nome" type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <label for="telefone">Telefone</label>
            <input id="telefone" type="tel" name="telefone" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <label for="password2">Confirmar password</label>
            <input id="password2" type="password" name="password2" required>

            <button type="submit">Registar</button>
        </form>

        <p class="alternar">
            Ja tens conta?
            <a href="<?= htmlspecialchars(function_exists('url') ? url('login') : '/login') ?>">Entrar</a>
        </p>
    </main>
</body>
</html>
