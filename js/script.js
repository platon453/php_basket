
document.addEventListener('DOMContentLoaded', () => {
    // --- ОБЩАЯ ФУНКЦИЯ ДЛЯ API ---
    async function api(action, data = {}) {
        try {
            const response = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action, ...data })
            });
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error("API Error:", error);
            // Можно показать пользователю сообщение об ошибке
            return { cart: [] }; // Возвращаем пустую корзину в случае ошибки
        }
    }

    // --- ЛОГИКА СТРАНИЦЫ КОРЗИНЫ (cart.html) ---
    if (document.getElementById('full-cart-view')) {
        const fullCartView = document.getElementById('full-cart-view');
        const emptyCartView = document.getElementById('empty-cart-view');
        const itemsContainer = fullCartView.querySelector('.cart-items');
        const summaryContainer = fullCartView.querySelector('.order-summary');
        const itemsCountSpan = fullCartView.querySelector('.item-count');
        const clearCartBtn = fullCartView.querySelector('.clear-cart');

        const renderCart = (cart) => {
            if (!cart || cart.length === 0) {
                fullCartView.style.display = 'none';
                emptyCartView.style.display = 'flex'; // Используем flex для центрирования
                return;
            }

            fullCartView.style.display = 'block';
            emptyCartView.style.display = 'none';

            itemsContainer.innerHTML = '<h3>Товары в наличии</h3>';
            cart.forEach(item => {
                const itemHTML = `
                    <div class="cart-item" data-item-id="${item.id}">
                        <img src="${item.image}" alt="${item.title}" class="book-cover">
                        <div class="item-details">
                            <h4>${item.title}</h4>
                            <p class="author">${item.author}</p>
                        </div>
                        <div class="item-controls">
                            <div class="quantity-controls">
                                <button class="qty-btn minus" aria-label="Уменьшить количество">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="qty-btn plus" aria-label="Увеличить количество">+</button>
                            </div>
                            <div class="price-info">
                                <span class="current-price">${item.discountPrice * item.quantity} ₽</span>
                                ${item.price > item.discountPrice ? `<span class="original-price">${item.price * item.quantity} ₽</span>` : ''}
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="action-btn remove" aria-label="Удалить товар">🗑️</button>
                        </div>
                    </div>`;
                itemsContainer.insertAdjacentHTML('beforeend', itemHTML);
            });

            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const totalPrice = cart.reduce((sum, item) => sum + item.discountPrice * item.quantity, 0);
            const totalOriginalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const totalDiscount = totalOriginalPrice - totalPrice;

            itemsCountSpan.textContent = `${totalItems} ${getNoun(totalItems, 'товар', 'товара', 'товаров')}`;
            
            summaryContainer.innerHTML = `
                <h3>Ваш заказ</h3>
                <div class="summary-row">
                    <span>Товары (${totalItems})</span>
                    <span>${totalOriginalPrice} ₽</span>
                </div>
                ${totalDiscount > 0 ? `
                <div class="summary-row">
                    <span>Скидка</span>
                    <span class="discount-amount">-${totalDiscount} ₽</span>
                </div>` : ''}
                <div class="summary-row total">
                    <span>Итого</span>
                    <span>${totalPrice} ₽</span>
                </div>`;
        };

        const handleCartInteraction = async (e) => {
            const target = e.target;
            const itemEl = target.closest('.cart-item');
            
            if (target === clearCartBtn) {
                const { cart } = await api('clear');
                renderCart(cart);
                return;
            }

            if (!itemEl) return;

            const itemId = parseInt(itemEl.dataset.itemId, 10);
            let quantity = parseInt(itemEl.querySelector('.quantity').textContent, 10);

            if (target.matches('.plus')) quantity++;
            if (target.matches('.minus')) quantity--;
            if (target.matches('.remove')) quantity = 0;
            
            if (quantity < 0) quantity = 0;

            const { cart } = await api('update_quantity', { itemId, quantity });
            renderCart(cart);
        };
        
        fullCartView.addEventListener('click', handleCartInteraction);
        
        // Первоначальная загрузка корзины
        api('get').then(response => renderCart(response.cart));
    }

    // --- ЛОГИКА СТРАНИЦЫ ОФОРМЛЕНИЯ (checkout.html) ---
    if (document.querySelector('.checkout-form')) {
        // Переключение между физ. и юр. лицом
        const personTypeTabs = document.querySelectorAll('.person-type-tabs .tab');
        const personTypeContents = document.querySelectorAll('.checkout-form > .tab-content');

        personTypeTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                personTypeTabs.forEach(t => t.classList.remove('active'));
                personTypeContents.forEach(c => {
                    c.classList.remove('active');
                    c.style.display = 'none'; // Скрываем контент
                });

                tab.classList.add('active');
                const activeContent = document.querySelector(`.checkout-form > .tab-content[data-tab="${tab.dataset.tab}"]`);
                if (activeContent) {
                    activeContent.classList.add('active');
                    activeContent.style.display = 'block'; // Показываем контент
                }
            });
        });

        // Переключение способов доставки
        const deliveryOptions = document.querySelectorAll('.delivery-option');
        const deliveryForms = document.querySelectorAll('.delivery-form');
        const pickupContainer = document.querySelector('.delivery-form[data-delivery-form="pickup"]');

        deliveryOptions.forEach(option => {
            option.addEventListener('click', async () => { // делаем обработчик асинхронным
                deliveryOptions.forEach(o => o.classList.remove('active'));
                deliveryForms.forEach(f => f.style.display = 'none');

                option.classList.add('active');
                const deliveryType = option.dataset.delivery;

                if (deliveryType === 'pickup') {
                    // Загружаем и показываем frame4.html для самовывоза
                    if (pickupContainer.innerHTML === '') { // Загружаем только если он пуст
                        try {
                            const response = await fetch('my_good_front/frame4.html');
                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                            const content = await response.text();
                            pickupContainer.innerHTML = content;
                        } catch (error) {
                            console.error("Failed to fetch frame4.html:", error);
                            pickupContainer.innerHTML = '<p>Не удалось загрузить пункты самовывоза. Попробуйте позже.</p>';
                        }
                    }
                    pickupContainer.style.display = 'block';
                } else {
                    // Показываем другие формы
                    const activeForm = document.querySelector(`.delivery-form[data-delivery-form="${deliveryType}"]`);
                    if (activeForm) {
                        activeForm.style.display = 'block';
                    }
                }
            });
        });

        // Загрузка данных о заказе в сайдбар
        api('get').then(response => {
            const { cart } = response;
            const summaryContainer = document.querySelector('.order-summary-sidebar .order-summary');
            
            if (cart && cart.length > 0) {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                const totalPrice = cart.reduce((sum, item) => sum + item.discountPrice * item.quantity, 0);
                const totalOriginalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                const totalDiscount = totalOriginalPrice - totalPrice;

                summaryContainer.innerHTML = `
                    <h3>Ваш заказ</h3>
                    <div class="summary-row">
                        <span>Товары (${totalItems})</span>
                        <span>${totalOriginalPrice} ₽</span>
                    </div>
                    ${totalDiscount > 0 ? `
                    <div class="summary-row">
                        <span>Скидка</span>
                        <span class="discount-amount">-${totalDiscount} ₽</span>
                    </div>` : ''}
                    <div class="summary-row total">
                        <span>Итого</span>
                        <span>${totalPrice} ₽</span>
                    </div>`;
            } else {
                summaryContainer.innerHTML = '<p>Ваша корзина пуста.</p>';
                document.querySelector('.pay-btn').disabled = true;
            }
        });
    }
});

// Вспомогательная функция для склонения слов
function getNoun(number, one, two, five) {
    let n = Math.abs(number);
    n %= 100;
    if (n >= 5 && n <= 20) {
        return five;
    }
    n %= 10;
    if (n === 1) {
        return one;
    }
    if (n >= 2 && n <= 4) {
        return two;
    }
    return five;
}
