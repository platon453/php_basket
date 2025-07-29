<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа - Доставка</title>
    <link rel="stylesheet" href="/assets/css/basket_styles.css">
    <link rel="stylesheet" href="/assets/css/basket_admin.css">
</head>
<body>
    <main class="main">
        <div class="container">
            <div class="checkout-content">
                <div class="checkout-form">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>Способ получения</h3>
                            <div class="location-info">📍 Россия, Москва</div>
                            <div class="delivery-form">
                                <h4>Куда доставить</h4>
                                <p class="delivery-note">Улица и номер дома в 📍 Россия, Москва</p>
                                <div class="address-inputs">
                                    <div class="input-row">
                                        <input type="text" placeholder="" class="address-input full-width">
                                        <input type="text" placeholder="Индекс" class="address-input">
                                    </div>
                                    <div class="input-row">
                                        <input type="text" placeholder="Квартира или офис" class="address-input">
                                        <input type="text" placeholder="Комментарий для курьера" class="address-input">
                                    </div>
                                </div>
                                <div class="delivery-cost">
                                    <h4>Стоимость доставки</h4>
                                    <p>Рассчитывается индивидуально</p>
                                    <p class="delivery-time">Срок доставки в почтовых отправлениях 15 дней</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>Способ оплаты</h3>
                            <label class="checkbox-label">
                                <input type="checkbox" checked>
                                <span>Использовать подарочный сертификат</span>
                            </label>
                            <div class="payment-options">
                                <div class="payment-option active">
                                    <h4>Через СБП</h4>
                                    <p>Оплатите через систему быстрых платежей банков</p>
                                </div>
                                <div class="payment-option">
                                    <h4>Картой на сайте</h4>
                                    <p>Оплатите на сайте онлайн</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>Получатель</h3>
                            <div class="form-group">
                                <label>ФИО</label>
                                <input type="text" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Электронная почта</label>
                                <input type="email" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Телефон</label>
                                <input type="tel" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="/assets/js/basket_script.js"></script>
    <script src="/assets/js/basket_script.js"></script>
</body>
</html>