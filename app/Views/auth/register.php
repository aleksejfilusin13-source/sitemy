<?php require __DIR__ . '/../../../include/header.php'; ?>
<section class="auth-form">
    <h1 class="page__title">Регистрация</h1>
    <?php foreach ($errors as $error): ?>
        <p class="alert alert--error"><?= e($error) ?></p>
    <?php endforeach; ?>
    <form class="auth-form__form"
          action="index.php?action=register" method="post">
        <input type="hidden" name="csrf_token"
               value="<?= e(csrf_token()) ?>">
        <label class="auth-form__field">
            <span class="auth-form__label">Имя</span>
            <input class="auth-form__input" type="text" name="name"
                   maxlength="100" value="<?= e($name) ?>" required>
        </label>
        <label class="auth-form__field">
            <span class="auth-form__label">E-mail</span>
            <input class="auth-form__input" type="email" name="email"
                   maxlength="190" autocomplete="email"
                   value="<?= e($email) ?>" required>
        </label>
        <label class="auth-form__field">
            <span class="auth-form__label">Пароль (не менее 12 символов)</span>
            <input class="auth-form__input" type="password"
                   name="password" minlength="12"
                   autocomplete="new-password" required>
        </label>
        <label class="auth-form__field">
            <span class="auth-form__label">Повторите пароль</span>
            <input class="auth-form__input" type="password"
                   name="password_confirm" minlength="12"
                   autocomplete="new-password" required>
        </label>
        <button class="button button--primary" type="submit">
            Создать аккаунт
        </button>
    </form>
</section>
<?php require __DIR__ . '/../../../include/footer.php'; ?>
