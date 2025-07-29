<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа - Юридическое лицо</title>
    <link rel="stylesheet" href="/assets/css/basket_styles.css">
    <link rel="stylesheet" href="/assets/css/basket_admin.css">
</head>
<body>
    <header class="header">
    <div class="header-top">
        <div class="logo">
            <img src="assets/img/Лого сайта.png" alt="Логотип">
        </div>
        <button class="ratings-btn">
            <img src="assets/img/Рейтинги.png" alt="Рейтинги">
            Рейтинги
        </button>
        <div class="search-container">
            <input type="text" placeholder="Что будем искать?" class="search-input">
            <button class="search-btn">
                <img src="assets/img/Искать.png" alt="Поиск">
            </button>
        </div>
        <div class="user-actions">
            <a href="#" class="user-item">
                <img src="assets/img/Вход.png" alt="Вход">
                <span>Вход</span>
            </a>
            <a href="#" class="user-item">
                <img src="assets/img/Начатые.png" alt="Начатые">
                <span>Начатые</span>
            </a>
            <a href="#" class="user-item">
                <img src="assets/img/Избранное.png" alt="Избранные">
                <span>Избранные</span>
            </a>
            <a href="#" class="user-item active">
                <img src="assets/img/Корзина.png" alt="Корзина">
                <span>Корзина</span>
            </a>
        </div>
    </div>
    
    <div class="nav-container">
        <nav class="nav">
            <a href="#" class="nav-item">
                <img src="assets/img/Читать онлайн.png" alt="">
                Читать онлайн
            </a>
            <a href="#" class="nav-item">
                <img src="assets/img/Новые главы.png" alt="">
                Новые главы
            </a>
            <a href="#" class="nav-item">
                <img src="assets/img/Новые отзывы.png" alt="">
                Новые отзывы
            </a>
            <a href="#" class="nav-item">
                <img src="assets/img/Книги в FB2.png" alt="">
                Книги в FB2
            </a>
            <a href="#" class="nav-item">
                <img src="assets/img/Бесплатные книги.png" alt="">
                Бесплатные книги
            </a>
            <a href="#" class="nav-item">
                <img src="assets/img/Рецензии на книги.png" alt="">
                Рецензии на книги
            </a>
        </nav>
    </div>
</header>

    <main class="main">
        <div class="container">
            <div class="breadcrumb">
                <a href="#">Корзина</a> > <span>Оформление заказа</span>
            </div>

            <h1 class="page-title">ОФОРМЛЕНИЕ ЗАКАЗА</h1>

            <div class="checkout-content">
                <div class="checkout-form">
                    <div class="person-type-tabs">
                        <button class="tab">Физическое лицо</button>
                        <button class="tab active">Юридическое лицо</button>
                    </div>

                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>Способ получения</h3>
                            <div class="location-info">📍 Россия, Москва</div>
                            
                            <div class="delivery-options">
                                <button class="delivery-option active">Самовывоз</button>
                            </div>

                            <div class="delivery-details">
                                <div class="delivery-input-group">
                                    <input type="text" placeholder="В отделе, 17 мин, Бесплатно" class="delivery-input">
                                </div>
                            </div>

                            <button class="map-btn">ВЫБРАТЬ МАГАЗИН НА КАРТЕ</button>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>Способ оплаты</h3>

                            <div class="payment-options">
                                <div class="payment-option active">
                                    <h4>По счету</h4>
                                    <p>Оплатите счет на расчетный счет после оформления заказа</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>Юридическое лицо</h3>
                            <button class="add-legal-btn">УКАЗАТЬ ЮРИДИЧЕСКОЕ ЛИЦО</button>

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

                <div class="order-summary-sidebar">
                    <div class="summary-info">
                        <div class="summary-row">
                            <span>2 товара • 0.50 кг</span>
                            <span>794 ₽</span>
                        </div>
                        <div class="summary-row">
                            <span>Доставка</span>
                            <span>Не выбрана</span>
                        </div>
                        <div class="summary-row discount">
                            <span>Скидка</span>
                            <span>-148 ₽</span>
                        </div>
                        <div class="summary-row total">
                            <span>Итого</span>
                            <span>646 ₽</span>
                        </div>
                    </div>
                    <button class="pay-btn">ОПЛАТИТЬ ЗАКАЗ</button>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-left">
                    <div class="qr-section">
                        <img src="/placeholder.svg?height=100&width=100" alt="QR Code" class="qr-code">
                        <div class="app-info">
                            <div class="app-badge">📱 Download for Android</div>
                            <p>Сайт может содержать материалы 16+, ставка налога 6 ₽. На информационном ресурсе применяются рекомендательные технологии</p>
                            <div class="age-rating">16+</div>
                        </div>
                    </div>
                </div>
                <div class="footer-center">
                    <div class="footer-column">
                        <h4>Авторам</h4>
                        <ul>
                            <li><a href="#">Стать автором</a></li>
                            <li><a href="#">Инструкция для авторов</a></li>
                            <li><a href="#">Способы оплаты</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Документы</h4>
                        <ul>
                            <li><a href="#">Авторский договор</a></li>
                            <li><a href="#">Правила</a></li>
                            <li><a href="#">Пользовательское соглашение</a></li>
                            <li><a href="#">Правообладателям</a></li>
                            <li><a href="#">Рекомендательные технологии</a></li>
                            <li><a href="#">Политика обработки персональных данных</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-right">
                    <h4>Как с нами связаться</h4>
                    <div class="social-links">
                        <a href="#" class="social-link">VK</a>
                        <a href="#" class="social-link">TG</a>
                        <a href="#" class="social-link">FB</a>
                        <a href="#" class="social-link">IG</a>
                    </div>
                    <div class="contact-info">
                        <p>Консультант</p>
                        <p>Техническая поддержка</p>
                        <p>info@site.ru</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="/assets/js/basket_script.js"></script>
    <script src="/assets/js/basket_script.js"></script>
</body>
</html>