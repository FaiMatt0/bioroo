// Inizializzazione dell'applicazione
document.addEventListener('DOMContentLoaded', function() {
    // Importa e inizializza i moduli in base alla pagina corrente
    const currentPage = document.body.dataset.page;
    
    // Inizializza funzionalità esistenti
    initExistingFunctionality();
    
    // Inizializza moduli specifici per pagina
    if (currentPage) {
        initPageModules(currentPage);
    }
});

// Inizializza le funzionalità esistenti
function initExistingFunctionality() {
    // Gestione messaggi flash
    const flashMessages = document.querySelectorAll('.alert-dismissible');
    if (flashMessages.length > 0) {
        flashMessages.forEach(message => {
            setTimeout(() => {
                const btnClose = message.querySelector('.btn-close');
                if (btnClose) {
                    btnClose.click();
                }
            }, 5000); // Chiude automaticamente i messaggi dopo 5 secondi
        });
    }
    
    // Gestione pulsanti quantità per prodotti
    const quantityInputs = document.querySelectorAll('[id^="quantity-"]');
    if (quantityInputs.length > 0) {
        quantityInputs.forEach(input => {
            const decreaseBtn = input.previousElementSibling;
            const increaseBtn = input.nextElementSibling;
            
            if (decreaseBtn && increaseBtn) {
                decreaseBtn.addEventListener('click', function() {
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                    }
                });
                
                increaseBtn.addEventListener('click', function() {
                    let value = parseInt(input.value);
                    let maxValue = parseInt(input.getAttribute('max') || 99);
                    if (value < maxValue) {
                        input.value = value + 1;
                    }
                });
            }
        });
    }
    
    // Gestione tab bootstrap
    const productTabs = document.getElementById('productTabs');
    if (productTabs) {
        const tabLinks = productTabs.querySelectorAll('.nav-link');
        const tabContents = document.querySelectorAll('.tab-pane');
        
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Rimuovi classe active da tutti i tab
                tabLinks.forEach(l => l.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('show', 'active'));
                
                // Aggiungi classe active al tab corrente
                this.classList.add('active');
                const target = document.querySelector(this.getAttribute('data-bs-target'));
                if (target) {
                    target.classList.add('show', 'active');
                }
            });
        });
    }
    
    // Validazione form
    const forms = document.querySelectorAll('.needs-validation');
    if (forms.length > 0) {
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    }
    
    // Gestione immagini prodotti
    const productThumbnails = document.querySelectorAll('.product-thumbnail');
    const productMainImage = document.querySelector('.product-main-image');
    if (productThumbnails.length > 0 && productMainImage) {
        productThumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const imageSrc = this.getAttribute('src');
                productMainImage.setAttribute('src', imageSrc);
                
                // Aggiorna classe active
                productThumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
    
    // Gestione ricerca
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="keyword"]');
        
        searchForm.addEventListener('submit', event => {
            if (!searchInput.value.trim()) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    }
    
    // Gestione categorie mobile
    const categoryToggle = document.getElementById('category-toggle');
    const categoryList = document.getElementById('category-list');
    if (categoryToggle && categoryList) {
        categoryToggle.addEventListener('click', function() {
            categoryList.classList.toggle('d-none');
        });
    }
    
    // Inizializza tooltips e popovers di Bootstrap
    initBootstrapComponents();
}

// Configurazione componenti Bootstrap
function initBootstrapComponents() {
    // Inizializza tooltip (se Bootstrap JS è caricato)
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Inizializza popover
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }
}

// Inizializza moduli in base alla pagina corrente
function initPageModules(page) {
    switch(page) {
        case 'home':
            // Inizializza homepage
            import('./modules/home.js').then(module => {
                module.init();
            });
            break;
        case 'products':
            // Inizializza filtri prodotto, paginazione, etc.
            import('./modules/products.js').then(module => {
                module.init();
            });
            break;
        case 'product-detail':
            // Inizializza galleria immagini, recensioni, etc.
            import('./modules/products.js').then(module => {
                module.initProductDetail();
            });
            break;
        case 'cart':
            // Inizializza funzionalità carrello
            import('./modules/cart.js').then(module => {
                module.init();
            });
            break;
        case 'checkout':
            // Inizializza validazione form checkout e integrazione pagamenti
            import('./modules/checkout.js').then(module => {
                module.init();
            });
            break;
        case 'auth':
            // Inizializza validazione form login/registrazione
            import('./modules/auth.js').then(module => {
                module.init();
            });
            break;
        case 'profile':
            // Inizializza funzionalità profilo utente
            import('./modules/profile.js').then(module => {
                module.init();
            });
            break;
        case 'about':
            // Inizializza funzionalità pagina about
            // Per ora non richiede JavaScript specifico
            break;
        case 'contact':
            // Inizializza funzionalità pagina contatti
            import('./modules/contact.js').then(module => {
                module.init();
            });
            
            // Carica Google Maps se la API è disponibile
            if (typeof google !== 'undefined' && google.maps) {
                import('./modules/contact.js').then(module => {
                    module.initMap();
                });
            }
            break;
        case 'sustainability':
            // Inizializza funzionalità pagina sostenibilità
            // Per ora non richiede JavaScript specifico
            break;
        case 'wip':
            // Inizializza funzionalità pagina work in progress
            // Per ora non richiede JavaScript specifico
            break;
        case 'admin':
            // Inizializza pannello admin e datatables
            import('./modules/admin.js').then(module => {
                module.init();
            });
            break;
        default:
            // Nessuna inizializzazione speciale
            break;
    }
}