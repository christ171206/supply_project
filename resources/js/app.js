import './bootstrap';

import Alpine from 'alpinejs';
import './sweetalert.js';
import './currency-converter.js';
import './logout-confirmation.js';
import { requestNotificationPermission } from './pwa.js';

window.Alpine = Alpine;

Alpine.start();

// Initialiser les convertisseurs au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    if (window.currencyConverter) {
        window.currencyConverter.initializeConverters();
    }

    // Request notification permission for PWA
    requestNotificationPermission();
});
