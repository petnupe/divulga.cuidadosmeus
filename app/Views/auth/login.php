<?php require_once __DIR__ . '/../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-center text-primary-custom mb-4">Login ILPI</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form action="/ilpi/authenticate" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="text-end mb-3">
                        <a href="/ilpi/forgot-password" class="text-muted small text-decoration-none">Esqueci minha senha</a>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2">Entrar</button>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">Ainda não cadastrou sua instituição?</p>
                    <a href="/ilpi/register" class="btn btn-outline-primary-custom btn-sm">Criar conta</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
