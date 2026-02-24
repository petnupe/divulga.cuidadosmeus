<?php require_once 'header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h1 class="display-4 fw-bold text-primary-custom">Encontre a ILPI ideal</h1>
        <p class="lead text-muted">Vagas disponíveis em Instituições de Longa Permanência para Idosos</p>
    </div>
</div>

<div class="row mb-5 justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm p-4 bg-light">
            <form action="/" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="estado_id" class="form-label">Estado</label>
                    <select id="estado_id" class="form-select" onchange="loadCidades(this.value)">
                        <option value="">Selecione...</option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $estado['id'] ?>"><?= htmlspecialchars($estado['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="cidade_id" class="form-label">Cidade</label>
                    <select name="cidade_id" id="cidade_id" class="form-select">
                        <option value="">Todas as Cidades</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="grau_id" class="form-label">Grau de Dependência</label>
                    <select name="grau_id" id="grau_id" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($graus as $grau): ?>
                            <option value="<?= $grau['id'] ?>" <?= $selectedGrauId == $grau['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($grau['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-search me-2"></i>Buscar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <?php if (empty($ilpis)): ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h3 class="text-muted">Nenhuma ILPI encontrada</h3>
            <p>Tente ajustar os filtros de busca para encontrar mais resultados.</p>
        </div>
    <?php else: ?>
        <?php foreach ($ilpis as $ilpi): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm hover-shadow">
                    <?php if (!empty($ilpi['foto_capa'])): ?>
                        <img src="<?= htmlspecialchars($ilpi['foto_capa']) ?>" class="card-img-top" alt="Foto ILPI" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary d-flex align-items-center justify-content-center text-white" style="height: 200px;">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success">Disponível</span>
                            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($ilpi['cidade_nome']) ?>/<?= htmlspecialchars($ilpi['estado_uf']) ?></small>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($ilpi['nome']) ?></h5>
                        <p class="text-muted small mb-3"><?= htmlspecialchars($ilpi['bairro']) ?></p>
                        
                        <div class="mb-3">
                            <span class="d-block text-muted small">A partir de</span>
                            <span class="fs-4 fw-bold text-primary-custom">R$ <?= number_format($ilpi['valor_minimo'], 2, ',', '.') ?></span>
                            <span class="text-muted small">/mês</span>
                        </div>
                        
                        <p class="card-text small text-muted mb-3">
                            <?= htmlspecialchars($ilpi['endereco']) ?>, <?= htmlspecialchars($ilpi['numero']) ?>
                            <?= $ilpi['complemento'] ? ' - ' . htmlspecialchars($ilpi['complemento']) : '' ?>
                        </p>

                        <div class="d-grid gap-2">
                            <a href="https://wa.me/55<?= preg_replace('/\D/', '', $ilpi['telefone']) ?>?text=Olá, vi seu anúncio no Divulga Cuidados Meus e gostaria de mais informações." 
                               class="btn btn-outline-success" target="_blank" onclick="trackClick('whatsapp', <?= (int)$ilpi['id'] ?>)">
                                <i class="fab fa-whatsapp me-2"></i>Contatar via WhatsApp
                            </a>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($ilpi['endereco'] . ', ' . $ilpi['numero'] . ' - ' . $ilpi['cidade_nome'] . ' - ' . $ilpi['estado_uf']) ?>" 
                               class="btn btn-outline-secondary btn-sm" target="_blank" onclick="trackClick('map', <?= (int)$ilpi['id'] ?>)">
                                <i class="fas fa-map-marked-alt me-2"></i>Ver no Mapa
                            </a>
                            
                            <?php if (!empty($ilpi['todas_fotos'])): ?>
                                <button type="button" class="btn btn-primary-custom btn-sm" onclick="trackClick('photos_open', <?= (int)$ilpi['id'] ?>); openPhotoGallery('<?= htmlspecialchars($ilpi['todas_fotos']) ?>', '<?= htmlspecialchars(addslashes($ilpi['nome'])) ?>')">
                                    <i class="fas fa-images me-2"></i>Ver Fotos
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($ilpi['exibir_redes_sociais']): ?>
                            <div class="mt-3 text-center border-top pt-2">
                                <?php if (!empty($ilpi['facebook'])): ?>
                                    <a href="https://facebook.com/<?= htmlspecialchars($ilpi['facebook']) ?>" class="text-secondary me-3" target="_blank" rel="noopener" onclick="trackClick('facebook', <?= (int)$ilpi['id'] ?>)"><i class="fab fa-facebook fa-lg"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($ilpi['instagram'])): ?>
                                    <a href="https://instagram.com/<?= htmlspecialchars($ilpi['instagram']) ?>" class="text-secondary" target="_blank" rel="noopener" onclick="trackClick('instagram', <?= (int)$ilpi['id'] ?>)"><i class="fab fa-instagram fa-lg"></i></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Photo Gallery Modal -->
<div class="modal fade" id="photoGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryTitle">Fotos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="carouselGallery" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="galleryInner">
                        <!-- Items will be injected here -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselGallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselGallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openPhotoGallery(photosString, ilpiName) {
    const photos = photosString.split(',');
    const galleryInner = document.getElementById('galleryInner');
    const galleryTitle = document.getElementById('galleryTitle');
    
    galleryTitle.textContent = ilpiName;
    galleryInner.innerHTML = '';
    
    photos.forEach((photo, index) => {
        const item = document.createElement('div');
        item.className = `carousel-item ${index === 0 ? 'active' : ''}`;
        
        const img = document.createElement('img');
        img.src = photo;
        img.className = 'd-block w-100';
        img.style.height = '500px';
        img.style.objectFit = 'contain';
        img.style.backgroundColor = '#000';
        
        item.appendChild(img);
        galleryInner.appendChild(item);
    });
    
    const modal = new bootstrap.Modal(document.getElementById('photoGalleryModal'));
    modal.show();
}

function trackClick(type, ilpiId) {
    try {
        fetch('/api/track', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ type: type, ilpi_id: ilpiId })
        });
    } catch (e) {}
}
function loadCidades(estadoId) {
    const cidadeSelect = document.getElementById('cidade_id');
    cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
    
    if (!estadoId) {
        cidadeSelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
        return;
    }

    fetch(`/api/cidades/${estadoId}`)
        .then(response => response.json())
        .then(data => {
            cidadeSelect.innerHTML = '<option value="">Todas as Cidades</option>';
            data.forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade.id;
                option.textContent = cidade.nome;
                if (cidade.id == "<?= $selectedCidadeId ?>") {
                    option.selected = true;
                }
                cidadeSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
        });
}
</script>

<?php require_once 'footer.php'; ?>
