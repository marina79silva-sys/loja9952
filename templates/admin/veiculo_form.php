<?php
$veiculo = $veiculo ?? [];
$marcas  = $marcas ?? [];
$erros   = $erros ?? [];
$titulo  = $titulo ?? 'Cadastro de veículo';
$editar  = !empty($veiculo['id']);
$actionUrl = $editar ? '/admin/veiculos/editar/' . (int)$veiculo['id'] : '/admin/veiculos/criar';

function old($key, $default = '') {
    return htmlspecialchars($_POST[$key] ?? $default);
}

function selectValue($veiculo, $key, $default = '') {
    if (!empty($veiculo[$key])) {
        return htmlspecialchars($veiculo[$key]);
    }
    return htmlspecialchars($_POST[$key] ?? $default);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?></title>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#eef2f8; color:#1f2937; }
        .container { width:min(1100px,calc(100% - 32px)); margin:24px auto; }
        .card { background:#fff; border-radius:14px; box-shadow:0 18px 40px rgba(15,23,42,.08); padding:28px; }
        h1 { margin-top:0; font-size:2rem; color:#111827; }
        .helper { margin:8px 0 24px; color:#4b5563; }
        .errors { margin:16px 0; padding:16px; background:#fee2e2; border:1px solid #fecaca; color:#991b1b; border-radius:8px; }
        .errors li { margin-bottom:8px; }
        form { display:grid; gap:18px; }
        .grid-2 { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:18px; }
        .field { display:flex; flex-direction:column; gap:8px; }
        label { font-weight:700; color:#111827; }
        input[type=text], input[type=number], input[type=file], select, textarea {
            width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#f8fafc; font-size:1rem; color:#111827;
        }
        textarea { min-height:150px; resize:vertical; }
        .file-note { font-size:.92rem; color:#6b7280; }
        .actions { display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-top:8px; }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:12px 18px; border:none; border-radius:10px; cursor:pointer; font-weight:700; text-decoration:none; color:#fff; }
        .btn-primary { background:#1D4ED8; }
        .btn-secondary { background:#6B7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1><?= $editar ? 'Editar veículo' : 'Adicionar veículo' ?></h1>
            <p class="helper">Preencha todos os campos abaixo. Use o botão de upload para associar uma imagem ao veículo.</p>

            <?php if (!empty($erros)): ?>
                <div class="errors">
                    <strong>Foram encontrados os seguintes erros:</strong>
                    <ul>
                        <?php foreach ($erros as $erro): ?>
                            <li><?= htmlspecialchars($erro) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars($actionUrl) ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(function_exists('csrf_token') ? csrf_token() : '') ?>">

                <div class="grid-2">
                    <div class="field">
                        <label for="marca_id">Marca</label>
                        <select id="marca_id" name="marca_id" required>
                            <option value="">Selecione uma marca</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?= (int)$marca['id'] ?>" <?= (selectValue($veiculo, 'marca_id') == $marca['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($marca['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="modelo">Modelo</label>
                        <input id="modelo" name="modelo" type="text" value="<?= selectValue($veiculo, 'modelo') ?>" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label for="ano">Ano</label>
                        <input id="ano" name="ano" type="number" min="1900" max="2099" step="1" value="<?= selectValue($veiculo, 'ano') ?>" required>
                    </div>
                    <div class="field">
                        <label for="quilometros">Quilómetros</label>
                        <input id="quilometros" name="quilometros" type="number" min="0" step="1" value="<?= selectValue($veiculo, 'quilometros', 0) ?>">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label for="combustivel">Combustível</label>
                        <select id="combustivel" name="combustivel" required>
                            <?php $combustiveis = ['Gasolina', 'Diesel', 'Elétrico', 'Híbrido', 'Outros']; ?>
                            <option value="">Selecione um tipo</option>
                            <?php foreach ($combustiveis as $combustivel): ?>
                                <option value="<?= htmlspecialchars($combustivel) ?>" <?= (selectValue($veiculo, 'combustivel') === $combustivel) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($combustivel) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="cilindrada">Cilindrada</label>
                        <input id="cilindrada" name="cilindrada" type="text" value="<?= selectValue($veiculo, 'cilindrada') ?>" placeholder="Ex: 1.6, 2.0">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label for="preco">Preço (€)</label>
                        <input id="preco" name="preco" type="text" value="<?= selectValue($veiculo, 'preco') ?>" required>
                    </div>
                    <div class="field">
                        <label for="imagem">Imagem</label>
                        <input id="imagem" name="imagem" type="file" accept="image/jpeg,image/png,image/webp">
                        <span class="file-note">Apenas JPG, PNG ou WEBP. Máx. 5MB.</span>
                    </div>
                </div>

                <div class="field">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao"><?= selectValue($veiculo, 'descricao') ?></textarea>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary"><?= $editar ? 'Atualizar veículo' : 'Criar veículo' ?></button>
                    <a href="<?= htmlspecialchars(function_exists('url') ? url('admin/veiculos') : '/admin/veiculos') ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
