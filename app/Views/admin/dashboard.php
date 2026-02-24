<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-dark"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Administrativo</h2>
            <p class="text-muted">Bem-vindo, <?= htmlspecialchars($_SESSION['admin_name']) ?></p>
        </div>
        <div>
            <a href="/admin/logout" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-start border-5 border-primary h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">Total de ILPIs</h5>
                <h2 class="fw-bold text-primary"><?= $stats['total'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-start border-5 border-warning h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">Pendentes</h5>
                <h2 class="fw-bold text-warning"><?= $stats['pendente'] ?></h2>
                <a href="/admin/ilpis?status=pendente" class="btn btn-sm btn-outline-warning mt-2">Ver Pendentes</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-start border-5 border-success h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">Ativas</h5>
                <h2 class="fw-bold text-success"><?= $stats['ativo'] ?></h2>
                <a href="/admin/ilpis?status=ativo" class="btn btn-sm btn-outline-success mt-2">Ver Ativas</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-start border-5 border-danger h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">Rejeitadas</h5>
                <h2 class="fw-bold text-danger"><?= $stats['rejeitado'] ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Gestão de ILPIs</h5>
            </div>
            <div class="card-body">
                <p>Gerencie os cadastros, aprove novos registros e visualize detalhes das instituições.</p>
                <a href="/admin/ilpis" class="btn btn-dark w-100">Acessar Lista de ILPIs</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Renovações e Planos</h5>
            </div>
            <div class="card-body">
                <p>Acompanhe renovações e edite os parâmetros dos planos disponíveis.</p>
                <div class="d-grid gap-2">
                    <a href="/admin/renovacoes" class="btn btn-outline-dark">Ver Histórico de Renovações</a>
                    <a href="/admin/planos" class="btn btn-dark">Editar Planos</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Cobranças Pendentes</h5>
            </div>
            <div class="card-body">
                <p>Gerencie cobranças em aberto: gerar QR Code PIX, abrir invoice e cancelar quando necessário.</p>
                <a href="/admin/transacoes" class="btn btn-dark w-100">Acessar Transações</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
