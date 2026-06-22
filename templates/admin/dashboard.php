<?php
// Painel administrativo do dashboard
$totalVeic = $totalVeic ?? 0;
$totalRes = $totalRes ?? 0;
$pendentes = $pendentes ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo ?? 'Dashboard') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:24px; }
        .dashboard-card { max-width:900px; margin:0 auto; background:#fff; padding:24px; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.08); }
        .dashboard-card h1 { margin-top:0; font-size:1.9rem; color:#222; }
        .dashboard-table { width:100%; border-collapse:collapse; margin-top:20px; }
        .dashboard-table th, .dashboard-table td { border:1px solid #ddd; padding:14px 16px; text-align:left; }
        .dashboard-table th { background:#1A237E; color:#fff; }
        .dashboard-table tr:nth-child(even) { background:#fafafa; }
        .dashboard-table a { color:#1A237E; text-decoration:none; font-weight:600; }
        .dashboard-table a:hover { text-decoration:underline; }
        .dashboard-description { margin:10px 0 0; color:#555; }
    </style>
</head>
<body>
    <div class="dashboard-card">
        <h1>Resumo do Painel</h1>
        <p class="dashboard-description">Veja o total de veículos, reservas e reservas pendentes, com acesso direto às respetivas secções administrativas.</p>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Indicador</th>
                    <th>Total</th>
                    <th>Secção</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total de veículos</td>
                    <td><?= intval($totalVeic) ?></td>
                    <td><a href="<?= htmlspecialchars(function_exists('url') ? url('admin/veiculos') : '/admin/veiculos') ?>">Ver veículos</a></td>
                </tr>
                <tr>
                    <td>Total de reservas</td>
                    <td><?= intval($totalRes) ?></td>
                    <td><a href="<?= htmlspecialchars(function_exists('url') ? url('admin/reservas') : '/admin/reservas') ?>">Ver reservas</a></td>
                </tr>
                <tr>
                    <td>Reservas pendentes</td>
                    <td><?= intval($pendentes) ?></td>
                    <td><a href="<?= htmlspecialchars(function_exists('url') ? url('admin/reservas') : '/admin/reservas') ?>">Reservas pendentes</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
