<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-primary-custom">Painel da ILPI</h2>
                <p class="text-muted mb-0">Bem-vindo, <?= htmlspecialchars($ilpi['nome']) ?></p>
            </div>
            <a href="/ilpi/profile" class="btn btn-outline-primary">
                <i class="fas fa-user-edit me-2"></i>Editar Perfil
            </a>
        </div>
    </div>
</div>

<?php if (isset($isExpired) && $isExpired): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger border-start border-5 border-danger shadow-sm" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i> Plano Vencido</h4>
            <p class="mb-0">
                Seu plano expirou em <strong><?= $dataVencimento ? date('d/m/Y', strtotime($dataVencimento)) : 'Data desconhecida' ?></strong>. 
                Seu cadastro foi desativado e não aparece mais nas buscas públicas. 
                Por favor, entre em contato para renovar sua assinatura.
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($ilpi['status']) && $ilpi['status'] === 'pendente'): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-warning border-start border-5 border-warning shadow-sm" role="alert">
            <h4 class="alert-heading"><i class="fas fa-clock me-2"></i> Cadastro em Análise</h4>
            <p class="mb-0">Seu cadastro está sendo analisado pela nossa equipe. Durante este período, você pode gerenciar seus dados e leitos, mas seu perfil ainda não aparecerá nas buscas públicas.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row mb-4">
    <!-- Info Card -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-start border-5 border-primary">
            <div class="card-body">
                <h5 class="card-title text-muted">Seu Plano</h5>
                <h3 class="fw-bold text-primary-custom"><?= htmlspecialchars($ilpi['plano_nome']) ?></h3>
                
                <?php if ($dataVencimento): ?>
                    <?php 
                        $hoje = new DateTime();
                        $vencimento = new DateTime($dataVencimento);
                        $diasRestantes = $hoje->diff($vencimento)->days;
                        $isVencido = $hoje > $vencimento;
                        $corTexto = $isVencido ? 'text-danger' : ($diasRestantes < 7 ? 'text-warning' : 'text-success');
                    ?>
                    <p class="mt-2 mb-0 fw-bold <?= $corTexto ?>">
                        <i class="fas fa-calendar-alt me-1"></i> 
                        Vence em: <?= date('d/m/Y', strtotime($dataVencimento)) ?>
                    </p>
                <?php else: ?>
                    <p class="mt-2 mb-0 text-muted small"><i class="fas fa-calendar-alt me-1"></i> Data de vencimento não disponível</p>
                <?php endif; ?>

                <ul class="list-unstyled mt-3">
                    <li><i class="fas fa-bed me-2 text-secondary"></i> Limite de Leitos: <?= $ilpi['limite_leitos'] ?></li>
                    <li><i class="fas fa-camera me-2 text-secondary"></i> Fotos por Leito: <?= $ilpi['limite_fotos'] ?></li>
                    <li>
                        <i class="fas fa-share-alt me-2 text-secondary"></i> Redes Sociais: 
                        <?= $ilpi['exibir_redes_sociais'] ? '<span class="text-success">Ativo</span>' : '<span class="text-muted">Inativo</span>' ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-start border-5 border-success">
            <div class="card-body">
                <h5 class="card-title text-muted">Leitos Ativos</h5>
                <h3 class="fw-bold text-success"><?= $activeBeds ?> / <?= $ilpi['limite_leitos'] ?></h3>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= ($activeBeds / $ilpi['limite_leitos']) * 100 ?>%"></div>
                </div>
                <small class="text-muted mt-2 d-block">Leitos divulgados como disponíveis</small>
            </div>
        </div>
    </div>

    <!-- Actions Card -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 bg-light">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <h5 class="mb-3">Gerenciar Vagas</h5>
                <?php if ($activeBeds >= $ilpi['limite_leitos']): ?>
                    <div class="alert alert-warning py-2 small w-100">Limite de leitos ativos atingido!</div>
                <?php endif; ?>
                <a href="/ilpi/leitos/create" class="btn btn-primary-custom w-100 mb-2">
                    <i class="fas fa-plus me-2"></i> Cadastrar Novo Leito
                </a>
                <small class="text-muted">Mantenha suas vagas atualizadas</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary-custom"><i class="fas fa-list me-2"></i> Seus Leitos Cadastrados</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Tipo</th>
                                <th>Grau de Dependência</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Atualizado em</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($leitos)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-bed fa-2x mb-3"></i>
                                        <p>Nenhum leito cadastrado.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($leitos as $leito): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?= htmlspecialchars($leito['tipo']) ?></td>
                                        <td><?= htmlspecialchars($leito['grau_dependencia_nome']) ?></td>
                                        <td>R$ <?= number_format($leito['valor'], 2, ',', '.') ?></td>
                                        <td>
                                            <?php if ($leito['status'] == 'disponivel'): ?>
                                                <span class="badge bg-success rounded-pill">Disponível</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill">Ocupado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small text-muted">
                                            <?= date('d/m/Y H:i', strtotime($leito['updated_at'])) ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="/ilpi/leitos/edit/<?= $leito['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/ilpi/leitos/delete/<?= $leito['id'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este leito?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
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

<div class="row mt-5 mb-3">
    <div class="col-12 text-end">
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            <i class="fas fa-trash-alt me-2"></i> Excluir Minha Conta
        </button>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">Excluir Conta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold">Tem certeza que deseja excluir sua conta permanentemente?</p>
                <p class="text-danger small">Esta ação não pode ser desfeita. Todos os seus dados, incluindo leitos e fotos, serão apagados do sistema.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="/ilpi/delete-account" method="POST">
                    <button type="submit" class="btn btn-danger">Sim, Excluir Tudo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
