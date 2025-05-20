/**
 * Home page functionality
 */
export function init() {
    // Inizializza il carousel con impostazioni personalizzate
    const heroCarousel = document.getElementById('heroCarousel');
    if (heroCarousel) {
        // Se stai usando Bootstrap nativo
        const carousel = new bootstrap.Carousel(heroCarousel, {
            interval: 5000,
            wrap: true,
            touch: true
        });
    }
    
    // Inizializza l'animazione per i numeri nelle sezioni statistiche
    animateNumbers();
    
    // Inizializza la validazione per il form della newsletter
    setupNewsletterForm();
}

/**
 * Animazione per i numeri nelle statistiche
 */
function animateNumbers() {
    const numberElements = document.querySelectorAll('.animate-number');
    
    numberElements.forEach(element => {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000; // 2 secondi
        const step = target / (duration / 16); // 16ms è circa 60fps
        
        let current = 0;
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    });
}

/**
 * Validazione e gestione del form newsletter
 */
function setupNewsletterForm() {
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            
            if (!email || !validateEmail(email)) {
                showMessage(newsletterForm, 'Inserisci un indirizzo email valido.', 'danger');
                return;
            }
            
            // In un caso reale, qui invieresti la richiesta al server
            // Per ora, simuliamo una risposta positiva
            showMessage(newsletterForm, 'Grazie per la tua iscrizione!', 'success');
            emailInput.value = '';
        });
    }
}

/**
 * Validazione semplice dell'email
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Mostra un messaggio nel form
 */
function showMessage(form, message, type) {
    // Rimuovi eventuali messaggi precedenti
    const oldMessage = form.querySelector('.form-message');
    if (oldMessage) {
        oldMessage.remove();
    }
    
    // Crea e aggiungi il nuovo messaggio
    const messageElement = document.createElement('div');
    messageElement.className = `form-message alert alert-${type} mt-3`;
    messageElement.textContent = message;
    
    form.appendChild(messageElement);
    
    // Rimuovi il messaggio dopo 3 secondi
    setTimeout(() => {
        messageElement.remove();
    }, 3000);
}