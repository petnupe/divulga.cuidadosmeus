<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-dark"><i class="fas fa-building me-2"></i>Gestão de ILPIs</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ILPIs</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/admin/logout" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentStatus === null ? 'active bg-dark' : 'text-dark' ?>" href="/admin/ilpis">Todas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentStatus === 'pendente' ? 'active bg-warning text-dark' : 'text-dark' ?>" href="/admin/ilpis?status=pendente">Pendentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentStatus === 'ativo' ? 'active bg-success' : 'text-dark' ?>" href="/admin/ilpis?status=ativo">Ativas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentStatus === 'rejeitado' ? 'active bg-danger' : 'text-dark' ?>" href="/admin/ilpis?status=rejeitado">Rejeitadas</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">ILPI</th>
                                <th>CNPJ</th>
                                <th>Contato</th>
                                <th>Localização</th>
                                <th>Plano</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ilpis)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-search fa-2x mb-3"></i>
                                        <p>Nenhuma ILPI encontrada com os filtros atuais.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ilpis as $ilpi): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= htmlspecialchars($ilpi['nome']) ?></div>
                                            <small class="text-muted">Cadastrado em <?= date('d/m/Y', strtotime($ilpi['created_at'])) ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($ilpi['cnpj']) ?></td>
                                        <td>
                                            <div class="small">
                                                <i class="fas fa-user me-1"></i> <?= htmlspecialchars($ilpi['responsavel']) ?><br>
                                                <i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($ilpi['email']) ?><br>
                                                <i class="fas fa-phone me-1"></i> <?= htmlspecialchars($ilpi['telefone']) ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($ilpi['cidade_nome']) ?> / <?= htmlspecialchars($ilpi['estado_uf']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($ilpi['plano_nome']) ?></span></td>
                                        <td>
                                            <?php if ($ilpi['status'] == 'pendente'): ?>
                                                <span class="badge bg-warning text-dark">Pendente</span>
                                            <?php elseif ($ilpi['status'] == 'ativo'): ?>
                                                <span class="badge bg-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejeitado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <?php if ($ilpi['status'] == 'pendente'): ?>
                                                <a href="/admin/approve/<?= $ilpi['id'] ?>" class="btn btn-sm btn-success me-1" title="Aprovar" onclick="return confirm('Deseja aprovar o cadastro desta ILPI?');">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="/admin/reject/<?= $ilpi['id'] ?>" class="btn btn-sm btn-danger" title="Rejeitar" onclick="return confirm('Deseja rejeitar o cadastro desta ILPI?');">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php elseif ($ilpi['status'] == 'ativo'): ?>
                                                <a href="/admin/reject/<?= $ilpi['id'] ?>" class="btn btn-sm btn-outline-danger" title="Bloquear/Rejeitar" onclick="return confirm('Deseja bloquear o acesso desta ILPI?');">
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="/admin/approve/<?= $ilpi['id'] ?>" class="btn btn-sm btn-outline-success" title="Reativar" onclick="return confirm('Deseja reativar o cadastro desta ILPI?');">
                                                    <i class="fas fa-redo"></i>
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
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
