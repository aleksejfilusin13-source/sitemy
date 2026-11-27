<?php require __DIR__ . '/../../../include/header.php'; ?>
<section class="auth-form">
    <h1 class="page__title">Редактирование профиля</h1>

    <?php foreach ($errors as $error): ?>
        <p class="alert alert--error"><?= e($error) ?></p>
    <?php endforeach; ?>

    <form class="auth-form__form" action="index.php?action=edit_profile" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

        <label class="auth-form__field">
            <span class="auth-form__label">Имя *</span>
            <input class="auth-form__input" type="text" name="name"
                   maxlength="100" value="<?= e($name) ?>" required>
        </label>

        <label class="auth-form__field">
            <span class="auth-form__label">Телефон</span>
            <input class="auth-form__input" type="tel" name="phone"
                   maxlength="30" placeholder="+7 (999) 000-00-00"
                   value="<?= e($phone) ?>">
        </label>

        <label class="auth-form__field">
            <span class="auth-form__label">Город</span>
            <input class="auth-form__input" type="text" name="city"
                   maxlength="100" value="<?= e($city) ?>">
        </label>

        <label class="auth-form__field">
            <span class="auth-form__label">Дата рождения</span>
            <input class="auth-form__input" type="date" name="birth_date"
                   max="<?= date('Y-m-d') ?>" value="<?= e($birthDate) ?>">
        </label>

        <label class="auth-form__field">
            <span class="auth-form__label">Информация о себе</span>
            <textarea class="auth-form__input" name="about_me"
                      rows="4" maxlength="500"><?= e($aboutMe) ?></textarea>
        </label>

        <div class="auth-form__actions">
            <button class="button button--primary" type="submit">Сохранить изменения</button>
            <a href="index.php?action=profile" class="button">Отмена</a>
        </div>
    </form>

    <hr>

    <h2 class="page__title">Смена пароля</h2>

    <?php if (!empty($passwordErrors)): ?>
        <?php foreach ($passwordErrors as $error): ?>
            <p class="alert alert--error"><?= e($error) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form class="auth-form__form" action="index.php?action=change_password" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

        <label class="auth-form__field">
            <span class="auth-form__label">Текущий пароль *</span>
            <input class="auth-form__input" type="password" name="current_password"
                   autocomplete="current-password" required>
        </label>

        <label class="auth-form__field">
            <span class="auth-form__label">Новый пароль (не менее 12 символов) *</span>
            <input class="auth-form__input" type="password" name="new_password"
                   minlength="12" autocomplete="new-password" required>
        </label>

        <label class="auth-form__field">
            <span class="auth-form__label">Подтвердите новый пароль *</span>
            <input class="auth-form__input" type="password" name="new_password_confirm"
                   minlength="12" autocomplete="new-password" required>
        </label>

        <button class="button button--primary" type="submit">Изменить пароль</button>
    </form>
</section>
<?php require __DIR__ . '/../../../include/footer.php'; ?>
