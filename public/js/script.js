document.addEventListener('DOMContentLoaded', () => {
    // --- ОБЩАЯ ФУНКЦИЯ ДЛЯ API ---
    async function api(action, data = {}) {
        try {
            const response = await fetch('/index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action, ...data })
            });
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error("API Error:", error);
            return { cart: [] }; // Возвращаем пустую корзину в случае ошибки
        }
    }

    async function fetchTemplate(templateName) {
        const response = await fetch('/index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_template', name: templateName })
        });
        if (!response.ok) throw new Error(`Failed to fetch template ${templateName}`);
        return await response.text();
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
                emptyCartView.style.display = 'flex';
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
                    c.style.display = 'none';
                });

                tab.classList.add('active');
                const activeContent = document.querySelector(`.checkout-form > .tab-content[data-tab="${tab.dataset.tab}"]`);
                if (activeContent) {
                    activeContent.classList.add('active');
                    activeContent.style.display = 'block';
                }
            });
        });

        // Переключение способов доставки
        const deliveryOptions = document.querySelectorAll('.delivery-option');
        const deliveryForms = document.querySelectorAll('.delivery-form');

        deliveryOptions.forEach(option => {
            option.addEventListener('click', async () => {
                deliveryOptions.forEach(o => o.classList.remove('active'));
                deliveryForms.forEach(f => f.style.display = 'none');

                option.classList.add('active');
                const deliveryType = option.dataset.delivery;
                const container = document.querySelector(`.delivery-form[data-delivery-form="${deliveryType}"]`);

                if (!container) return;

                const isLoaded = container.dataset.loaded === 'true';

                if (!isLoaded) {
                    let templateName = '';
                    if (deliveryType === 'pickup') {
                        templateName = 'frame4.html';
                    } else if (deliveryType === 'courier') {
                        templateName = 'frame7.html';
                    } else if (deliveryType === 'postal') {
                        templateName = 'frame8.html';
                    }

                    if (templateName) {
                        try {
                            container.innerHTML = await fetchTemplate(templateName);
                            container.dataset.loaded = 'true';
                        } catch (error) {
                            console.error(error);
                            container.innerHTML = '<p>Не удалось загрузить информацию. Попробуйте позже.</p>';
                        }
                    }
                }
                
                container.style.display = 'block';
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

        // --- ОБЩАЯ ЛОГИКА МОДАЛЬНЫХ ОКОН ---
        const mainModalOverlay = document.querySelector('.modal-overlay');
        const mainModalContainer = mainModalOverlay.querySelector('.modal');
        let legalEntityData = null; // Для хранения данных юр. лица
        let checkoutData = {}; // Для хранения данных формы

        const openModal = (content) => {
            mainModalContainer.innerHTML = content;
            mainModalOverlay.style.display = 'flex';
        };

        const closeModal = () => {
            mainModalOverlay.style.display = 'none';
            mainModalContainer.innerHTML = ''; // Очищаем содержимое при закрытии
        };

        // --- ЛОГИКА ДЛЯ ЮРИДИЧЕСКОГО ЛИЦА ---
        const legalTabContent = document.querySelector('.tab-content[data-tab="legal"]');

        const renderLegalEntityInfo = () => {
            if (legalEntityData && legalEntityData.orgName) {
                legalTabContent.innerHTML = `
                    <div class="saved-legal-info" style="padding: 15px; border: 1px solid #eee; border-radius: 4px;">
                        <h4>${legalEntityData.orgName}</h4>
                        <p style="color: #666; margin-bottom: 10px;">ИНН: ${legalEntityData.orgInn}</p>
                        <button type="button" class="edit-legal-btn" style="background: none; border: 1px solid #ccc; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Изменить</button>
                    </div>
                `;
            } else {
                legalTabContent.innerHTML = '<button type="button" class="add-legal-btn">УКАЗАТЬ ЮРИДИЧЕСКОЕ ЛИЦО</button>';
            }
        };
        
        const openLegalEntityModal = async () => {
            try {
                const formHtml = await fetchTemplate('frame10.html');
                openModal(formHtml);
                
                if (legalEntityData) {
                    const form = mainModalContainer.querySelector('.legal-form');
                    for (const key in legalEntityData) {
                        if (form.elements[key]) {
                            form.elements[key].value = legalEntityData[key];
                        }
                    }
                }
            } catch (error) {
                console.error(error);
                openModal('<p>Ошибка загрузки формы.</p><button class="modal-close">×</button>');
            }
        };

        legalTabContent.addEventListener('click', (e) => {
            if (e.target.matches('.add-legal-btn') || e.target.matches('.edit-legal-btn')) {
                openLegalEntityModal();
            }
        });

        // --- ОБРАБОТЧИКИ МОДАЛЬНЫХ ОКОН (ЗАКРЫТИЕ И СОХРАНЕНИЕ ЮР. ЛИЦА) ---
        mainModalOverlay.addEventListener('click', (e) => {
            if (e.target === mainModalOverlay || e.target.closest('.modal-close')) {
                closeModal();
            }
            if (e.target.id === 'save-legal-data-btn') {
                const form = mainModalContainer.querySelector('.legal-form');
                if (form) {
                    const formData = new FormData(form);
                    legalEntityData = Object.fromEntries(formData.entries());
                    renderLegalEntityInfo();
                    closeModal();
                }
            }
        });

        // --- СБОР ДАННЫХ И ОТКРЫТИЕ МОДАЛЬНОГО ОКНА ОПЛАТЫ ---
        const getCheckoutData = () => {
            const activePersonTab = document.querySelector('.person-type-tabs .tab.active').dataset.tab;
            const activeDeliveryOption = document.querySelector('.delivery-option.active').dataset.delivery;
            const deliveryForm = document.querySelector(`.delivery-form[data-delivery-form="${activeDeliveryOption}"]`);
            const deliveryData = {};
            if (deliveryForm) {
                deliveryForm.querySelectorAll('input, select, textarea').forEach(input => {
                    const key = input.name || input.placeholder;
                    if (key) deliveryData[key] = input.value;
                });
            }

            return {
                personType: activePersonTab,
                deliveryType: activeDeliveryOption,
                deliveryDetails: deliveryData,
                legalDetails: activePersonTab === 'legal' ? legalEntityData : null
            };
        };

        document.querySelector('.pay-btn').addEventListener('click', async () => {
            checkoutData = getCheckoutData();
            
            try {
                // Получаем актуальную сумму
                const { cart } = await api('get');
                if (!cart || cart.length === 0) {
                    alert("Ваша корзина пуста!");
                    return;
                }
                const totalPrice = cart.reduce((sum, item) => sum + item.discountPrice * item.quantity, 0);

                // Загружаем и открываем модальное окно оплаты
                const paymentHtml = await fetchTemplate('payment_modal.html');
                openModal(paymentHtml + '<button class="modal-close">×</button>');

                // Обновляем сумму в модальном окне
                document.querySelector('.payment-total-amount').textContent = `Оплатить ${totalPrice} ₽`;
                document.querySelector('.payment-final-button').textContent = `ОПЛАТИТЬ ${totalPrice} ₽ >`;

                // Навешиваем обработчик на финальную кнопку
                document.querySelector('.payment-final-button').addEventListener('click', async () => {
                    // Здесь можно добавить сбор данных карты, если нужно
                    console.log("Finalizing order with data:", checkoutData);
                    
                    const result = await api('place_order', { orderDetails: checkoutData });

                    if (result.success) {
                        closeModal();
                        // Показываем красивый экран успеха
                        try {
                            const successHtml = await fetchTemplate('order_success.html');
                            
                            const checkoutContent = document.querySelector('.checkout-content');
                            checkoutContent.innerHTML = successHtml;

                            // Обновляем номер заказа
                            const orderIdElement = checkoutContent.querySelector('#order-success-message');
                            if (orderIdElement) {
                                orderIdElement.textContent = `Номер вашего заказа: #${result.orderId}`;
                            }
                            
                            // Прячем сайдбар и расширяем основной контент
                            const sidebar = document.querySelector('.order-summary-sidebar');
                            if(sidebar) sidebar.style.display = 'none';
                            checkoutContent.style.gridTemplateColumns = '1fr';

                        } catch (error) {
                            console.error(error);
                            // Fallback to simple message
                            document.querySelector('.checkout-content').innerHTML = `
                                <div style="text-align: center; padding: 50px;">
                                    <h2>Заказ №${result.orderId} успешно оформлен!</h2>
                                    <p>Спасибо за покупку!</p>
                                    <a href="/" class="button">Вернуться на главную</a>
                                </div>`;
                        }
                    } else {
                        alert('Произошла ошибка при оформлении заказа. Попробуйте снова.');
                    }
                });

            } catch (error) {
                console.error("Payment modal error:", error);
                openModal('<p>Ошибка при открытии формы оплаты.</p><button class="modal-close">×</button>');
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