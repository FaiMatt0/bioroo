</div>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <h5>Marketplace</h5>
                    <p>Il tuo marketplace online per prodotti di qualità.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Link rapidi</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>/" class="text-white">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/products" class="text-white">Prodotti</a></li>
                        <li><a href="<?= BASE_URL ?>/about" class="text-white">Chi siamo</a></li>
                        <li><a href="<?= BASE_URL ?>/contact" class="text-white">Contatti</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Informazioni</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>/sustainability" class="text-white">Sostenibilità</a></li>
                        <li><a href="<?= BASE_URL ?>/terms" class="text-white">Termini e condizioni</a></li>
                        <li><a href="<?= BASE_URL ?>/privacy" class="text-white">Privacy policy</a></li>
                        <li><a href="<?= BASE_URL ?>/shipping" class="text-white">Spedizioni</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Contattaci</h5>
                    <address>
                        <p><i class="fas fa-map-marker-alt me-2"></i> Via Roma 123, Milano</p>
                        <p><i class="fas fa-phone me-2"></i> +39 02 1234567</p>
                        <p><i class="fas fa-envelope me-2"></i> info@marketplace.com</p>
                    </address>
                </div>
            </div>
            <hr class="my-3 bg-light">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> Marketplace. Tutti i diritti riservati.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Realizzato con <i class="fas fa-heart text-danger"></i> in Italia</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Specifica la pagina corrente per il JS
        document.body.dataset.page = "<?= isset($pageType) ? $pageType : '' ?>";
    </script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/main.js" type="module"></script>
</body>
</html>