<?php

function e(string|int|null $value): string
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $expected = $_SESSION['csrf_token'] ?? '';
    $provided = $_POST['csrf_token'] ?? '';
    if (!is_string($expected) || $expected === ''
        || !is_string($provided)
        || !hash_equals($expected, $provided)) {
        http_response_code(403);
        exit('Недействительный запрос. Обновите страницу.');
    }
}

function require_login(): void
{
    if (!isset($_SESSION['user_id'])
        || !is_int($_SESSION['user_id'])) {
        header('Location: index.php?action=login');
        exit;
    }
}

function password_algorithm(): string|int
{
    return defined('PASSWORD_ARGON2ID')
        ? PASSWORD_ARGON2ID
        : PASSWORD_DEFAULT;
}

function password_options(): array
{
    if (defined('PASSWORD_ARGON2ID')) {
        return [
            'memory_cost' => 19 * 1024,
            'time_cost' => 2,
            'threads' => 1,
        ];
    }
    return [];
}
