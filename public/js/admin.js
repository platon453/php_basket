document.addEventListener('DOMContentLoaded', () => {
    const loginSection = document.getElementById('login-section');
    const ordersSection = document.getElementById('orders-section');
    const loginForm = document.getElementById('login-form');
    const adminPasswordInput = document.getElementById('admin-password');
    const ordersTableBody = document.querySelector('#orders-table tbody');

    // Modal elements
    const modalOverlay = document.getElementById('details-modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content-details');
    const modalCloseBtn = document.getElementById('details-modal-close');

    let currentPassword = '';
    let allOrders = [];

    // --- ОБЩАЯ ФУНКЦИЯ ДЛЯ API ---
    async function api(action, data = {}) {
        try {
            const response = await fetch('/index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action, ...data })
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error("API Error:", error);
            return { success: false, error: error.message };
        }
    }

    // --- ЛОГИКА ВХОДА ---
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const password = adminPasswordInput.value;

        if (!password) {
            alert('Пожалуйста, введите пароль.');
            return;
        }

        const response = await api('get_all_orders', { password });

        if (response.success) {
            currentPassword = password;
            allOrders = response.orders;
            loginSection.style.display = 'none';
            ordersSection.style.display = 'block';
            renderOrders(allOrders);
        } else {
            alert('Неверный пароль или произошла ошибка.');
        }
    });

    // --- ОТРИСОВКА ТАБЛИЦЫ ЗАКАЗОВ ---
    const renderOrders = (orders) => {
        ordersTableBody.innerHTML = '';

        if (!orders || orders.length === 0) {
            ordersTableBody.innerHTML = '<tr><td colspan="6">Заказов пока нет.</td></tr>';
            return;
        }

        orders.forEach(order => {
            const row = `
                <tr data-order-id="${order.id}">
                    <td>${order.id}</td>
                    <td>${order.created_at}</td>
                    <td>${order.customer_type === 'legal' ? 'Юр. лицо' : 'Физ. лицо'}</td>
                    <td>${order.delivery_type}</td>
                    <td>${order.total_price} ₽</td>
                    <td><button class="details-btn">Показать</button></td>
                </tr>
            `;
            ordersTableBody.insertAdjacentHTML('beforeend', row);
        });
    };

    // --- ЛОГИКА МОДАЛЬНОГО ОКНА ДЕТАЛЕЙ ---
    const openModal = () => modalOverlay.style.display = 'flex';
    const closeModal = () => modalOverlay.style.display = 'none';

    modalCloseBtn.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    ordersTableBody.addEventListener('click', async (e) => {
        if (e.target.classList.contains('details-btn')) {
            const row = e.target.closest('tr');
            const orderId = row.dataset.orderId;

            const orderData = allOrders.find(o => o.id == orderId);
            if (!orderData) return;

            const response = await api('get_order_details', { password: currentPassword, orderId });

            if (response.success) {
                renderModalContent(orderData, response.items);
                openModal();
            } else {
                alert('Не удалось загрузить детали заказа.');
            }
        }
    });

    const renderModalContent = (order, items) => {
        modalTitle.textContent = `Детали Заказа #${order.id}`;

        let itemsHtml = '<p>Нет товаров в заказе.</p>';
        if (items && items.length > 0) {
            itemsHtml = `
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 8px; border-bottom: 1px solid #ddd;">Товар</th>
                            <th style="padding: 8px; border-bottom: 1px solid #ddd;">Кол-во</th>
                            <th style="padding: 8px; border-bottom: 1px solid #ddd;">Цена за шт.</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${items.map(item => `
                            <tr>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;">${item.product_title} (ID: ${item.product_id})</td>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;">${item.quantity}</td>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;">${item.price_per_item} ₽</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        let customerDetailsHtml = '';
        try {
            const details = JSON.parse(order.customer_details);
            customerDetailsHtml = '<h4>Детали доставки:</h4><pre>' + JSON.stringify(details, null, 2) + '</pre>';
        } catch (e) {}

        let legalDetailsHtml = '';
        if (order.customer_type === 'legal') {
            try {
                const details = JSON.parse(order.legal_details);
                legalDetailsHtml = '<h4>Данные юр. лица:</h4><pre>' + JSON.stringify(details, null, 2) + '</pre>';
            } catch (e) {}
        }

        modalContent.innerHTML = `
            ${customerDetailsHtml}
            ${legalDetailsHtml}
            <h4 style="margin-top: 20px;">Состав заказа:</h4>
            ${itemsHtml}
        `;
    };
});