<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-dark"><i class="fas fa-sync-alt me-2"></i>Gestão de Renovações</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Renovações</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/admin/logout" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
        </div>
    </div>
</div>

<!-- Form de Nova Renovação -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 text-primary"><i class="fas fa-plus-circle me-2"></i>Registrar Nova Renovação</h5>
            </div>
            <div class="card-body">
                <form action="/admin/renovacoes/store" method="POST">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="ilpi_id" class="form-label">ILPI</label>
                            <select class="form-select" id="ilpi_id" name="ilpi_id" required onchange="updateRenovacaoDates()">
                                <option value="">Selecione a ILPI...</option>
                                <?php foreach ($ilpis as $ilpi): ?>
                                    <option value="<?= $ilpi['id'] ?>" data-last-expiration="<?= $ilpi['last_expiration'] ?? '' ?>">
                                        <?= htmlspecialchars($ilpi['nome']) ?> (<?= htmlspecialchars($ilpi['cnpj']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="plano_id" class="form-label">Plano</label>
                            <select class="form-select" id="plano_id" name="plano_id" required>
                                <option value="">Selecione o Plano...</option>
                                <?php foreach ($planos as $plano): ?>
                                    <option value="<?= $plano['id'] ?>"><?= htmlspecialchars($plano['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="periodo_meses" class="form-label">Período (Meses)</label>
                            <input type="number" class="form-control" id="periodo_meses" name="periodo_meses" value="1" min="1" required onchange="calculateVencimento()">
                        </div>
                        <div class="col-md-2">
                            <label for="data_renovacao" class="form-label">Data Renovação</label>
                            <input type="date" class="form-control" id="data_renovacao" name="data_renovacao" value="<?= date('Y-m-d') ?>" required onchange="calculateVencimento()">
                        </div>
                        <div class="col-md-2">
                            <label for="data_vencimento" class="form-label">Data Vencimento</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="valor" class="form-label">Valor (R$)</label>
                            <input type="text" class="form-control money-mask" id="valor" name="valor" placeholder="0,00" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Registrar Renovação</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Histórico -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Histórico de Renovações</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Data Renovação</th>
                                <th>ILPI</th>
                                <th>Plano</th>
                                <th>Vencimento</th>
                                <th>Valor</th>
                                <th class="text-end pe-4">Registrado em</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($renovacoes)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-file-invoice-dollar fa-2x mb-3"></i>
                                        <p>Nenhuma renovação registrada até o momento.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($renovacoes as $renovacao): ?>
                                    <tr>
                                        <td class="ps-4"><?= date('d/m/Y', strtotime($renovacao['data_renovacao'])) ?></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($renovacao['ilpi_nome']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($renovacao['cnpj']) ?></small>
                                        </td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($renovacao['plano_nome']) ?></span></td>
                                        <td class="<?= strtotime($renovacao['data_vencimento']) < time() ? 'text-danger fw-bold' : '' ?>">
                                            <?= date('d/m/Y', strtotime($renovacao['data_vencimento'])) ?>
                                        </td>
                                        <td class="fw-bold text-success">R$ <?= number_format($renovacao['valor'], 2, ',', '.') ?></td>
                                        <td class="text-end pe-4 text-muted small">
                                            <?= date('d/m/Y H:i', strtotime($renovacao['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateRenovacaoDates() {
        const ilpiSelect = document.getElementById('ilpi_id');
        const selectedOption = ilpiSelect.options[ilpiSelect.selectedIndex];
        const lastExpiration = selectedOption.getAttribute('data-last-expiration');
        const dataRenovacaoInput = document.getElementById('data_renovacao');
        
        if (lastExpiration) {
            // If there is a last expiration date, set renovation date to it (or next day?)
            // Usually renewal starts when previous ends. Let's use the expiration date as start.
            // Or if expiration was yesterday, today is fine. 
            // The prompt says "default (data da ultima + 30 dias)". 
            // It implies "renew FROM last date".
            dataRenovacaoInput.value = lastExpiration;
        } else {
            // Default to today if no previous
            dataRenovacaoInput.value = new Date().toISOString().split('T')[0];
        }
        
        calculateVencimento();
    }

    function calculateVencimento() {
        const dataRenovacaoStr = document.getElementById('data_renovacao').value;
        const meses = parseInt(document.getElementById('periodo_meses').value) || 1;
        const dataVencimentoInput = document.getElementById('data_vencimento');
        
        if (dataRenovacaoStr) {
            const dataRenovacao = new Date(dataRenovacaoStr);
            // Add months * 30 days
            const daysToAdd = meses * 30;
            dataRenovacao.setDate(dataRenovacao.getDate() + daysToAdd);
            
            dataVencimentoInput.value = dataRenovacao.toISOString().split('T')[0];
        }
    }
    
    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        calculateVencimento();
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
