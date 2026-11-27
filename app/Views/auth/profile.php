<?php require __DIR__ . '/../../../include/header.php'; ?>
<section class="user-profile">
    <h1 class="page__title">Личный кабинет</h1>
    <p class="page__lead">Вы вошли в систему.</p>

    <?php if (!empty($success)): ?>
        <div class="alert alert--success" role="status">
            <?= e($success) ?>
        </div>
    <?php endif; ?>

    <dl class="user-profile__details">
        <div class="user-profile__row">
            <dt class="user-profile__label">ID</dt>
            <dd class="user-profile__value"><?= (int) $user['id'] ?></dd>
        </div>
        <div class="user-profile__row">
            <dt class="user-profile__label">Имя</dt>
            <dd class="user-profile__value"><?= e($user['name']) ?></dd>
        </div>
        <div class="user-profile__row">
            <dt class="user-profile__label">E-mail</dt>
            <dd class="user-profile__value"><?= e($user['email']) ?></dd>
        </div>
        <div class="user-profile__row">
            <dt class="user-profile__label">Телефон</dt>
            <dd class="user-profile__value">
                <?= !empty($user['phone']) ? e($user['phone']) : 'Не указан' ?>
            </dd>
        </div>
        <div class="user-profile__row">
            <dt class="user-profile__label">Город</dt>
            <dd class="user-profile__value">
                <?= !empty($user['city']) ? e($user['city']) : 'Не указан' ?>
            </dd>
        </div>
        <div class="user-profile__row">
            <dt class="user-profile__label">Дата рождения</dt>
            <dd class="user-profile__value">
                <?= !empty($user['birth_date']) ? e($user['birth_date']) : 'Не указана' ?>
            </dd>
        </div>
        <div class="user-profile__row">
            <dt class="user-profile__label">О себе</dt>
            <dd class="user-profile__value">
                <?= !empty($user['about_me']) ? nl2br(e($user['about_me'])) : 'Информация не заполнена' ?>
            </dd>
        </div>
        <div class="user-profile__row">
            <dt class="user-profile__label">Дата регистрации</dt>
            <dd class="user-profile__value"><?= e($user['created_at']) ?></dd>
        </div>
    </dl>

    <div class="user-profile__actions">
        <a href="index.php?action=edit_profile" class="button button--primary">Редактировать профиль</a>
    </div>
</section>
<?php require __DIR__ . '/../../../include/footer.php'; ?>
