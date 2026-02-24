<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0 text-primary-custom"><i class="fas fa-user-edit me-2"></i>Meu Perfil</h4>
                <a href="/ilpi/dashboard" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Voltar</a>
            </div>
            <div class="card-body p-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Dados atualizados com sucesso!</div>
                <?php endif; ?>

                <form action="/ilpi/profile" method="POST">
                    <h5 class="mb-3 border-bottom pb-2">Dados da Instituição</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome da ILPI *</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($ilpi['nome']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CNPJ</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($ilpi['cnpj']) ?>" readonly>
                            <small class="text-muted">CNPJ não pode ser alterado.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone / WhatsApp *</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($ilpi['telefone']) ?>" required placeholder="(00) 00000-0000">
                        </div>
                        <div class="col-md-6">
                            <label for="responsavel" class="form-label">Nome do Responsável *</label>
                            <input type="text" class="form-control" id="responsavel" name="responsavel" value="<?= htmlspecialchars($ilpi['responsavel']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email (Login)</label>
                        <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($ilpi['email']) ?>" readonly>
                        <small class="text-muted">Email não pode ser alterado.</small>
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Endereço</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="cep" class="form-label">CEP *</label>
                            <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($ilpi['cep']) ?>" required onblur="buscarCep(this.value)">
                            <div id="cepLoading" class="form-text text-muted d-none"><span class="spinner-border spinner-border-sm"></span> Carregando endereço...</div>
                        </div>
                        <div class="col-md-4">
                            <label for="estado_id" class="form-label">Estado *</label>
                            <select name="estado_id" id="estado_id" class="form-select" required onchange="loadCidades(this.value)">
                                <option value="">Selecione...</option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= $estado['id'] ?>" <?= ($ilpi['estado_id'] == $estado['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($estado['nome']) ?> (<?= htmlspecialchars($estado['uf']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cidade_id" class="form-label">Cidade *</label>
                            <select name="cidade_id" id="cidade_id" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($cidades as $cidade): ?>
                                    <option value="<?= $cidade['id'] ?>" <?= ($ilpi['cidade_id'] == $cidade['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cidade['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="cidadeLoading" class="form-text text-muted d-none"><span class="spinner-border spinner-border-sm"></span> Carregando cidades...</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="endereco" class="form-label">Logradouro *</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($ilpi['endereco']) ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label for="numero" class="form-label">Número *</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($ilpi['numero']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro *</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($ilpi['bairro']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="complemento" name="complemento" value="<?= htmlspecialchars($ilpi['complemento']) ?>">
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Descrição da ILPI</h5>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Breve descrição (máx. 300 caracteres)</label>
                        <textarea class="form-control" id="descricao" name="descricao" maxlength="300" rows="3" placeholder="Informe seus serviços, estrutura e diferenciais."><?= htmlspecialchars($ilpi['descricao'] ?? '') ?></textarea>
                        <small class="text-muted">Use até 300 caracteres.</small>
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Redes Sociais</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="facebook" class="form-label"><i class="fab fa-facebook text-primary me-1"></i> Facebook</label>
                            <div class="input-group">
                                <span class="input-group-text">facebook.com/</span>
                                <input type="text" class="form-control" id="facebook" name="facebook" value="<?= htmlspecialchars($ilpi['facebook']) ?>" placeholder="seu-perfil" pattern="^[A-Za-z0-9._-]+$">
                            </div>
                            <small class="text-muted">Informe apenas o que vem após facebook.com/</small>
                        </div>
                        <div class="col-md-6">
                            <label for="instagram" class="form-label"><i class="fab fa-instagram text-danger me-1"></i> Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text">instagram.com/</span>
                                <input type="text" class="form-control" id="instagram" name="instagram" value="<?= htmlspecialchars($ilpi['instagram']) ?>" placeholder="seu-usuario" pattern="^[A-Za-z0-9._-]+$">
                            </div>
                            <small class="text-muted">Informe apenas o que vem após instagram.com/</small>
                        </div>
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Alterar Senha (Opcional)</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Deixe em branco para manter a atual">
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_senha" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirm_senha" name="confirm_senha">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadCidades(estadoId, selectedCidade = null) {
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
                if (selectedCidade && (cidade.id == selectedCidade || cidade.nome == selectedCidade)) {
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
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(r => r.json())
        .then(d => {
            if (!d || d.erro) return;
            if (d.uf !== 'RS') return;
            const endereco = document.getElementById('endereco');
            const bairro = document.getElementById('bairro');
            const estadoSel = document.getElementById('estado_id');
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
        })
        .catch(() => {});
}
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
