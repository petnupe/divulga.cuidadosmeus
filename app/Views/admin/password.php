<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white text-center py-3">
                <h4 class="mb-0"><i class="fas fa-key me-2"></i>Alterar Senha do Administrador</h4>
            </div>
            <div class="card-body p-5">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form action="/admin/password" method="POST">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha Atual</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleVisibility('current_password', this)" aria-label="Mostrar/ocultar senha"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nova Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleVisibility('new_password', this)" aria-label="Mostrar/ocultar senha"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleVisibility('confirm_password', this)" aria-label="Mostrar/ocultar senha"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2">Atualizar Senha</button>
                </form>
            </div>
        </div>
    </div>
</div>

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

<?php require_once __DIR__ . '/../footer.php'; ?>
