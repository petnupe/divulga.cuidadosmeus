<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-dark"><i class="fas fa-credit-card me-2"></i>Transações</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transações</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <form method="get" action="/admin/transacoes" class="d-flex align-items-center gap-2">
                <?php $current = $currentStatus ?? 'pending'; ?>
                <select name="status" class="form-select form-select-sm" style="width:auto">
                    <option value="pending" <?= $current === 'pending' ? 'selected' : '' ?>>Pendentes</option>
                    <option value="confirmado" <?= $current === 'confirmado' ? 'selected' : '' ?>>Confirmadas</option>
                    <option value="cancelado" <?= $current === 'cancelado' ? 'selected' : '' ?>>Canceladas</option>
                    <option value="todos" <?= $current === 'todos' ? 'selected' : '' ?>>Todas</option>
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">Filtrar</button>
            </form>
            <a href="/admin/logout" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
        </div>
    </div>
</div>

<?php if (isset($_GET['pix']) && $_GET['pix'] === 'ok'): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle me-1"></i> QR Code PIX gerado e atualizado na transação.</div>
<?php endif; ?>
<?php if (isset($_GET['pix_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-1"></i> Erro ao gerar QR Code PIX. <?= isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '' ?></div>
<?php endif; ?>
<?php if (isset($_GET['cancel']) && $_GET['cancel'] === 'ok'): ?>
    <div class="alert alert-warning"><i class="fas fa-ban me-1"></i> Cobrança cancelada com sucesso.</div>
<?php endif; ?>
<?php if (isset($_GET['cancel_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-1"></i> Erro ao cancelar a cobrança. <?= isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '' ?></div>
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
                        <th class="ps-4">ILPI</th>
                        <th>Plano</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Criada em</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($transacoes)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-2x mb-3"></i>
                            <p>Nenhuma transação para o filtro selecionado.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transacoes as $tx): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold"><?= htmlspecialchars($tx['ilpi_nome']) ?></div>
                            <small class="text-muted">ID #<?= $tx['ilpi_id'] ?></small>
                        </td>
                        <td><?= htmlspecialchars($tx['plano_nome'] ?? '—') ?></td>
                        <td class="fw-bold text-success">R$ <?= number_format($tx['valor'], 2, ',', '.') ?></td>
                        <td>
                            <?php 
                                $st = strtoupper($tx['status']);
                                $cls = 'bg-secondary';
                                if ($st === 'PENDING' || $st === 'PENDING_PAYMENT') $cls = 'bg-warning text-dark';
                                if ($st === 'PAYMENT_CONFIRMED' || $st === 'PAYMENT_RECEIVED') $cls = 'bg-success';
                                if ($st === 'CANCELLED') $cls = 'bg-secondary';
                            ?>
                            <span class="badge <?= $cls ?>"><?= htmlspecialchars($tx['status']) ?></span>
                        </td>
                        <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?></td>
                        <td class="text-end pe-4">
                            <?php if (!empty($tx['url_pagamento'])): ?>
                                <a href="<?= htmlspecialchars($tx['url_pagamento']) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fas fa-external-link-alt"></i> Invoice
                                </a>
                            <?php endif; ?>
                            <?php if (strtoupper($tx['status']) === 'PENDING' || strtoupper($tx['status']) === 'PENDING_PAYMENT'): ?>
                                <a href="/admin/transacoes/pix/<?= $tx['id'] ?>" class="btn btn-sm btn-primary-custom me-1">
                                    <i class="fas fa-qrcode"></i> PIX
                                </a>
                                <a href="/admin/transacoes/cancel/<?= $tx['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancelar esta cobrança no Asaas?');">
                                    <i class="fas fa-ban"></i> Cancelar
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!empty($_GET['pix']) && !empty($selectedTx) && !empty($selectedTx['pix_qr_base64'])): ?>
    <div class="card-footer">
        <div class="mb-2 small text-muted">
            <strong>Referente à cobrança:</strong>
            ILPI: <?= htmlspecialchars($selectedTx['ilpi_nome'] ?? ('#'.$selectedTx['ilpi_id'])) ?> • 
            Plano: <?= htmlspecialchars($selectedTx['plano_nome'] ?? '—') ?> • 
            Valor: R$ <?= number_format($selectedTx['valor'], 2, ',', '.') ?> • 
            Status: <?= htmlspecialchars($selectedTx['status']) ?> • 
            Transação #<?= (int)$selectedTx['id'] ?>
        </div>
        <div class="text-center">
            <img alt="QR Code PIX" src="data:image/png;base64,<?= htmlspecialchars($selectedTx['pix_qr_base64']) ?>" class="img-fluid rounded border p-2" />
        </div>
    </div>
    <?php endif; ?>
    <div class="card-footer text-muted small">
        Use as ações para auxiliar ILPIs com pagamento pendente: abrir invoice, gerar PIX e cancelar cobranças quando necessário.
    </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
