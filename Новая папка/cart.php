<?php
session_start();

// Подключение к базе данных
$conn = new mysqli('web.edu', '22110', 'apvbuq', '22110_kursach');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Инициализация корзины
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Обработка удаления товара
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

// Обработка изменения количества товара
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Получение данных о товарах в корзине из базы данных
$cart_products = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    // Формируем список ID товаров в корзине
    $product_ids = array_keys($_SESSION['cart']);
    $product_ids_str = implode(",", $product_ids);

    // Запрос к базе данных для получения информации о товарах
    $sql = "SELECT * FROM products WHERE id IN ($product_ids_str)";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
            $quantity = $_SESSION['cart'][$product_id];
            $row['quantity'] = $quantity;
            $row['total_price'] = $row['price'] * $quantity;
            $cart_products[] = $row;
            $total_price += $row['total_price'];
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
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина - Интернет-магазин спортивного питания</title>
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
        .total-price {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .buy-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #008000;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .buy-button:hover {
            background-color: #006400;
        }
        .success-message {
            display: none;
            margin-top: 20px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            text-align: center;
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
            <h2>Корзина</h2>
            <p>Здесь находятся ваши товары, добавленные в корзину.</p>
            
            <div class="products">
                <?php if (!empty($cart_products)): ?>
                    <?php foreach ($cart_products as $product): ?>
                        <div class="product">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                            <div>
                                <h3><?php echo $product['name']; ?></h3>
                                <p><?php echo $product['description']; ?></p>
                                <p class="price">Цена: <?php echo $product['price']; ?> ₽</p>
                                <p>Количество: <?php echo $product['quantity']; ?></p>
                                <p>Итого: <?php echo $product['total_price']; ?> ₽</p>
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" min="1">
                                    <button type="submit" name="update_cart">Обновить</button>
                                    <button type="submit" name="remove_from_cart">Удалить из корзины</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Ваша корзина пуста.</p>
                <?php endif; ?>
            </div>

            <div class="total-price">
                Общая стоимость: <?php echo $total_price; ?> ₽
            </div>

            <button class="buy-button" id="buy-button">Купить</button>

            <div class="success-message" id="success-message">
                Вы успешно оплатили товар!
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 СпортПит</p>
        </div>
    </footer>

    <script>
        document.getElementById('buy-button').addEventListener('click', function() {
            document.getElementById('success-message').style.display = 'block';
        });
    </script>
</body>
</html>
