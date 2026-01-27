<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0">
            <div class="card-header bg-primary-custom text-white text-center py-3">
                <h4 class="mb-0"><i class="fas fa-key me-2"></i>Recuperar Senha</h4>
            </div>
            <div class="card-body p-5">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <p class="text-muted text-center mb-4">
                    Digite seu email abaixo e enviaremos um link para redefinir sua senha.
                </p>

                <form action="/ilpi/forgot-password" method="POST">
                    <div class="mb-4">
                        <label for="email" class="form-label">E-mail Cadastrado</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="seu@email.com.br">
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 py-2">Enviar Link</button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="/ilpi/login" class="text-muted text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> Voltar para o Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
