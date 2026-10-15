<?php require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/redirect.php';
if (!empty($_SESSION['user'])) redirect($_SESSION['user']['role'] === 'admin' ? '/admin/dashboard.php' : '/user/dashboard.php');
redirect('/auth/login.php');
