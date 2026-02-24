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
            <h4 class="alert-heading"><i class="fas fa-clock me-2"></i> Pagamento Pendente</h4>
            <p class="mb-2">Seu cadastro está pendente até a confirmação do pagamento do plano escolhido. Após a confirmação, o acesso completo será liberado automaticamente.</p>
            <div class="row g-3 align-items-center">
                <div class="col-md-4 text-center">
                    <?php if (!empty($pixQrBase64)): ?>
                        <img id="pixQrPendingImg" alt="QR Code PIX" src="data:image/png;base64,<?= htmlspecialchars($pixQrBase64) ?>" class="img-fluid rounded border p-2" />
                        <div class="small text-muted mt-2">Escaneie o QR Code para pagar via PIX</div>
                    <?php endif; ?>
                    <div id="pixPaidBannerDashboard" class="alert alert-success mt-2 py-2 px-3" style="display:none;">
                        <i class="fas fa-check-circle me-1"></i> Pagamento confirmado
                    </div>
                </div>
                <div class="col-md-8">
                    <?php if (!empty($pixPayload)): ?>
                        <label class="form-label">Payload PIX</label>
                        <div class="input-group" id="pixPayloadGroup">
                            <input type="text" class="form-control" id="pixPayloadInput" value="<?= htmlspecialchars($pixPayload) ?>" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('pixPayloadInput').value)">Copiar</button>
                        </div>
                        <small class="text-muted d-block mt-1">Copie e pague pelo seu app bancário.</small>
                    <?php endif; ?>
                    <?php if (empty($pixQrBase64)): ?>
                        <a id="btnGeneratePixDashboard" href="/ilpi/payment/pix" class="btn btn-primary-custom mt-3 py-2">
                            <i class="fas fa-qrcode me-1"></i> Gerar QR Code PIX
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($pendingPaymentUrl)): ?>
                        <a href="<?= htmlspecialchars($pendingPaymentUrl) ?>" class="btn btn-sm btn-warning mt-3">
                            <i class="fas fa-credit-card me-1"></i> Pagar por Boleto/Cartão
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <small class="text-muted d-block mt-2">Se você já pagou, aguarde alguns minutos para atualização.</small>
            <?php if (isset($_GET['pix']) && $_GET['pix'] === 'ok'): ?>
                <div class="mt-2 text-success small"><i class="fas fa-check-circle me-1"></i> QR Code PIX gerado com sucesso.</div>
            <?php endif; ?>
            <?php if (isset($_GET['pix_error'])): ?>
                <div class="mt-2 text-danger small"><i class="fas fa-exclamation-circle me-1"></i> Não foi possível gerar o QR Code. Tente novamente.</div>
                <?php if (isset($_GET['msg'])): ?>
                    <div class="text-muted small mt-1"><?= htmlspecialchars($_GET['msg']) ?></div>
                <?php endif; ?>
            <?php endif; ?>
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
                <div class="mt-3">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#renovarPlanoModal">
                        <i class="fas fa-sync-alt me-1"></i> Renovar / Alterar Plano
                    </button>
                </div>
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
                <div class="mt-3">
                    <ul class="list-unstyled small text-muted">
                        <li><i class="fab fa-whatsapp me-2 text-success"></i>Cliques WhatsApp: <?= (int)($ilpi['whatsapp_clicks'] ?? 0) ?></li>
                        <li><i class="fas fa-map-marked-alt me-2 text-secondary"></i>Cliques Mapa: <?= (int)($ilpi['map_clicks'] ?? 0) ?></li>
                        <li><i class="fab fa-facebook me-2 text-primary"></i>Cliques Facebook/Instagram: <?= (int)($ilpi['facebook_clicks'] ?? 0) ?></li>
                        <li><i class="fas fa-images me-2 text-primary-custom"></i>Visualizações de Fotos: <?= (int)($ilpi['photos_open'] ?? 0) ?></li>
                    </ul>
                </div>
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

