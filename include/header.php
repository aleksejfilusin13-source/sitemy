<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student MVC</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <nav class="site-nav" aria-label="Основная навигация">
            <a class="site-nav__link" href="index.php">Список</a>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a class="site-nav__link" href="index.php?action=profile">
                    Личный кабинет
                </a>
                <span class="site-nav__user">
                    <?= e($_SESSION['user_name'] ?? '') ?>
                </span>
                <form class="site-nav__logout"
                      action="index.php?action=logout" method="post">
                    <input type="hidden" name="csrf_token"
                           value="<?= e(csrf_token()) ?>">
                    <button class="button button--secondary" type="submit">
                        Выйти
                    </button>
                </form>
            <?php else: ?>
                <a class="site-nav__link" href="index.php?action=login">Войти</a>
                <a class="site-nav__link" href="index.php?action=register">
                    Регистрация
                </a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
