<?php
$veiculos = $veiculos ?? [];
$titulo = $titulo ?? 'Veículos';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?></title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f2f4f8; color:#222; }
        .container { width:min(1200px,calc(100% - 32px)); margin:24px auto; }
        .topbar { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px; }
        .topbar h1 { margin:0; font-size:1.85rem; }
        .btn { display:inline-block; padding:10px 16px; border:none; border-radius:6px; text-decoration:none; color:#fff; font-weight:700; cursor:pointer; }
        .btn-primary { background:#1A237E; }
        .btn-edit { background:#0b79d0; }
        .btn-delete { background:#d32f2f; }
        .card { background:#fff; border-radius:12px; box-shadow:0 18px 32px rgba(0,0,0,.06); padding:24px; }
        .table-wrapper { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:760px; }
        th, td { padding:14px 16px; text-align:left; border-bottom:1px solid #e4e7ec; }
        th { background:#1A237E; color:#fff; font-weight:700; }
        tr:nth-child(even) { background:#f8fbff; }
        .status { display:inline-flex; align-items:center; padding:4px 10px; border-radius:999px; font-size:.95rem; font-weight:700; }
        .status-available { background:#e8f3ff; color:#0b5fb2; }
        .status-unavailable { background:#fdecea; color:#b72b2b; }
        .actions { display:flex; gap:8px; flex-wrap:wrap; }
        .empty-cell { text-align:center; color:#555; padding:28px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <h1>Gestão de veículos</h1>
            <a href="<?= htmlspecialchars(function_exists('url') ? url('admin/veiculos/criar') : '/admin/veiculos/criar') ?>" class="btn btn-primary">Adicionar novo veículo</a>
        </div>
        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Ano</th>
                            <th>Preço</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($veiculos)): ?>
                            <tr>
                                <td class="empty-cell" colspan="7">Nenhum veículo disponível.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($veiculos as $veiculo): ?>
                                <tr>
                                    <td><?= (int)$veiculo['id'] ?></td>
                                    <td><?= htmlspecialchars($veiculo['marca']) ?></td>
                                    <td><?= htmlspecialchars($veiculo['modelo']) ?></td>
                                    <td><?= htmlspecialchars($veiculo['ano']) ?></td>
                                    <td>€ <?= number_format((float)$veiculo['preco'], 2, ',', '.') ?></td>
                                    <td>
                                        <?php if (!empty($veiculo['disponivel']) && $veiculo['disponivel'] == 1): ?>
                                            <span class="status status-available">Disponível</span>
                                        <?php else: ?>
                                            <span class="status status-unavailable">Indisponível</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a href="<?= htmlspecialchars(function_exists('url') ? url('admin/veiculos/editar/'.((int)$veiculo['id'])) : '/admin/veiculos/editar/'.((int)$veiculo['id'])) ?>" class="btn btn-edit">Editar</a>
                                        <a href="<?= htmlspecialchars(function_exists('url') ? url('admin/veiculos/apagar/'.((int)$veiculo['id'])) : '/admin/veiculos/apagar/'.((int)$veiculo['id'])) ?>" class="btn btn-delete" onclick="return confirm('Tem a certeza que deseja apagar este veículo?');">Apagar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
