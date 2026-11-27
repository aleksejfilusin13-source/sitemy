<?php

require_once __DIR__ . '/../Models/User.php';

final class AuthController
{
    public function register(): void
    {
        if (!empty($_SESSION['user_id'])) {
            header('Location: index.php?action=profile');
            exit;
        }
        $errors = [];
        $name = '';
        $email = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $nameInput = $_POST['name'] ?? '';
            $emailInput = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['password_confirm'] ?? '';
            $name = is_string($nameInput) ? trim($nameInput) : '';
            $email = is_string($emailInput)
                ? strtolower(trim($emailInput)) : '';
            $password = is_string($password) ? $password : '';
            $confirm = is_string($confirm) ? $confirm : '';
            if ($name === '' || strlen($name) > 100) {
                $errors[] = 'Укажите имя длиной не более 100 байт.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)
                || strlen($email) > 190) {
                $errors[] = 'Укажите корректный e-mail.';
            }
            if (strlen($password) < 12) {
                $errors[] = 'Пароль должен содержать не менее 12 символов.';
            }
            if (password_algorithm() === PASSWORD_DEFAULT
                && strlen($password) > 72) {
                $errors[] = 'Для текущего алгоритма пароль не должен превышать 72 байта.';
            }
            if ($password !== $confirm) {
                $errors[] = 'Пароли не совпадают.';
            }
            if (!$errors && User::findByEmail($email) !== null) {
                $errors[] = 'Пользователь с таким e-mail уже существует.';
            }
            if (!$errors) {
                $hash = password_hash(
                    $password,
                    password_algorithm(),
                    password_options()
                );
                try {
                    $userId = User::create($name, $email, $hash);
                } catch (PDOException $exception) {
                    if ($exception->getCode() === '23000') {
                        $errors[] = 'Пользователь с таким e-mail уже существует.';
                    } else {
                        throw $exception;
                    }
                }
                if (isset($userId)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $name;
                    unset($_SESSION['csrf_token']);
                    header('Location: index.php?action=profile');
                    exit;
                }
            }
        }
        require __DIR__ . '/../Views/auth/register.php';
    }

    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            header('Location: index.php?action=profile');
            exit;
        }
        $errors = [];
        $email = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $emailInput = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $email = is_string($emailInput)
                ? strtolower(trim($emailInput)) : '';
            $password = is_string($password) ? $password : '';
            $user = User::findByEmail($email);
            $hash = $user['password_hash'] ?? null;
            if (!$user || !is_string($hash) || $hash === ''
                || !password_verify($password, $hash)) {
                $errors[] = 'Неверный e-mail или пароль.';
            } else {
                if (password_needs_rehash(
                    $hash,
                    password_algorithm(),
                    password_options()
                )) {
                    $newHash = password_hash(
                        $password,
                        password_algorithm(),
                        password_options()
                    );
                    User::updatePasswordHash((int) $user['id'], $newHash);
                }
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_name'] = $user['name'];
                unset($_SESSION['csrf_token']);
                header('Location: index.php?action=profile');
                exit;
            }
        }
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Метод не поддерживается.');
        }
        verify_csrf();
        $_SESSION = [];
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
    public function profile(): void
    {
        require_login();
        $user = User::findById((int) $_SESSION['user_id']);
        if ($user === null) {
            $_SESSION = [];
            session_destroy();
            header('Location: index.php?action=login');
            exit;
        }
        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        require __DIR__ . '/../Views/auth/profile.php';
    }

    public function editProfile(): void
    {
        require_login();
        $userId = (int) $_SESSION['user_id'];
        $user = User::findById($userId);

        if ($user === null) {
            $_SESSION = [];
            session_destroy();
            header('Location: index.php?action=login');
            exit;
        }

        $errors = [];
        $name = $user['name'];
        $phone = $user['phone'] ?? '';
        $city = $user['city'] ?? '';
        $birthDate = $user['birth_date'] ?? '';
        $aboutMe = $user['about_me'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $nameInput = $_POST['name'] ?? '';
            $phoneInput = $_POST['phone'] ?? '';
            $cityInput = $_POST['city'] ?? '';
            $birthDateInput = $_POST['birth_date'] ?? '';
            $aboutMeInput = $_POST['about_me'] ?? '';

            $name = is_string($nameInput) ? trim($nameInput) : '';
            $phone = is_string($phoneInput) ? trim($phoneInput) : '';
            $city = is_string($cityInput) ? trim($cityInput) : '';
            $birthDate = is_string($birthDateInput) ? trim($birthDateInput) : '';
            $aboutMe = is_string($aboutMeInput) ? trim($aboutMeInput) : '';

            if ($name === '' || strlen($name) > 100) {
                $errors[] = 'Укажите имя длиной не более 100 байт.';
            }
            if ($phone !== '' && mb_strlen($phone) > 30) {
                $errors[] = 'Телефон не должен превышать 30 символов.';
            }
            if ($city !== '' && mb_strlen($city) > 100) {
                $errors[] = 'Название города не должно превышать 100 символов.';
            }
            if ($birthDate !== '') {
                $dateObj = DateTimeImmutable::createFromFormat('Y-m-d', $birthDate);
                $isValidDate = $dateObj && $dateObj->format('Y-m-d') === $birthDate;
                if (!$isValidDate) {
                    $errors[] = 'Укажите корректную дату рождения.';
                } elseif ($dateObj > new DateTimeImmutable('today')) {
                    $errors[] = 'Дата рождения не может быть в будущем.';
                }
            }
            if ($aboutMe !== '' && mb_strlen($aboutMe) > 500) {
                $errors[] = 'Информация о себе не должна превышать 500 символов.';
            }

            if (!$errors) {
                User::updateProfile(
                    $userId,
                    $name,
                    $phone === '' ? null : $phone,
                    $city === '' ? null : $city,
                    $birthDate === '' ? null : $birthDate,
                    $aboutMe === '' ? null : $aboutMe
                );
                $_SESSION['user_name'] = $name;
                $_SESSION['flash_success'] = 'Данные профиля успешно обновлены.';
                header('Location: index.php?action=profile');
                exit;
            }
        }

        require __DIR__ . '/../Views/auth/profile-edit.php';
    }

    public function changePassword(): void
    {
        require_login();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=edit_profile');
            exit;
        }

        verify_csrf();
        $userId = (int) $_SESSION['user_id'];
        $user = User::findById($userId);

        if ($user === null) {
            $_SESSION = [];
            session_destroy();
            header('Location: index.php?action=login');
            exit;
        }

        $currentPassword = is_string($_POST['current_password'] ?? null) ? $_POST['current_password'] : '';
        $newPassword = is_string($_POST['new_password'] ?? null) ? $_POST['new_password'] : '';
        $confirmPassword = is_string($_POST['new_password_confirm'] ?? null) ? $_POST['new_password_confirm'] : '';

        $passwordErrors = [];

        if ($currentPassword === '' || !password_verify($currentPassword, $user['password_hash'])) {
            $passwordErrors[] = 'Неверный текущий пароль.';
        }
        if (strlen($newPassword) < 12) {
            $passwordErrors[] = 'Новый пароль должен содержать не менее 12 символов.';
        }
        if (password_algorithm() === PASSWORD_DEFAULT && strlen($newPassword) > 72) {
            $passwordErrors[] = 'Для текущего алгоритма пароль не должен превышать 72 байта.';
        }
        if ($newPassword !== $confirmPassword) {
            $passwordErrors[] = 'Новый пароль и подтверждение не совпадают.';
        }

        if (!$passwordErrors) {
            $newHash = password_hash(
                $newPassword,
                password_algorithm(),
                password_options()
            );
            User::updatePasswordHash($userId, $newHash);
            session_regenerate_id(true);
            unset($_SESSION['csrf_token']);

            $_SESSION['flash_success'] = 'Пароль успешно изменён.';
            header('Location: index.php?action=profile');
            exit;
        }

        $errors = [];
        $name = $user['name'];
        $phone = $user['phone'] ?? '';
        $city = $user['city'] ?? '';
        $birthDate = $user['birth_date'] ?? '';
        $aboutMe = $user['about_me'] ?? '';

        require __DIR__ . '/../Views/auth/profile-edit.php';
    }
}
