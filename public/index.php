<?php

require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/StudentController.php';

$auth = new AuthController();
$students = new StudentController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'register':
        $auth->register();
        break;
    case 'login':
        $auth->login();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'profile':
        $auth->profile();
        break;
    case 'edit_profile':
        $auth->editProfile();
        break;
    case 'change_password':
        $auth->changePassword();
        break;
    case 'index':
        $students->index();
        break;
    case 'show':
        $students->show();
        break;
    case 'create':
        $students->create();
        break;
    case 'statistics':
        $students->statistics();
        break;
    default:
        http_response_code(404);
        echo 'Страница не найдена';
}
