<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Интернет-магазин спортивного питания</title>
    <style>
        .search-bar {
            display: flex;
            justify-content: center; /* Выравнивание по центру */
            gap: 10px;
            margin: 20px 0;
        }
        .results-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .results-container div {
            margin: 10px 0;
            text-align: center;
        }
        .filter-bar {
            display: flex;
            justify-content: space-between; /* Выравнивание по краям */
            align-items: center; /* Выравнивание по вертикали */
            gap: 10px;
            margin: 20px 0;
        }
        .filter-bar select {
            padding: 5px;
            font-size: 16px;
        }
        .product-icons {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Четыре колонки */
            gap: 10px; /* Уменьшенное расстояние между товарами */
            justify-items: center; /* Выравнивание по центру */
        }
        .product-icon {
            text-align: center;
            width: 100%; /* Ширина товара */
            max-width: 150px; /* Уменьшенная максимальная ширина товара */
            border: 1px solid #ddd; /* Граница вокруг товара */
            padding: 10px;
            border-radius: 10px; /* Закругленные углы */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Уменьшенная тень */
            transition: transform 0.2s ease-in-out; /* Анимация при наведении */
        }
        .product-icon:hover {
            transform: scale(1.05); /* Увеличение при наведении */
        }
        .product-icon img {
            width: 100%;
            height: auto;
            border-radius: 10px 10px 0 0; /* Закругленные углы для изображения */
        }
        .product-icon h4 {
            margin: 10px 0;
            font-size: 14px; /* Уменьшенный размер текста */
            color: #333;
        }
        .product-icon .price {
            font-size: 12px; /* Уменьшенный размер текста для цены */
            color: #008000; /* Зеленый цвет для цены */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>СпортПит</h1>
            <nav>
            <nav>
    <ul>
        <li><a href="index.php">Главная</a></li>
        <li><a href="catalog.php">Каталог</a></li>
        <li><a href="favorites.php">Избранное</a></li>
        <li><a href="cart.php">Корзина</a></li>
        <li><a href="about.php">О нас</a></li>
        <li><a href="admin_login.php">Вход администратора</a></li>
        <li><a href="contacts.php">Контакты</a></li> 
        <li><a href="login.php">Войти</a></li>
        <li><a href="register.php">Регистрация</a></li>
    </ul>
</nav>
            </nav>
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Найти товар..." />
                <button id="search-button">Поиск</button>
            </div>
            <div class="filter-bar">
                <select id="sort-filter">
                    <option value="all">Без сортировки</option>
                    <option value="price-asc">По возрастанию цены</option>
                    <option value="price-desc">По убыванию цены</option>
                    <option value="name-asc">По алфавиту (А-Я)</option>
                    <option value="name-desc">По алфавиту (Я-А)</option>
                </select>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Добро пожаловать в наш интернет-магазин!</h2>
            <p>Лучшее спортивное питание для вашего успеха.</p>

            <h3>Популярные товары</h3>
            <div id="product-icons" class="product-icons">
                <!-- Товары будут добавляться сюда через JavaScript -->
            </div>

            <div id="results" class="results-container"></div>
        </div>
    </main>
    <script>
        const products = [
            { name: 'Протеин', price: 499, imageUrl: 'https://avatars.mds.yandex.net/i?id=f1734a904b95070edde7ef29f32f6a35_l-12992035-images-thumbs&n=13', category: 'Протеин' },
            { name: 'Гейнер', price: 599, imageUrl: 'https://avatars.mds.yandex.net/i?id=837316e0deeaefad47e4c05ff7ee59368b99401e-8316014-images-thumbs&n=13', category: 'Гейнер' },
            { name: 'Креатин', price: 699, imageUrl: 'https://avatars.mds.yandex.net/i?id=0ffd8523ae0e96baee82b94356dd125cff71702e-5173519-images-thumbs&n=13', category: 'Креатин' },
            { name: 'Энергетические напитки', price: 199, imageUrl: 'https://svezhiy-nch.ru/a/svezhiy/files/userfiles/images/catalog/627e9ff99f9d11ec96be2cf05d42dc5f_2391d329611311ed97302cf05d42dc5f.jpg', category: 'Энергетические напитки' },
        ];

        function displayProducts(filteredProducts) {
            const productIconsContainer = document.getElementById('product-icons');
            productIconsContainer.innerHTML = '';

            filteredProducts.forEach(product => {
                const productElement = document.createElement('a');
                productElement.href = `catalog.php?category=${product.category}`; // Ссылка на категорию
                productElement.className = 'product-icon';
                productElement.innerHTML = `
                    <img src="${product.imageUrl}" alt="${product.name}" />
                    <h4>${product.name}</h4>
                    <div class="price">${product.price} ₽</div>
                `;
                productIconsContainer.appendChild(productElement);
            });
        }

        function filterProducts() {
            let filteredProducts = [...products];

            // Сортировка по цене и алфавиту
            const selectedSortFilter = document.getElementById('sort-filter').value;
            if (selectedSortFilter === 'price-asc') {
                filteredProducts.sort((a, b) => a.price - b.price);
            } else if (selectedSortFilter === 'price-desc') {
                filteredProducts.sort((a, b) => b.price - a.price);
            } else if (selectedSortFilter === 'name-asc') {
                filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
            } else if (selectedSortFilter === 'name-desc') {
                filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
            }

            displayProducts(filteredProducts);
        }

        function searchProducts() {
            const query = document.getElementById('search-input').value.toLowerCase();
            const results = products.filter(product => product.name.toLowerCase().includes(query));
            displayProducts(results);
        }

        document.getElementById('search-button').addEventListener('click', searchProducts);
        document.getElementById('sort-filter').addEventListener('change', filterProducts);

        // Инициализация отображения всех товаров
        displayProducts(products);
    </script>
</body>
</html>
<footer>
    <div class="container">
        <p>&copy; 2024 СпорПит</p>
    </div>
</footer>