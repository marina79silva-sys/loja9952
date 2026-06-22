<?php
$veiculo = $veiculo ?? [];
$titulo = $titulo ?? 'Eliminar Veículo';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?></title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#eef2f8; color:#1f2937; }
        .container { width:min(600px,calc(100% - 32px)); margin:24px auto; }
        .card { background:#fff; border-radius:14px; box-shadow:0 18px 40px rgba(15,23,42,.08); padding:28px; }
        h1 { margin-top:0; font-size:2rem; color:#111827; }
        .warning { padding:16px; background:#fef3c7; border:1px solid #fcd34d; color:#92400e; border-radius:10px; margin-bottom:20px; }
        .info { padding:14px; background:#f0f9ff; border:1px solid #bae6fd; color:#0c4a6e; border-radius:8px; margin:16px 0; }
        .actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:20px; }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:12px 18px; border:none; border-radius:10px; cursor:pointer; font-weight:700; text-decoration:none; }
        .btn-danger { background:#dc2626; color:#fff; }
        .btn-cancel { background:#6B7280; color:#fff; }
        .btn:hover { opacity:0.9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Eliminar Veículo</h1>
            <div class="warning">
                ⚠️ <strong>Ação irreversível!</strong> Tem a certeza que deseja eliminar este veículo? Esta ação não pode ser desfeita.
            </div>
            
            <?php if (!empty($veiculo)): ?>
                <div class="info">
                    <strong><?= htmlspecialchars($veiculo['marca']) ?> <?= htmlspecialchars($veiculo['modelo']) ?></strong><br>
                    Ano <?= htmlspecialchars($veiculo['ano']) ?> · ID: <?= (int)$veiculo['id'] ?>
                </div>
            <?php endif; ?>

            <div class="actions">
                <form method="POST" style="display:flex; gap:12px; flex-wrap:wrap;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(function_exists('csrf_token') ? csrf_token() : '') ?>">
                    <button type="submit" class="btn btn-danger">Confirmar eliminação</button>
                    <a href="<?= htmlspecialchars(function_exists('url') ? url('admin/veiculos') : '/admin/veiculos') ?>" class="btn btn-cancel">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
