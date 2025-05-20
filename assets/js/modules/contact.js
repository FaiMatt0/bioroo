/**
 * Contact page functionality
 */
export function init() {
    // Inizializza validazione form di contatto
    setupContactForm();
}

/**
 * Configura la validazione del form di contatto
 */
function setupContactForm() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    }
}

/**
 * Validate contact form
 */
function validateForm(form) {
    let isValid = true;
    
    // Reset previous validation
    form.querySelectorAll('.is-invalid').forEach(element => {
        element.classList.remove('is-invalid');
    });
    
    form.querySelectorAll('.invalid-feedback').forEach(element => {
        element.remove();
    });
    
    // Validate name
    const nameInput = form.querySelector('#name');
    if (!nameInput.value.trim()) {
        showError(nameInput, 'Il nome è obbligatorio');
        isValid = false;
    }
    
    // Validate email
    const emailInput = form.querySelector('#email');
    if (!emailInput.value.trim()) {
        showError(emailInput, 'L\'email è obbligatoria');
        isValid = false;
    } else if (!validateEmail(emailInput.value.trim())) {
        showError(emailInput, 'Inserisci un indirizzo email valido');
        isValid = false;
    }
    
    // Validate message
    const messageInput = form.querySelector('#message');
    if (!messageInput.value.trim()) {
        showError(messageInput, 'Il messaggio è obbligatorio');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Show error message for an input
 */
function showError(input, message) {
    input.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    input.parentNode.appendChild(errorDiv);
}

/**
 * Validate email format
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Initialize Google Map
 */
export function initMap() {
    // Check if map container exists
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;
    
    // Default location (Milano)
    const defaultLocation = { lat: 45.4642, lng: 9.1900 };
    
    // Create map
    const map = new google.maps.Map(mapContainer, {
        zoom: 15,
        center: defaultLocation,
        styles: [
            {
                "featureType": "administrative",
                "elementType": "all",
                "stylers": [{ "saturation": -100 }]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{ "saturation": -100 }]
            },
            {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [{ "saturation": -100 }]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [{ "saturation": -100 }]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{ "saturation": -100 }]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{ "saturation": -100 }]
            }
        ]
    });
    
    // Add marker
    const marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        title: 'Marketplace HQ',
        animation: google.maps.Animation.DROP
    });
    
    // Info window
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div style="text-align:center;">
                <h5 style="margin:0;padding:0;">Marketplace HQ</h5>
                <p style="margin:5px 0 0;">Via Roma 123, Milano</p>
            </div>
        `
    });
    
    // Show info window when marker is clicked
    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });
    
    // Open info window by default
    infoWindow.open(map, marker);
}