<?php
$erro = $erro ?? '';
$titulo = $titulo ?? 'Login Administrativo';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?></title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:linear-gradient(135deg, #1D4ED8 0%, #1A237E 100%); display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .container { width:min(420px,calc(100% - 32px)); }
        .card { background:#fff; border-radius:14px; box-shadow:0 24px 48px rgba(0,0,0,.2); padding:28px; }
        h1 { margin:0 0 8px; font-size:1.8rem; color:#111827; text-align:center; }
        .subtitle { text-align:center; color:#6B7280; margin-bottom:24px; font-size:.95rem; }
        .error { padding:14px; background:#fee2e2; border:1px solid #fecaca; color:#991b1b; border-radius:8px; margin-bottom:20px; }
        form { display:grid; gap:16px; }
        .field { display:flex; flex-direction:column; gap:6px; }
        label { font-weight:700; color:#111827; font-size:.95rem; }
        input { padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#f8fafc; font-size:1rem; color:#111827; }
        input:focus { outline:none; border-color:#1D4ED8; background:#fff; }
        button { padding:12px 16px; background:#1D4ED8; color:#fff; border:none; border-radius:10px; font-weight:700; cursor:pointer; font-size:1rem; }
        button:hover { background:#1D4ED8; opacity:0.95; }
        .footer { text-align:center; margin-top:18px; color:#6B7280; font-size:.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🔐 Painel Admin</h1>
            <p class="subtitle">Acesso restrito a administradores</p>

            <?php if (!empty($erro)): ?>
                <div class="error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars(function_exists('url') ? url('admin/login') : '/admin/login') ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(function_exists('csrf_token') ? csrf_token() : '') ?>">
                
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required autofocus value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="field">
                    <label for="password">Palavra-passe</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <button type="submit">Entrar</button>
            </form>

            <div class="footer">
                <p>AutoShop — Sistema de Gestão</p>
            </div>
        </div>
    </div>
</body>
</html>
