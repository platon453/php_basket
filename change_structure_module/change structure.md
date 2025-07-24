**Структура:**
- **api** - обработчики, которые мы вызываем по ajax (стараемся потихоньку переходить на вынос функционала, тк раскидан по всему проекту)
- **app** - обработчики, которые просто из формы вызываются, а также просто из кода
- **assets** - по названию понятно, что js, css, иконки и прочее там находится
- **config** - конфигурация проекта - НЕ ТРОГАТЬ
- **cron** - тут обработчики и скрипты, которые мы по расписанию запускаем (частично)
- **docs** - просто папка с документами для писателей, она у них в интерфейсе писателя есть при регистрации как автор
- **function** - основной сбор функциональности сайта, но проблема в том, что там у нас и верстка, и обработка, и прочее, все в кучу налеплено, да и не все вынесли еще, работаем над этим
- **new** - попытка сделать новую версию сайта, на данный момент не используется, юзаем только админку новую там, тк позволяет таблицы редактировать напрямую почти
- **routes** 
	- **list** - список возможных маршрутов (первая линия)
	- **actions** - назначение обработчиков по этим маршрутам
	- **titles** - тайтлы и дескрипшены, а также прочая сео инфа
- **templates** - тут храним верстку, но, как всегда, она там не везде чистая, есть еще фрагменты с бэкендом
---
### 🧩Инструкция: создание модулей  
  
#### 📌**Определение компонентов модуля****  
Для нового модуля (например, "UserReviews"):  
- Страницы: `UserReviewsList.php`, `UserReviewsView.php`  
- API: `Us1erReviewsAdd.php`, `UserReportsDelete.php`  
- Крон: `user_reviews_daily_report.php`  
- Шаблоны: `/user/user_reviews_list.php`, `/user/user_review_view.php`
#### 📂 2. **Создание файлов (стиль AddToList.php)**
```diff
function/
+ UserReviewsList.php     # Основной обработчик
+ UserReviewsAdd.php      # Логика добавления
+ UserReportsDelete.php   # Удаление отзывов

api/
+ UserReviewsSubmit.php   # API обработчик

cron/
+ user_reviews_daily_report.php   # Ежедневный отчёт

templates/
+ user_reviews_list.php
+ user_review_view.php

assets/
+ js/
    submit.js
    reports.js
+ css/
    users.css
```
#### 🛠 3. **Интеграция в роутинг**
- **A. routes/list.php**  
Добавить новый маршрут (на нижнюю строчку):
```php
$menuitems = [
    ...
    'previous_route',
    'user_reviews', // Новый модуль
    ...
];
```
- **B. routes/actions.php**  
Добавить обработку для нового ID:
```php
case *number*: // User Reviews
    if ($gitem === 'add') {
        require_once ROOT . '/function/UserReviewsAdd.php';
        AddUserReview(); // Функция из файла
    } elseif ($gitem === 'delete') {
        require_once ROOT . '/function/UserReportsDelete.php';
        DeleteUserReview($id); 
    } else {
        require_once ROOT . '/function/UserReviewsList.php';
        ShowUserReviewsList();
    }
    break;
```
- **C. routes/titles.php**
Добавить SEO-информацию:
```php
case *number*:
    if ($gitem === 'add') {
        $title = "Добавить отзыв - $storename";
        $pagedescr = "Поделитесь вашим мнением о книге";
    } else {
        $title = "Отзывы читателей - $storename";
        $pagedescr = "Реальные отзывы о книгах от наших читателей";
    }
    break;
```
####  4. **Шаблон файла в function/**  
- **UserReviewsList.php:**
```php
<?php
function ShowUserReviewsList() {
    // 1. Подключение зависимостей
    include __DIR__ . '/../../../config/db.php';
    
    // 2. Бизнес-логика
    $reviews = GetReviewsFromDB(); // Ваша функция
    
    // 3. Рендеринг через шаблон
    include __DIR__ .  '/templates/user_reviews_list.php';
    
    // ИЛИ возврат данных для унифицированного рендеринга
    return [
        'template' => 'user_reviews_list',
        'data' => ['reviews' => $reviews]
    ];
}

// Вспомогательные функции
function GetReviewsFromDB() {
    // SQL-запросы здесь
}
```
#### 🔄 5. **Взаимодействие между компонентами**  
- **API вызов (assets/js/submit.js):**
```js
fetch('/api/UserReviewsSubmit.php', {
    method: 'POST',
    body: JSON.stringify({ book_id: 123, text: "Отличная книга!" })
})
```
- **Обработка API (api/UserReviewsSubmit.php):**
```php
<?php
require_once __DIR__ . '/../function/UserReviewsAdd.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

try {
    $result = AddUserReview($data);
    echo json_encode(['success' => true, 'id' => $result]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
```
#### ⚠️ 6. **Критические правила**  
1. **Стиль именования:**  
   - Файлы в `function/`: `CamelCase` с префиксом модуля (`UserReviewsXxx.php`)  
   - Шаблоны: `snake_case` (`user_reviews_xxx.php`)  
   - JS/CSS: в `assets/js/` и `assets/css/`
2. **Разделение ответственности**
  - SQL → в отдельных функциях в том же файле или в ``/config/db.php (не редактировать `db.php``)`  
   - HTML → только в `templates/*folder*`  
   - Бизнес-логика → в файлах `function/UserReviews*.php`
#### 🔄 7. **Пример: модуль BookRecommendations**
- **Файлы:** 
```
function/BookRecommendationsList.php
function/BookRecommendationsAdd.php
api/BookRecommendationsSubmit.php
templates/book_recommendations_list.php
assets/js/recommend.js
```
  - **Регистрация в routes/actions.php:**
```php
case 151: // Book Recommendations
    if ($gitem === 'add') {
        require_once ROOT . '/function/BookRecommendationsAdd.php';
        AddRecommendation();
    } else {
        require_once ROOT . '/function/BookRecommendationsList.php';
        ShowRecommendations();
    }
    break;
```

---
# СООБЩЕНИЕ ОТ МЕНТОРА ПО ПРАВКАМ НУЖНО ПОНЯТЬ ЧТО МНЕ МЕНЯТЬ В МОЕЙ СТРУКТУРЕ
- **Добрый день, можете немного переделать под нашу структуру чтобы было легче внедрять**
- `api`
можно посмотреть на скриншоте api.png
- `app`
можно посмотреть на скриншоте app.png
- в `config` ничего не добавляем, всё уже есть
- `function`
можно посмотреть на скришоте function.pnp
- `routes`, файлы я присылала, не надо свои создавать, надо отредактировать существующие
можно посмотреть на скриншоте routes.pnp
- `templates`
можно посмотреть на скриншоте templates.png 