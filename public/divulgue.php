<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divulgue sua ILPI - Cuidados Meus</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3793901849610477" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --primary-color: #00a99d;
            --secondary-color: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
            background-color: #fff;
        }
        
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
        
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .btn-primary-custom:hover {
            background-color: #008f84;
            border-color: #008f84;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 169, 157, 0.3);
        }

        .navbar {
            padding: 15px 0;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(240,250,250,1) 0%, rgba(255,255,255,1) 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 80%;
            height: 200%;
            background: rgba(0, 169, 157, 0.05);
            transform: rotate(-15deg);
            border-radius: 50%;
            z-index: 0;
        }

        .feature-card {
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background-color: rgba(0, 169, 157, 0.1);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .plan-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            overflow: hidden;
            background: white;
        }

        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .plan-card.featured {
            border: 2px solid var(--primary-color);
            transform: scale(1.05);
            z-index: 2;
            box-shadow: 0 10px 40px rgba(0, 169, 157, 0.15);
        }

        .plan-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }

        .price-tag {
            font-size: 3rem;
            font-weight: 800;
            color: #333;
        }

        .price-tag small {
            font-size: 1rem;
            color: #777;
            font-weight: 400;
        }

        .check-list li {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .check-list i {
            margin-right: 10px;
        }

        .cta-section {
            background-color: var(--primary-color);
            color: white;
            padding: 80px 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/assets/img/logo.png" alt="Cuidados Meus" height="40" onerror="this.src='https://placehold.co/200x50?text=Cuidados+Meus&font=roboto'">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="/">Buscar Vagas</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link fw-bold text-primary-custom" href="/ilpi/login">Já sou parceiro</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary-custom py-2 px-4" href="/ilpi/register">Cadastrar ILPI</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section d-flex align-items-center">
        <div class="container position-relative" style="z-index: 1;">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="badge bg-light text-primary-custom px-3 py-2 rounded-pill mb-3 border border-success fw-bold">Para Instituições de Longa Permanência</span>
                    <h1 class="display-4 fw-bold mb-4 lh-sm">Divulgue seus leitos e preencha suas vagas com rapidez</h1>
                    <p class="lead text-muted mb-5">Conectamos famílias que buscam o melhor cuidado para seus idosos diretamente à sua instituição. Simples, rápido e eficiente.</p>
                    <div class="d-flex gap-3 flex-column flex-sm-row">
                        <a href="/ilpi/register" class="btn btn-primary-custom btn-lg shadow-lg">Começar Agora Grátis</a>
                        <a href="#planos" class="btn btn-outline-secondary btn-lg border-0 bg-white shadow-sm">Ver Planos</a>
                    </div>
                    <div class="mt-4 pt-3 d-flex align-items-center text-muted small">
                        <i class="fas fa-check-circle text-success me-2"></i> Sem fidelidade
                        <span class="mx-3">•</span>
                        <i class="fas fa-check-circle text-success me-2"></i> Cadastro simplificado
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="/assets/img/hero-landing.png" alt="Cuidado Humanizado" class="img-fluid rounded-4 shadow-lg" style="max-height: 500px; object-fit: cover;">
                </div>
            </div>
        </div>
    </header>

    <!-- Benefits Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Por que ser parceiro do Cuidados Meus?</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Nossa plataforma foi desenvolvida pensando nas necessidades específicas das ILPIs, oferecendo ferramentas para facilitar a gestão e divulgação.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search-location"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Visibilidade Direcionada</h4>
                        <p class="text-muted mb-0">Seja encontrado por quem realmente procura seus serviços. Famílias filtram por cidade e nível de dependência, encontrando sua ILPI no momento certo.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Gestão em Tempo Real</h4>
                        <p class="text-muted mb-0">Atualize a disponibilidade dos seus leitos instantaneamente. Mantenha seu quadro de vagas sempre atualizado sem complicações.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Contato Direto</h4>
                        <p class="text-muted mb-0">Receba contatos diretamente no seu WhatsApp. Sem intermediários, sem comissões sobre as mensalidades. A negociação é toda sua.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans Section -->
    <section id="planos" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Planos que cabem no seu orçamento</h2>
                <p class="text-muted">Escolha a opção ideal para o tamanho da sua instituição.</p>
            </div>

            <div class="row justify-content-center align-items-center g-4">
                <!-- Plano Básico -->
                <div class="col-lg-4 col-md-6">
                    <div class="card plan-card h-100">
                        <div class="card-body p-5">
                            <h5 class="fw-bold text-muted text-uppercase mb-4">Básico</h5>
                            <div class="price-tag mb-4">R$ 19,90<small>/mês</small></div>
                            <p class="text-muted mb-4">Para quem está começando e tem poucas vagas.</p>
                            
                            <ul class="list-unstyled check-list mb-5">
                                <li><i class="fas fa-check text-success"></i> <strong>1</strong> Leito ativo</li>
                                <li><i class="fas fa-check text-success"></i> <strong>1</strong> Foto por leito</li>
                                <li class="text-muted text-decoration-line-through"><i class="fas fa-times text-muted"></i> Redes Sociais no perfil</li>
                                <li><i class="fas fa-check text-success"></i> Painel de gestão</li>
                            </ul>
                            
                            <a href="/ilpi/register?plano=1" class="btn btn-outline-primary-custom w-100 rounded-pill py-2 fw-bold" style="border: 2px solid var(--primary-color); color: var(--primary-color);">Selecionar Básico</a>
                        </div>
                    </div>
                </div>

                <!-- Plano Sênior -->
                <div class="col-lg-4 col-md-6">
                    <div class="card plan-card featured h-100">
                        <div class="bg-primary-custom text-white text-center py-2 small fw-bold">RECOMENDADO</div>
                        <div class="card-body p-5">
                            <h5 class="fw-bold text-primary-custom text-uppercase mb-4">Sênior</h5>
                            <div class="price-tag mb-4">R$ 59,90<small>/mês</small></div>
                            <p class="text-muted mb-4">Ideal para instituições de médio porte.</p>
                            
                            <ul class="list-unstyled check-list mb-5">
                                <li><i class="fas fa-check text-success"></i> <strong>5</strong> Leitos ativos</li>
                                <li><i class="fas fa-check text-success"></i> <strong>5</strong> Fotos por leito</li>
                                <li><i class="fas fa-check text-success"></i> <strong>Redes Sociais</strong> no perfil</li>
                                <li><i class="fas fa-check text-success"></i> Painel de gestão</li>
                                <li><i class="fas fa-check text-success"></i> Destaque nas buscas</li>
                            </ul>
                            
                            <a href="/ilpi/register?plano=2" class="btn btn-primary-custom w-100 py-3 shadow">Selecionar Sênior</a>
                        </div>
                    </div>
                </div>

                <!-- Plano Master -->
                <div class="col-lg-4 col-md-6">
                    <div class="card plan-card h-100">
                        <div class="card-body p-5">
                            <h5 class="fw-bold text-muted text-uppercase mb-4">Master</h5>
                            <div class="price-tag mb-4">R$ 89,90<small>/mês</small></div>
                            <p class="text-muted mb-4">Para grandes instituições com alta rotatividade.</p>
                            
                            <ul class="list-unstyled check-list mb-5">
                                <li><i class="fas fa-check text-success"></i> <strong>15</strong> Leitos ativos</li>
                                <li><i class="fas fa-check text-success"></i> <strong>10</strong> Fotos por leito</li>
                                <li><i class="fas fa-check text-success"></i> <strong>Redes Sociais</strong> no perfil</li>
                                <li><i class="fas fa-check text-success"></i> Painel de gestão completo</li>
                                <li><i class="fas fa-check text-success"></i> Suporte prioritário</li>
                            </ul>
                            
                            <a href="/ilpi/register?plano=3" class="btn btn-outline-primary-custom w-100 rounded-pill py-2 fw-bold" style="border: 2px solid var(--primary-color); color: var(--primary-color);">Selecionar Master</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section text-center">
        <div class="container">
            <h2 class="display-5 fw-bold mb-4">Pronto para aumentar sua ocupação?</h2>
            <p class="lead mb-5 opacity-75">Junte-se a centenas de ILPIs que já estão transformando a forma de captar clientes.</p>
            <a href="/ilpi/register" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold text-primary-custom shadow">Cadastrar Minha ILPI Agora</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-white">Cuidados Meus</h5>
                    <p class="text-white-50">Conectando famílias e instituições de longa permanência com transparência e confiança.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-white">Links Rápidos</h5>
                    <ul class="list-unstyled text-white-50">
                        <li><a href="/" class="text-white-50 text-decoration-none hover-white">Buscar Vagas</a></li>
                        <li><a href="/ilpi/login" class="text-white-50 text-decoration-none hover-white">Área do Parceiro</a></li>
                        <li><a href="/ilpi/register" class="text-white-50 text-decoration-none hover-white">Cadastrar ILPI</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-white">Contato</h5>
                    <ul class="list-unstyled text-white-50">
                        <li><i class="fas fa-envelope me-2"></i> contato@cuidadosmeus.com.br</li>
                        <li><i class="fab fa-whatsapp me-2"></i> (51) 99128-9103</li>
                    </ul>
                </div>
            </div>
            <div class="border-top border-secondary mt-5 pt-4 text-center text-white-50">
                <p class="mb-0">&copy; 2024 Divulga Cuidados Meus. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
