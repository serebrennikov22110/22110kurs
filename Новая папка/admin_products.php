<?php
session_start();

// Проверка, является ли пользователь администратором
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Подключение к базе данных
$servername = "web.edu";
$username = "22110";
$password = "apvbuq";
$dbname = "22110_kursach";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка добавления товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO products (name, description, image, price, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $name, $description, $image, $price, $category);
    $stmt->execute();
    $stmt->close();
}

// Обработка удаления товара
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
}

// Обработка изменения товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, image = ?, price = ?, category = ? WHERE id = ?");
    $stmt->bind_param("sssdsi", $name, $description, $image, $price, $category, $id);
    $stmt->execute();
    $stmt->close();
}

// Получение всех товаров
$products = [];
$result = $conn->query("SELECT * FROM products");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление товарами</title>
    <style>
        .admin-panel {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .admin-panel h1 {
            text-align: center;
        }
        .admin-panel form {
            margin-bottom: 20px;
        }
        .admin-panel input, .admin-panel select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        .admin-panel table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-panel table, .admin-panel th, .admin-panel td {
            border: 1px solid #ddd;
        }
        .admin-panel th, .admin-panel td {
            padding: 10px;
            text-align: left;
        }
        .admin-panel th {
            background-color: #f4f4f4;
        }
        .admin-panel .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <h1>Управление товарами</h1>

        <!-- Форма для добавления товара -->
        <h2>Добавить товар</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Название" required>
            <input type="text" name="description" placeholder="Описание" required>
            <input type="text" name="image" placeholder="Ссылка на изображение" required>
            <input type="number" name="price" step="0.01" placeholder="Цена" required>
            <input type="text" name="category" placeholder="Категория" required>
            <button type="submit" name="add_product">Добавить</button>
        </form>

        <!-- Список товаров -->
        <h2>Список товаров</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Изображение</th>
                <th>Цена</th>
                <th>Категория</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['name']; ?></td>
                <td><?php echo $product['description']; ?></td>
                <td><img src="<?php echo $product['image']; ?>" width="100"></td>
                <td><?php echo $product['price']; ?> ₽</td>
                <td><?php echo $product['category']; ?></td>
                <td class="actions">
                    <a href="?delete=<?php echo $product['id']; ?>">Удалить</a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
                        <input type="text" name="description" value="<?php echo $product['description']; ?>" required>
                        <input type="text" name="image" value="<?php echo $product['image']; ?>" required>
                        <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                        <input type="text" name="category" value="<?php echo $product['category']; ?>" required>
                        <button type="submit" name="edit_product">Изменить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
