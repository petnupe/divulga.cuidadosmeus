    </div>

    <footer class="bg-white py-4 mt-5 border-top">
        <div class="container text-center text-muted">
            <small>&copy; <?php echo date('Y'); ?> Divulga Cuidados Meus. Todos os direitos reservados.</small>
            <div class="mt-2">
                <a href="mailto:contato@cuidadosmeus.com.br" class="text-muted text-decoration-none"><i class="fas fa-envelope me-1"></i> contato@cuidadosmeus.com.br</a>
                <span class="mx-2">•</span>
                <a href="https://wa.me/5551991289103" target="_blank" rel="noopener" class="text-success text-decoration-none"><i class="fab fa-whatsapp me-1"></i> (51) 99128-9103</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- IMask -->
    <script src="https://unpkg.com/imask"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var cnpjInput = document.getElementById('cnpj');
            if (cnpjInput) {
                IMask(cnpjInput, {
                    mask: '00.000.000/0000-00'
                });
            }

            var phoneInput = document.getElementById('telefone');
            if (phoneInput) {
                IMask(phoneInput, {
                    mask: '(00) 00000-0000'
                });
            }

            var cepInput = document.getElementById('cep');
            if (cepInput) {
                IMask(cepInput, {
                    mask: '00000-000'
                });
            }

            var moneyInputs = document.querySelectorAll('.money-mask');
            moneyInputs.forEach(function(input) {
                IMask(input, {
                    mask: Number,
                    scale: 2,
                    signed: false,
                    thousandsSeparator: '.',
                    padFractionalZeros: true,
                    normalizeZeros: true,
                    radix: ',',
                    mapToRadix: ['.']
                });
            });
        });
    </script>
</body>
</html>
