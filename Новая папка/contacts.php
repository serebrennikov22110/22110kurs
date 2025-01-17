<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Контакты - Интернет-магазин спортивного питания</title>
    <style>
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>СпортПит</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="catalog.php">Каталог</a></li>
                    <li><a href="favorites.php">Избранное</a></li>
                    <li><a href="cart.php">Корзина</a></li>
                    <li><a href="about.php">О нас</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                    <li><a href="login.php">Войти</a></li>
                    <li><a href="register.php">Регистрация</a><li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Контакты</h2>
            <div class="contact-info">
                <p><strong>Телефон:</strong> +7 (123) 456-78-90</p>
                <p><strong>Адрес:</strong> г. Москва, ул. Спортивная, д. 1</p>
                <p><strong>Email:</strong> info@sportpit.ru</p>
                <img src="https://proumnyjdom.ru/wp-content/uploads/2019/04/https-i-imgur-com-jrzfmx6-png-1024x613.png" alt="Геолакация на карте" />
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 СпорПит</p>
        </div>
    </footer>
</body>
</html>