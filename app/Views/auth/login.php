<?php require __DIR__ . '/../../../include/header.php'; ?>
<section class="auth-form">
    <h1 class="page__title">Вход в систему</h1>
    <?php foreach ($errors as $error): ?>
        <p class="alert alert--error"><?= e($error) ?></p>
    <?php endforeach; ?>
    <form class="auth-form__form"
          action="index.php?action=login" method="post">
        <input type="hidden" name="csrf_token"
               value="<?= e(csrf_token()) ?>">
        <label class="auth-form__field">
            <span class="auth-form__label">E-mail</span>
            <input class="auth-form__input" type="email" name="email"
                   autocomplete="username"
                   value="<?= e($email) ?>" required>
        </label>
        <label class="auth-form__field">
            <span class="auth-form__label">Пароль</span>
            <input class="auth-form__input" type="password" name="password"
                   autocomplete="current-password" required>
        </label>
        <button class="button button--primary" type="submit">Войти</button>
    </form>
</section>
<?php require __DIR__ . '/../../../include/footer.php'; ?>
