<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h4 class="mb-0 text-primary-custom">Editar Leito</h4>
            </div>
            <div class="card-body p-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                        if ($_GET['error'] == 'limit_reached') echo 'Limite de leitos ativos atingido!';
                        else echo 'Erro ao atualizar leito.';
                        ?>
                    </div>
                <?php endif; ?>

                <form action="/ilpi/leitos/update/<?= $leito['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Leito *</label>
                        <input type="text" class="form-control" id="tipo" name="tipo" value="<?= htmlspecialchars($leito['tipo']) ?>" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="grau_dependencia_id" class="form-label">Grau de Dependência *</label>
                            <select name="grau_dependencia_id" id="grau_dependencia_id" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($graus as $grau): ?>
                                    <option value="<?= $grau['id'] ?>" <?= ($leito['grau_dependencia_id'] == $grau['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($grau['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="valor" class="form-label">Valor Mensal (R$) *</label>
                            <input type="text" class="form-control money-mask" id="valor" name="valor" value="<?= htmlspecialchars(number_format($leito['valor'], 2, ',', '.')) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="disponivel" <?= ($leito['status'] == 'disponivel') ? 'selected' : '' ?>>Disponível (Vaga Aberta)</option>
                            <option value="ocupado" <?= ($leito['status'] == 'ocupado') ? 'selected' : '' ?>>Ocupado (Sem Vaga)</option>
                        </select>
                        <div class="form-text">Leitos "Disponíveis" consomem do seu limite de leitos ativos do plano.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Fotos Atuais</label>
                        <div class="row g-2">
                            <?php foreach ($fotos as $foto): ?>
                                <div class="col-md-3 col-6 position-relative">
                                    <img src="<?= htmlspecialchars($foto['url_foto']) ?>" class="img-thumbnail w-100" style="height: 100px; object-fit: cover;">
                                    <a href="/ilpi/leitos/delete-photo/<?= $foto['id'] ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="return confirm('Excluir esta foto?');">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($fotos)): ?>
                                <p class="text-muted small fst-italic">Sem fotos cadastradas.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="fotos" class="form-label">Adicionar Fotos</label>
                        <input type="file" class="form-control" id="fotos" name="fotos[]" multiple accept="image/png, image/jpeg">
                        <div class="form-text">Formatos aceitos: JPG, PNG. Limite do plano: <?= htmlspecialchars($foto_limit) ?> fotos. Restantes: <?= htmlspecialchars($foto_remaining) ?>.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/ilpi/dashboard" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary-custom px-4">Atualizar Leito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
