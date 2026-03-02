<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divulga Cuidados Meus - Vagas em ILPIs</title>
    <meta name="description" content="Encontre a ILPI ideal para idosos. Vagas em Instituições de Longa Permanência com fotos, preços e contato direto.">
    
    <?php 
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $canonicalUrl = 'https://cuidadosmeus.com.br/divulga' . ($currentPath === '/' ? '' : $currentPath);
    ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
    <meta property="og:title" content="Divulga Cuidados Meus - Vagas em ILPIs">
    <meta property="og:description" content="Encontre a ILPI ideal para idosos. Vagas em Instituições de Longa Permanência com fotos, preços e contato direto.">
    <meta property="og:image" content="https://cuidadosmeus.com.br/divulga/assets/img/logo.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
    <meta property="twitter:title" content="Divulga Cuidados Meus - Vagas em ILPIs">
    <meta property="twitter:description" content="Encontre a ILPI ideal para idosos. Vagas em Instituições de Longa Permanência com fotos, preços e contato direto.">
    <meta property="twitter:image" content="https://cuidadosmeus.com.br/divulga/assets/img/logo.png">
    
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
    <?php
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if (!preg_match('#^/(admin|ilpi)(/|$)#', $uri)) {
    ?>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3793901849610477" crossorigin="anonymous"></script>
    <?php } ?>
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
