<?php
$reservas = $reservas ?? [];
$titulo = $titulo ?? 'Reservas';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?></title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#eef2f8; color:#1f2937; }
        .container { width:min(1200px,calc(100% - 32px)); margin:24px auto; }
        .header { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px; }
        .header h1 { margin:0; font-size:1.9rem; }
        .card { background:#fff; border-radius:14px; box-shadow:0 18px 40px rgba(15,23,42,.08); padding:22px; }
        .table-responsive { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:860px; }
        th, td { padding:14px 16px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#1D4ED8; color:#fff; font-weight:700; }
        tr:nth-child(even) { background:#f8fbff; }
        .status { display:inline-flex; align-items:center; gap:8px; padding:7px 12px; border-radius:999px; font-size:.95rem; font-weight:700; }
        .status-pendente { background:#fef3c7; color:#92400e; }
        .status-confirmada { background:#dcfce7; color:#166534; }
        .status-cancelada { background:#fee2e2; color:#991b1b; }
        .select-status { width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#111827; }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:10px 14px; border:none; border-radius:10px; text-decoration:none; cursor:pointer; font-weight:700; }
        .btn-submit { background:#1D4ED8; color:#fff; }
        .btn-reset { background:#6B7280; color:#fff; }
        .meta { color:#475569; font-size:.95rem; }
        .empty { text-align:center; padding:24px 0; color:#475569; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gerir Reservas</h1>
            <p class="meta">Atualize o estado de cada reserva diretamente na tabela.</p>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Estado</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservas)): ?>
                            <tr>
                                <td class="empty" colspan="6">Nenhuma reserva encontrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><?= (int)$reserva['id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($reserva['cliente']) ?></strong><br>
                                        <span class="meta"><?= htmlspecialchars($reserva['email']) ?> · <?= htmlspecialchars($reserva['telefone']) ?></span>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($reserva['marca']) ?> <?= htmlspecialchars($reserva['modelo']) ?>
                                        <br><span class="meta">Ano <?= htmlspecialchars($reserva['ano']) ?> · €<?= number_format((float)$reserva['preco'], 2, ',', '.') ?></span>
                                    </td>
                                    <td>
                                        <?php $estado = $reserva['estado'] ?? 'pendente'; ?>
                                        <span class="status status-<?= htmlspecialchars($estado) ?>"><?= ucfirst(htmlspecialchars($estado)) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($reserva['criado_em'] ?? $reserva['data'] ?? 'now'))) ?></td>
                                    <td>
                                        <form method="POST" action="<?= htmlspecialchars(function_exists('url') ? url('admin/reservas/estado') : '/admin/reservas/estado') ?>">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(function_exists('csrf_token') ? csrf_token() : '') ?>">
                                            <input type="hidden" name="reserva_id" value="<?= (int)$reserva['id'] ?>">
                                            <select name="estado" class="select-status" aria-label="Alterar estado da reserva">
                                                <option value="pendente" <?= $estado === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                                <option value="confirmada" <?= $estado === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                                <option value="cancelada" <?= $estado === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                            </select>
                                            <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">
                                                <button type="submit" class="btn btn-submit">Salvar</button>
                                                <button type="reset" class="btn btn-reset">Repor</button>
                                            </div>
                                        </form>
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