<!-- Renovar Plano Modal -->
<div class="modal fade" id="renovarPlanoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title"><i class="fas fa-sync-alt me-2"></i>Escolher Plano para Renovar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <?php if (!empty($planos)): ?>
                <div class="row g-3">
                    <?php foreach ($planos as $plano): ?>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-white text-center py-3">
                                    <h5 class="card-title text-primary fw-bold mb-0"><?= htmlspecialchars($plano['nome']) ?></h5>
                                </div>
                                <div class="card-body">
                                    <h4 class="text-center text-success fw-bold mb-3">R$ <?= number_format($plano['valor'], 2, ',', '.') ?> <small class="text-muted fs-6">/mês</small></h4>
                                    <ul class="list-unstyled mb-3 small">
                                        <li class="mb-2"><i class="fas fa-bed text-secondary me-2"></i> Até <strong><?= $plano['limite_leitos'] ?></strong> leitos</li>
                                        <li class="mb-2"><i class="fas fa-camera text-secondary me-2"></i> <strong><?= $plano['limite_fotos'] ?></strong> fotos por leito</li>
                                        <li class="mb-2">
                                            <?php if ($plano['exibir_redes_sociais']): ?>
                                                <i class="fas fa-check text-success me-2"></i> Redes Sociais no perfil
                                            <?php else: ?>
                                                <i class="fas fa-times text-muted me-2"></i> Sem Redes Sociais
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                    <div class="text-center">
                                        <a href="#" class="btn btn-dark btn-sm js-generate-pix-btn" data-plano-id="<?= $plano['id'] ?>">
                                            <i class="fas fa-qrcode me-1"></i> Gerar PIX e Renovar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class="alert alert-warning">Nenhum plano disponível.</div>
                <?php endif; ?>
                <div id="pixAjaxResult" class="mt-4" style="display:none;">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1"><i class="fas fa-qrcode me-2"></i>PIX Gerado</h6>
                            <div class="small text-muted mb-3" id="pixAjaxContext"></div>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4 text-center">
                                    <img id="pixAjaxQrImg" alt="QR Code PIX" class="img-fluid rounded border p-2" />
                                    <div class="small text-muted mt-2">Escaneie para pagar via PIX</div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Payload PIX</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="pixAjaxPayload" readonly>
                                        <button class="btn btn-outline-secondary" type="button" id="pixAjaxCopyBtn">Copiar</button>
                                    </div>
                                    <a id="pixAjaxInvoiceUrl" href="#" class="btn btn-outline-warning mt-3" style="display:none;">
                                        <i class="fas fa-credit-card me-1"></i> Pagar por Boleto/Cartão
                                    </a>
                                    <div id="pixAjaxError" class="text-danger small mt-2" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="pixAjaxSuccess" class="alert alert-success mt-3" style="display:none;">
                    <i class="fas fa-check-circle me-2"></i> Pagamento confirmado. Seu acesso completo será liberado em instantes.
                    Você pode fechar esta janela quando quiser.
                </div>
                <div class="alert alert-info mt-4 mb-0 border-start border-5 border-info">
                    <i class="fas fa-info-circle me-2"></i>
                    A renovação será aplicada de vencimento a vencimento (não reinicia a contagem antes).
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Controle de fechamento da modal: só permite fechamento manual ou após confirmação de pagamento
(function(){
    var modalEl = document.getElementById('renovarPlanoModal');
    if (modalEl) {
        var allowClose = false;
        window.__pixPaidConfirmed = false;
        var closeBtn = modalEl.querySelector('.btn-close');
        var footerCloseBtn = modalEl.querySelector('[data-bs-dismiss="modal"]');
        if (closeBtn) closeBtn.addEventListener('click', function(){ allowClose = true; });
        if (footerCloseBtn) footerCloseBtn.addEventListener('click', function(){ allowClose = true; });
        modalEl.addEventListener('hide.bs.modal', function(e){
            if (!allowClose && !window.__pixPaidConfirmed) {
                e.preventDefault();
            }
        });
        modalEl.addEventListener('hidden.bs.modal', function(){
            if (window.__pixPaidConfirmed) {
                window.location.reload();
            }
        });
    }
})();

