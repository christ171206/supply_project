// Convertisseur de devises en temps réel
class CurrencyConverter {
    constructor() {
        this.rates = {}
        this.baseCurrency = 'XOF' // FCFA
        this.targetCurrencies = {
            'EUR': { name: 'Euro', symbol: '€' },
            'USD': { name: 'Dollar US', symbol: '$' },
            'GBP': { name: 'Livre Sterling', symbol: '£' }
        }
    }

    async fetchRates() {
        try {
            // Utiliser une API de conversion gratuite
            const response = await fetch('https://api.exchangerate-api.com/v4/latest/XOF')
            const data = await response.json()
            this.rates = data.rates
            return data.rates
        } catch (error) {
            console.error('Erreur lors de la récupération des taux:', error)
            // Taux par défaut en cas d'erreur
            this.rates = {
                'EUR': 0.00152,
                'USD': 0.00164,
                'GBP': 0.00128
            }
            return this.rates
        }
    }

    convert(amount, targetCurrency) {
        if (!this.rates[targetCurrency]) return null
        return (amount * this.rates[targetCurrency]).toFixed(2)
    }

    formatPrice(priceInFCFA, currency = 'EUR') {
        const converted = this.convert(priceInFCFA, currency)
        const target = this.targetCurrencies[currency]
        return `${target.symbol} ${converted}`
    }

    initializeConverters() {
        // Ajouter un bouton de conversion sur tous les prix
        const priceElements = document.querySelectorAll('[data-price]')

        priceElements.forEach(element => {
            const priceValue = element.getAttribute('data-price')
            const currentText = element.textContent

            // Créer un bouton de conversion
            const button = document.createElement('button')
            button.className = 'text-xs text-blue-600 hover:text-blue-700 ml-2 font-semibold'
            button.textContent = '💱 Convertir'
            button.style.border = 'none'
            button.style.background = 'none'
            button.style.cursor = 'pointer'

            button.onclick = (e) => {
                e.preventDefault()
                showCurrencyModal(priceValue, currentText)
            }

            element.appendChild(button)
        })
    }
}

// Créer une instance globale
window.currencyConverter = new CurrencyConverter()
window.currencyConverter.fetchRates()

// Modal de conversion
function showCurrencyModal(priceInFCFA, originalText) {
    const converter = window.currencyConverter

    let html = `
        <div style="text-align: left;">
            <p style="margin-bottom: 15px; font-weight: bold;">Prix original: <span style="color: #3b82f6;">${originalText}</span></p>
            <div style="border-top: 1px solid #e5e7eb; padding-top: 15px;">
                <h4 style="margin-bottom: 10px; font-weight: 600;">Convertir en:</h4>
    `

    Object.entries(converter.targetCurrencies).forEach(([code, info]) => {
        const converted = converter.convert(priceInFCFA, code)
        html += `
            <div style="padding: 8px; background: #f3f4f6; margin: 5px 0; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                <span>${info.symbol} ${info.name}</span>
                <strong style="color: #10b981;">${info.symbol} ${converted}</strong>
            </div>
        `
    })

    html += `
            </div>
        </div>
    `

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '💱 Conversion de Devises',
            html: html,
            icon: 'info',
            confirmButtonText: 'Fermer',
            confirmButtonColor: '#3b82f6'
        })
    }
}

export { CurrencyConverter, showCurrencyModal }
