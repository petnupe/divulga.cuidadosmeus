<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0">
            <div class="card-header bg-primary-custom text-white text-center py-3">
                <h4 class="mb-0"><i class="fas fa-lock me-2"></i>Redefinir Senha</h4>
            </div>
            <div class="card-body p-5">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <p class="text-muted text-center mb-4">
                    Crie uma nova senha para sua conta.
                </p>

                <form action="/ilpi/reset-password" method="POST">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleVisibility('password', this)" aria-label="Mostrar/ocultar senha"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleVisibility('password_confirmation', this)" aria-label="Mostrar/ocultar senha"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-custom w-100 py-2">Salvar Nova Senha</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
