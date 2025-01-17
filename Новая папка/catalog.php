<?php
session_start();

// Подключение к базе данных
$conn = new mysqli('web.edu', '22110', 'apvbuq', '22110_kursach');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка добавления товара в корзину
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Инициализация корзины, если она еще не существует
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Если товар уже есть в корзине, увеличиваем количество
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Иначе добавляем товар в корзину
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Редирект, чтобы избежать повторной отправки формы
    header("Location: catalog.php");
    exit();
}

// Обработка добавления товара в избранное
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_favorites'])) {
    $product_id = $_POST['product_id'];

    // Инициализация избранного, если оно еще не существует
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    // Добавляем товар в избранное, если его там еще нет
    if (!in_array($product_id, $_SESSION['favorites'])) {
        $_SESSION['favorites'][] = $product_id;
    }

    // Редирект, чтобы избежать повторной отправки формы
    header("Location: catalog.php");
    exit();
}

// Получение всех товаров из базы данных
$products = [];
$result = $conn->query("SELECT * FROM products");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "Ошибка при загрузке товаров: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Каталог товаров</title>
    <style>
        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between; /* Равномерное распределение товаров */
        }
        .product {
            width: calc(50% - 10px); /* Две колонки */
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            box-sizing: border-box; /* Учитываем padding в ширине */
        }
        .product img {
            max-width: 100%;
            height: 200px; /* Фиксированная высота для картинок */
            object-fit: cover; /* Масштабирование картинок */
            border-radius: 10px;
        }
        .product h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }
        .product p {
            margin: 10px 0;
            font-size: 14px;
            color: #666;
        }
        .product .price {
            font-size: 16px;
            color: #008000; /* Зеленый цвет для цены */
            font-weight: bold;
        }
        .category {
            width: 100%;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
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
    <h1>Каталог товаров</h1>
    <div class="products">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="price">Цена: <?php echo htmlspecialchars($product['price']); ?> ₽</p>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" name="add_to_cart">Добавить в корзину</button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_favorites">Добавить в избранное</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Товары отсутствуют в каталоге.</p>
        <?php endif; ?>
    </div>
</body>
</html>
