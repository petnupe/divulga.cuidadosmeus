<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h4 class="mb-0 text-primary-custom">Cadastrar Novo Leito</h4>
            </div>
            <div class="card-body p-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="/ilpi/leitos/store" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Leito *</label>
                        <input type="text" class="form-control" id="tipo" name="tipo" placeholder="Ex: Quarto Individual, Quarto Duplo, Enfermaria" value="<?= htmlspecialchars($old['tipo'] ?? '') ?>" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="grau_dependencia_id" class="form-label">Grau de Dependência *</label>
                            <select name="grau_dependencia_id" id="grau_dependencia_id" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($graus as $grau): ?>
                                    <option value="<?= $grau['id'] ?>" <?= (isset($old['grau_dependencia_id']) && $old['grau_dependencia_id'] == $grau['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($grau['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="valor" class="form-label">Valor Mensal (R$) *</label>
                            <input type="text" class="form-control money-mask" id="valor" name="valor" placeholder="0,00" value="<?= htmlspecialchars($old['valor'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status Inicial *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="disponivel" <?= (isset($old['status']) && $old['status'] == 'disponivel') ? 'selected' : '' ?>>Disponível (Vaga Aberta)</option>
                            <option value="ocupado" <?= (isset($old['status']) && $old['status'] == 'ocupado') ? 'selected' : '' ?>>Ocupado (Sem Vaga)</option>
                        </select>
                        <div class="form-text">Leitos "Disponíveis" consomem do seu limite de leitos ativos do plano.</div>
                    </div>

                    <div class="mb-4">
                        <label for="fotos" class="form-label">Fotos do Leito</label>
                        <input type="file" class="form-control" id="fotos" name="fotos[]" multiple accept="image/png, image/jpeg">
                        <div class="form-text">Formatos aceitos: JPG, PNG. A quantidade de fotos é limitada pelo seu plano.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/ilpi/dashboard" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary-custom px-4">Salvar Leito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
