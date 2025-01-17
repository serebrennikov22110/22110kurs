<?php
session_start();

// Инициализация избранного
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Обработка удаления товара из избранного
if (isset($_POST['remove_from_favorites'])) {
    $product_id = $_POST['product_id'];

    // Удаляем товар из избранного
    if (in_array($product_id, $_SESSION['favorites'])) {
        $key = array_search($product_id, $_SESSION['favorites']);
        unset($_SESSION['favorites'][$key]);
    }
}

// Обработка добавления товара в корзину
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Если товар уже есть в корзине, увеличить количество
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Подключение к базе данных
$conn = new mysqli('web.edu', '22110', 'apvbuq', '22110_kursach');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных о товарах в избранном из базы данных
$favorite_products = [];
if (!empty($_SESSION['favorites'])) {
    // Формируем список ID товаров в избранном
    $product_ids = implode(",", $_SESSION['favorites']);

    // Запрос к базе данных для получения информации о товарах
    $sql = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $favorite_products[] = $row;
        }
    } else {
        echo "Ошибка при загрузке товаров: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Избранное - Интернет-магазин спортивного питания</title>
    <style>
        .products {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .product {
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
        }
        .product img {
            max-width: 100px;
            height: auto;
            border-radius: 10px;
        }
        .product h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .product p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .product .price {
            font-size: 16px;
            color: #008000; /* Зеленый цвет для цены */
            font-weight: bold;
        }
        .product form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .product form input[type="number"] {
            width: 60px;
        }
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
                    <li><a href="admin_login.php">Войти как администратор</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                    <li><a href="login.php">Войти</a></li>
                    <li><a href="register.php">Регистрация</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Избранное</h2>
            <p>Здесь находятся ваши избранные товары.</p>

            <div class="products">
                <?php if (!empty($favorite_products)): ?>
                    <?php foreach ($favorite_products as $product): ?>
                        <div class="product">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                            <div>
                                <h3><?php echo $product['name']; ?></h3>
                                <p><?php echo $product['description']; ?></p>
                                <p class="price">Цена: <?php echo $product['price']; ?> ₽</p>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="remove_from_favorites">Удалить из избранных</button>
                                <div>
                                    <span>Количество: </span>
                                    <input type="number" name="quantity" value="1" min="1">
                                    <button type="submit" name="add_to_cart">Добавить в корзину</button>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>В избранном пока нет товаров.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 СпортПит</p>
        </div>
    </footer>
</body>
</html>