document.querySelectorAll('.js-generate-pix-btn').forEach(function(btn){
    btn.addEventListener('click', function(e){
        e.preventDefault();
        var planoId = btn.getAttribute('data-plano-id');
        var originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Gerando...';
        fetch('/ilpi/payment/pix?ajax=1&plano_id=' + encodeURIComponent(planoId))
            .then(function(res){ return res.json(); })
            .then(function(data){
                var result = document.getElementById('pixAjaxResult');
                var qrImg = document.getElementById('pixAjaxQrImg');
                var payloadInput = document.getElementById('pixAjaxPayload');
                var copyBtn = document.getElementById('pixAjaxCopyBtn');
                var invoiceBtn = document.getElementById('pixAjaxInvoiceUrl');
                var errorDiv = document.getElementById('pixAjaxError');
                var contextDiv = document.getElementById('pixAjaxContext');
                errorDiv.style.display = 'none';
                if (data.ok) {
                    result.style.display = 'block';
                    if (data.qr) {
                        qrImg.src = 'data:image/png;base64,' + data.qr;
                        qrImg.style.display = 'block';
                    } else {
                        qrImg.style.display = 'none';
                    }
                    if (data.payload) {
                        payloadInput.value = data.payload;
                        copyBtn.onclick = function(){ navigator.clipboard.writeText(payloadInput.value); };
                    } else {
                        payloadInput.value = '';
                    }
                    if (data.invoiceUrl) {
                        invoiceBtn.href = data.invoiceUrl;
                        invoiceBtn.style.display = 'inline-block';
                    } else {
                        invoiceBtn.style.display = 'none';
                    }
                    var valorStr = (data.valor != null) ? ('R$ ' + Number(data.valor).toFixed(2).replace('.', ',')) : '';
                    var dueStr = (data.dueDate) ? ('Vence em ' + (new Date(data.dueDate + 'T00:00:00')).toLocaleDateString('pt-BR')) : '';
                    var parts = [];
                    if (data.planoNome) parts.push('Plano: ' + data.planoNome);
                    if (valorStr) parts.push('Valor: ' + valorStr);
                    if (dueStr) parts.push(dueStr);
                    if (data.transacaoId) parts.push('Transação #' + data.transacaoId);
                    contextDiv.textContent = parts.join(' • ');
                    if (data.transacaoId) {
                        var modalEl = document.getElementById('renovarPlanoModal');
                        var poll = setInterval(function(){
                            fetch('/ilpi/payment/status?transacao_id=' + encodeURIComponent(data.transacaoId))
                                .then(function(r){ return r.json(); })
                                .then(function(s){
                                    if (s.ok && (s.status === 'PAYMENT_CONFIRMED' || s.status === 'PAYMENT_RECEIVED')) {
                                        document.getElementById('pixAjaxResult').style.display = 'none';
                                        document.getElementById('pixAjaxSuccess').style.display = 'block';
                                        window.__pixPaidConfirmed = true;
                                        clearInterval(poll);
                                    }
                                })
                                .catch(function(){});
                        }, 5000);
                    }
                } else {
                    result.style.display = 'block';
                    qrImg.style.display = 'none';
                    payloadInput.value = '';
                    invoiceBtn.style.display = 'none';
                    errorDiv.textContent = data.error || 'Não foi possível gerar o PIX.';
                    errorDiv.style.display = 'block';
                    contextDiv.textContent = '';
                }
            })
            .catch(function(){
                var result = document.getElementById('pixAjaxResult');
                var errorDiv = document.getElementById('pixAjaxError');
                result.style.display = 'block';
                errorDiv.textContent = 'Falha na requisição. Tente novamente.';
                errorDiv.style.display = 'block';
            })
            .finally(function(){
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
    });
});
</script>
<script>
// Atualiza a área de cobrança no dashboard após confirmação via webhook
(function(){
    var img = document.getElementById('pixQrPendingImg');
    var payloadGroup = document.getElementById('pixPayloadGroup');
    var banner = document.getElementById('pixPaidBannerDashboard');
    var btnGen = document.getElementById('btnGeneratePixDashboard');
    if (img || payloadGroup || btnGen) {
        var poll2 = setInterval(function(){
            fetch('/ilpi/payment/status')
                .then(function(r){ return r.json(); })
                .then(function(s){
                    if (s.ok && (s.status === 'PAYMENT_CONFIRMED' || s.status === 'PAYMENT_RECEIVED')) {
                        if (img) img.style.display = 'none';
                        if (payloadGroup) payloadGroup.style.display = 'none';
                        if (btnGen) btnGen.style.display = 'none';
                        if (banner) banner.style.display = 'block';
                        clearInterval(poll2);
                    }
                })
                .catch(function(){});
        }, 5000);
    }
})();
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
