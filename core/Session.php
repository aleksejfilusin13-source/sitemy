<?php
// Файл подключается до вывода HTML и до session_start().
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

$isHttps = !empty($_SERVER['HTTPS'])
    && $_SERVER['HTTPS'] !== 'off';

session_name('student_mvc_session');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps, // На рабочем сайте обязателен HTTPS.
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
