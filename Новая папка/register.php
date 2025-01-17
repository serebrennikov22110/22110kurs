<?php
$dbHost = 'web.edu';
$dbUser = '22110'; // ваше имя пользователя
$dbPass = 'apvbuq'; // ваш пароль
$dbName = '22110_kursach';

// Подключение к базе данных
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $createdAt = date('Y-m-d H:i:s');

    // Вставка данных в базу
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $email, $createdAt);
    
    if ($stmt->execute()) {
        echo "Регистрация успешна!";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Регистрация</title>
</head>
<body>
    <header>
        <h1>Регистрация</h1>
    </header>
    <main>
        <form method="POST" action="">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </main>
</body>
</html>
