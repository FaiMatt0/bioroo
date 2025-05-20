</div><!-- End of container -->
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Marketplace</h5>
                    <p>Il tuo marketplace online per prodotti di qualità.</p>
                </div>
                <div class="col-md-6">
                    <h5>Contattaci</h5>
                    <p><i class="fas fa-envelope me-2"></i> info@marketplace.com</p>
                    <p><i class="fas fa-phone me-2"></i> +39 02 1234567</p>
                </div>
            </div>
            <hr class="my-3 bg-light">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> Marketplace. Tutti i diritti riservati.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
    // Basic functionality for navbar toggle
    document.addEventListener('DOMContentLoaded', function() {
        // Make sure Bootstrap JS is loaded
        if (typeof bootstrap !== 'undefined') {
            // Initialize any dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        }
    });
    </script>
</body>
</html>