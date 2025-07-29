document.addEventListener('DOMContentLoaded', () => {
    // --- ОБЩАЯ ФУНКЦИЯ ДЛЯ API ---
    async function api(endpoint, data = {}) {
        try {
            const response = await fetch(`/api/${endpoint}.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error("API Error:", error);
            return { cart: [] };
        }
    }

    async function fetchTemplate(templateName) {
        const response = await fetch(`/templates/basket/${templateName}`);
        if (!response.ok) throw new Error(`Failed to fetch template ${templateName}`);
        return await response.text();
    }

    // --- ЛОГИКА СТРАНИЦЫ КОРЗИНЫ ---
    if (document.getElementById('full-cart-view')) {
        // ... (здесь вся логика для страницы корзины, она остается без изменений)
    }

    // --- ЛОГИКА СТРАНИЦЫ ОФОРМЛЕНИЯ ---
    if (document.querySelector('.checkout-form')) {
        // ... (здесь логика для физ/юр лиц, доставки и т.д., она остается)

        // --- ЛОГИКА ПЕРЕКЛЮЧЕНИЯ СПОСОБОВ ОПЛАТЫ ---
        document.body.addEventListener('click', (e) => {
            const paymentOption = e.target.closest('.payment-option');
            if (paymentOption) {
                const optionsContainer = paymentOption.closest('.payment-options');
                if (optionsContainer) {
                    optionsContainer.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                    paymentOption.classList.add('active');
                }
            }
        });
    }
});

function getNoun(number, one, two, five) {
    let n = Math.abs(number);
    n %= 100;
    if (n >= 5 && n <= 20) return five;
    n %= 10;
    if (n === 1) return one;
    if (n >= 2 && n <= 4) return two;
    return five;
}