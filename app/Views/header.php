<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divulga Cuidados Meus - Vagas em ILPIs</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- IMask JS -->
    <script src="https://unpkg.com/imask"></script>
    
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <!-- Custom JS -->
    <script src="/assets/js/scripts.js" defer></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/assets/img/logo.png" alt="Divulga Cuidados Meus" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Buscar Vagas</a>
                    </li>
                    <?php if (isset($_SESSION['ilpi_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/ilpi/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="/ilpi/logout">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/ilpi/login">Sou uma ILPI</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary-custom btn-sm ms-2" href="/ilpi/register">Cadastrar ILPI</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" style="min-height: 80vh;">
