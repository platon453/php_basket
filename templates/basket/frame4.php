<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
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
                            
                            

                            <div class="delivery-details">
                                <div class="delivery-input-group">
                                    <label>В магазине</label>
                                    <input type="text" placeholder="В отделе, 17 мин, Бесплатно">
                                </div>
                                <div class="delivery-input-group">
                                    <label>В пункте выдачи</label>
                                    <input type="text" placeholder="В отделе, 17 мин, 250 ₽">
                                </div>
                            </div>

                            <button class="map-btn">ВЫБРАТЬ МАГАЗИН НА КАРТЕ</button>
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
                                <div class="payment-option">
                                    <h4>При получении</h4>
                                    <p>Оплатите заказ при получении наличными или банковской картой</p>
                                </div>
                                <div class="payment-option">
                                    <h4>Через СБП</h4>
                                    <p>Система быстрых платежей банков</p>
                                </div>
                                <div class="payment-option active">
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