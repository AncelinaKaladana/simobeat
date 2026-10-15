<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/redirect.php';
$pageTitle = $page_title ?? $pageTitle ?? APP_NAME;
?>
<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light dark">
  <title><?= e($pageTitle) ?> · <?= e(APP_NAME) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/adminlte/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/custom.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary <?= e($bodyClass ?? '') ?>">
  <div class="app-wrapper">
    <?php require __DIR__ . '/navbar.php'; ?>
    <?php if (($_SESSION['user']['role'] ?? '') === 'admin') {
      require __DIR__ . '/sidebar-admin.php';
    } elseif (($_SESSION['user']['role'] ?? '') === 'user') {
      require __DIR__ . '/sidebar-user.php';
    } ?>
    <main class="app-main">
      <div class="app-content">
        <div class="container-fluid">