<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-center text-primary-custom mb-4">Cadastro de ILPI</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="/ilpi/store" method="POST">
                    <h5 class="mb-3 border-bottom pb-2">Dados da Instituição</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome da ILPI *</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($old['nome'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cnpj" class="form-label">CNPJ *</label>
                            <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?= htmlspecialchars($old['cnpj'] ?? '') ?>" required placeholder="00.000.000/0000-00">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone / WhatsApp *</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($old['telefone'] ?? '') ?>" required placeholder="(00) 00000-0000">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="responsavel" class="form-label">Nome do Responsável *</label>
                            <input type="text" class="form-control" id="responsavel" name="responsavel" value="<?= htmlspecialchars($old['responsavel'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="plano_id" class="form-label">Plano *</label>
                            <div class="input-group">
                                <select name="plano_id" id="plano_id" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($planos as $plano): ?>
                                        <option value="<?= $plano['id'] ?>" <?= (isset($old['plano_id']) && $old['plano_id'] == $plano['id']) ? 'selected' : ((isset($_GET['plano']) && $_GET['plano'] == $plano['id']) ? 'selected' : '') ?>>
                                            <?= htmlspecialchars($plano['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#planosModal">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Endereço</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="cep" class="form-label">CEP *</label>
                            <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($old['cep'] ?? '') ?>" required onblur="buscarCep(this.value)">
                            <div id="cepLoading" class="form-text text-muted d-none"><span class="spinner-border spinner-border-sm"></span> Carregando endereço...</div>
                        </div>
                        <div class="col-md-4">
                            <label for="estado_id" class="form-label">Estado *</label>
                            <select name="estado_id" id="estado_id" class="form-select" required onchange="loadCidades(this.value)">
                                <option value="">Selecione...</option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= $estado['id'] ?>" <?= (isset($old['estado_id']) && $old['estado_id'] == $estado['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($estado['nome']) ?> (<?= htmlspecialchars($estado['uf']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cidade_id" class="form-label">Cidade *</label>
                            <select name="cidade_id" id="cidade_id" class="form-select" required>
                                <option value="">Selecione um estado primeiro</option>
                            </select>
                            <div id="cidadeLoading" class="form-text text-muted d-none"><span class="spinner-border spinner-border-sm"></span> Carregando cidades...</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="endereco" class="form-label">Logradouro *</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($old['endereco'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label for="numero" class="form-label">Número *</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($old['numero'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro *</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($old['bairro'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="complemento" name="complemento" value="<?= htmlspecialchars($old['complemento'] ?? '') ?>">
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Acesso</h5>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="email" class="form-label">E-mail (Login) *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="senha" class="form-label">Senha *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="senha" name="senha" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleVisibility('senha', this)" aria-label="Mostrar/ocultar senha"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_senha" class="form-label">Confirmar Senha *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_senha" name="confirm_senha" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleVisibility('confirm_senha', this)" aria-label="Mostrar/ocultar senha"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Descrição da ILPI</h5>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Breve descrição (máx. 300 caracteres)</label>
                        <textarea class="form-control" id="descricao" name="descricao" maxlength="300" rows="3" placeholder="Informe seus serviços, estrutura e diferenciais."><?= htmlspecialchars($old['descricao'] ?? '') ?></textarea>
                        <small class="text-muted">Use até 300 caracteres.</small>
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Redes Sociais (Opcional)</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="facebook" class="form-label"><i class="fab fa-facebook text-primary me-1"></i> Facebook</label>
                            <div class="input-group">
                                <span class="input-group-text">facebook.com/</span>
                                <input type="text" class="form-control" id="facebook" name="facebook" value="<?= htmlspecialchars($old['facebook'] ?? '') ?>" placeholder="seu-perfil" pattern="^[A-Za-z0-9._-]+$">
                            </div>
                            <small class="text-muted">Informe apenas o que vem após facebook.com/</small>
                        </div>
                        <div class="col-md-6">
                            <label for="instagram" class="form-label"><i class="fab fa-instagram text-danger me-1"></i> Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text">instagram.com/</span>
                                <input type="text" class="form-control" id="instagram" name="instagram" value="<?= htmlspecialchars($old['instagram'] ?? '') ?>" placeholder="seu-usuario" pattern="^[A-Za-z0-9._-]+$">
                            </div>
                            <small class="text-muted">Informe apenas o que vem após instagram.com/</small>
                        </div>
                        <div class="col-12 mt-2">
                            <small class="text-muted">* A exibição das redes sociais na página pública depende do plano contratado (Sênior ou Master).</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2">Cadastrar</button>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="1" id="termos" name="termos" required>
                        <label class="form-check-label" for="termos">
                            Li e aceito os <a href="#" data-bs-toggle="modal" data-bs-target="#termosModal">Termos de Uso</a>.
                        </label>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="/ilpi/login" class="text-decoration-none">Já possui conta? Faça login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Planos Modal -->
<div class="modal fade" id="planosModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title"><i class="fas fa-list-alt me-2"></i>Conheça Nossos Planos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="row g-3">
                    <?php foreach ($planos as $plano): ?>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-white text-center py-3">
                                    <h4 class="card-title text-primary fw-bold mb-0"><?= htmlspecialchars($plano['nome']) ?></h4>
                                </div>
                                <div class="card-body">
                                    <h3 class="text-center text-success fw-bold mb-3">R$ <?= number_format($plano['valor'], 2, ',', '.') ?> <small class="text-muted fs-6">/mês</small></h3>
                                    <ul class="list-unstyled mb-0 small">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Até <strong><?= $plano['limite_leitos'] ?></strong> leitos ativos</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> <strong><?= $plano['limite_fotos'] ?></strong> fotos por leito</li>
                                        <li class="mb-2">
                                            <?php if ($plano['exibir_redes_sociais']): ?>
                                                <i class="fas fa-check text-success me-2"></i> Redes Sociais no perfil
                                            <?php else: ?>
                                                <i class="fas fa-times text-muted me-2"></i> Sem Redes Sociais
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="alert alert-warning mt-4 mb-0 border-start border-5 border-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Atenção:</strong> Seu cadastro será liberado para acesso completo somente após a confirmação do pagamento do plano escolhido.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Termos Modal -->
<div class="modal fade" id="termosModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-file-contract me-2"></i>Termos de Uso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ol class="small">
                    <li>
                        <strong>Veracidade e Atualização das Vagas</strong><br>
                        A instituição compromete-se a atualizar a disponibilidade de leitos em tempo real, isentando a plataforma de qualquer responsabilidade por informações desatualizadas ou divergências entre o estoque virtual e o físico.
                    </li>
                    <li class="mt-2">
                        <strong>Natureza Meramente Informativa</strong><br>
                        A visualização da vaga no portal não garante a admissão automática do idoso, que permanece condicionada à avaliação multiprofissional e à assinatura de contrato específico entre a família e a ILPI.
                    </li>
                    <li class="mt-2">
                        <strong>Conformidade com a LGPD</strong><br>
                        A ILPI declara possuir autorização expressa para o compartilhamento de quaisquer dados ou imagens de suas dependências e serviços, respondendo isoladamente por qualquer violação à Lei Geral de Proteção de Dados (LGPD).
                    </li>
                    <li class="mt-2">
                        <strong>Limitação de Responsabilidade</strong><br>
                        A plataforma atua como mera facilitadora de busca, não possuindo vínculo solidário quanto à qualidade técnica, assistencial ou clínica dos serviços prestados pela ILPI anunciante.
                    </li>
                    <li class="mt-2">
                        <strong>Regras de Conduta na Divulgação</strong><br>
                        É vedada a publicação de informações falsas, imagens meramente ilustrativas que não correspondam à realidade da instituição ou qualquer conteúdo que induza o consumidor a erro, sob pena de suspensão imediata da conta.
                    </li>
                </ol>
                <div class="alert alert-secondary small mt-3 mb-0">
                    Recomenda-se revisão jurídica especializada (Direito Digital e Saúde) considerando ANVISA RDC 502/2021 e Estatuto do Idoso.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<script>
function loadCidades(estadoId, selectedCidadeId = null) {
    const cidadeSelect = document.getElementById('cidade_id');
    cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
    const cidadeLoading = document.getElementById('cidadeLoading');
    if (cidadeLoading) cidadeLoading.classList.remove('d-none');
    
    if (!estadoId) {
        cidadeSelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
        if (cidadeLoading) cidadeLoading.classList.add('d-none');
        return;
    }

    fetch(`/api/cidades/${estadoId}`)
        .then(response => response.json())
        .then(data => {
            cidadeSelect.innerHTML = '<option value="">Selecione...</option>';
            data.forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade.id;
                option.textContent = cidade.nome;
                if (selectedCidadeId && (cidade.id == selectedCidadeId || cidade.nome == selectedCidadeId)) {
                    option.selected = true;
                }
                cidadeSelect.appendChild(option);
            });
            if (cidadeLoading) cidadeLoading.classList.add('d-none');
        })
        .catch(error => {
            console.error('Error:', error);
            cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
            if (cidadeLoading) cidadeLoading.classList.add('d-none');
        });
}

// Pre-load cities if coming back from error with old data
<?php if (isset($old['estado_id'])): ?>
    document.addEventListener('DOMContentLoaded', function() {
        loadCidades('<?= $old['estado_id'] ?>', '<?= $old['cidade_id'] ?? '' ?>');
    });
<?php endif; ?>

function buscarCep(cep) {
    cep = (cep || '').replace(/\D/g, '');
    if (!cep || cep.length !== 8) return;
    const cepLoading = document.getElementById('cepLoading');
    if (cepLoading) cepLoading.classList.remove('d-none');
    const ufMap = {
        <?php foreach ($estados as $estado): ?>
        '<?= $estado['uf'] ?>': <?= $estado['id'] ?>,
        <?php endforeach; ?>
    };
    const endereco = document.getElementById('endereco');
    const bairro = document.getElementById('bairro');
    const estadoSel = document.getElementById('estado_id');
    const cidadeSel = document.getElementById('cidade_id');
    if (endereco) endereco.setAttribute('disabled', 'disabled');
    if (bairro) bairro.setAttribute('disabled', 'disabled');
    if (estadoSel) estadoSel.setAttribute('disabled', 'disabled');
    if (cidadeSel) cidadeSel.setAttribute('disabled', 'disabled');
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(r => r.json())
        .then(d => {
            if (!d || d.erro) return;
            if (d.uf !== 'RS') return;
            if (endereco) endereco.value = d.logradouro || '';
            if (bairro) bairro.value = d.bairro || '';
            const ufId = ufMap[d.uf] || null;
            if (ufId) {
                estadoSel.value = ufId;
                loadCidades(ufId, d.localidade || null);
            }
        })
        .finally(() => {
            if (cepLoading) cepLoading.classList.add('d-none');
            if (endereco) endereco.removeAttribute('disabled');
            if (bairro) bairro.removeAttribute('disabled');
            if (estadoSel) estadoSel.removeAttribute('disabled');
            if (cidadeSel) cidadeSel.removeAttribute('disabled');
        })
        .catch(() => {});
}
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
<script>
function toggleVisibility(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
