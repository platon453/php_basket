<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление Заказа</title>
    <link rel="stylesheet" href="/assets/css/basket_styles.css">
    <link rel="stylesheet" href="/assets/css/basket_admin.css">
</head>
<body>
    <header class="header"> </header>

    <main class="main">
        <div class="container">
            <div class="breadcrumb"><a href="cart">Корзина</a> > <span>Оформление заказа</span></div>
            <h1 class="page-title">ОФОРМЛЕНИЕ ЗАКАЗА</h1>

            <div class="checkout-content">
                <form class="checkout-form">
                    <div class="person-type-tabs">
                        <button type="button" class="tab active" data-tab="natural">Физическое лицо</button>
                        <button type="button" class="tab" data-tab="legal">Юридическое лицо</button>
                    </div>

                    <div class="tab-content active" data-tab="physical">
                        <div class="step">
                            <div class="step-content">
                                <h3>Способ получения</h3>
                                <div class="delivery-options">
                                    <button type="button" class="delivery-option active" data-delivery="pickup">Самовывоз</button>
                                    <button type="button" class="delivery-option" data-delivery="courier">Курьером</button>
                                    <button type="button" class="delivery-option" data-delivery="postal">Почтой РФ</button>
                                </div>
                                <div class="delivery-form" data-delivery-form="pickup"></div>
                                <div class="delivery-form" data-delivery-form="courier" style="display: none;"></div>
                                <div class="delivery-form" data-delivery-form="postal" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-content">
                                <h3>Получатель</h3>
                                <div class="form-group">
                                    <label>ФИО</label>
                                    <input type="text" name="name" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label>Электронная почта</label>
                                    <input type="email" name="email" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label>Телефон</label>
                                    <input type="tel" name="phone" class="form-input">
                                </div>
                            </div>
                        </div>
                        </div>

                    <div class="tab-content" data-tab="legal" style="display:none;">
                         <button type="button" class="add-legal-btn">УКАЗАТЬ ЮРИДИЧЕСКОЕ ЛИЦО</button>
                    </div>
                </form>

                <div class="order-summary-sidebar">
                    <div class="order-summary">
                        </div>
                    <button class="pay-btn">ОПЛАТИТЬ ЗАКАЗ</button>
                </div>
            </div>
        </div>
    </main>

    <div class="modal-overlay" style="display: none;">
        <div class="modal"> <button class="modal-close">×</button></div>
    </div>
    <script src="/assets/js/basket_script.js"></script>
    <script src="/assets/js/basket_script.js"></script>
    
</body>
</html>