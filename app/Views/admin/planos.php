<?php require_once __DIR__ . '/../header.php'; ?>
<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-dark"><i class="fas fa-cubes me-2"></i>Planos</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Planos</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/admin/logout" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
        </div>
    </div>
</div>
<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle me-1"></i>Plano atualizado.</div>
<?php endif; ?>
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 text-primary"><i class="fas fa-list me-2"></i>Lista</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Limite Leitos</th>
                        <th>Limite Fotos</th>
                        <th>Redes Sociais</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($planos)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                <p>Nenhum plano cadastrado.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($planos as $plano): ?>
                            <tr>
                                <td class="ps-4">#<?= (int)$plano['id'] ?></td>
                                <td>
                                    <input form="f<?= (int)$plano['id'] ?>" name="nome" type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($plano['nome'] ?? '') ?>">
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">R$</span>
                                        <input form="f<?= (int)$plano['id'] ?>" name="valor" type="text" class="form-control" value="<?= number_format((float)($plano['valor'] ?? 0), 2, ',', '.') ?>">
                                    </div>
                                </td>
                                <td>
                                    <input form="f<?= (int)$plano['id'] ?>" name="limite_leitos" type="number" min="0" class="form-control form-control-sm" value="<?= (int)($plano['limite_leitos'] ?? 0) ?>">
                                </td>
                                <td>
                                    <input form="f<?= (int)$plano['id'] ?>" name="limite_fotos" type="number" min="0" class="form-control form-control-sm" value="<?= (int)($plano['limite_fotos'] ?? 0) ?>">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input form="f<?= (int)$plano['id'] ?>" name="exibir_redes_sociais" class="form-check-input" type="checkbox" <?= !empty($plano['exibir_redes_sociais']) ? 'checked' : '' ?>>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <form id="f<?= (int)$plano['id'] ?>" action="/admin/planos/update/<?= (int)$plano['id'] ?>" method="POST" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-primary-custom"><i class="fas fa-save me-1"></i>Salvar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted small">
        Atualize os parâmetros dos planos conforme necessário.
    </div>
    </div>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
