<?php require_once __DIR__ . '/../../includes/admin-check.php';
$data = [];
$selected = [];
$edit = false;
$faults = $pdo->query('SELECT id,kode,nama FROM kerusakan ORDER BY kode')->fetchAll();
$symptoms = $pdo->query('SELECT id,kode,nama FROM gejala ORDER BY kode')->fetchAll();
$solutions = [];
$page_title = 'Tambah Aturan';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php'; ?><section class="app-content-header">
    <div class="container-fluid">
        <h3>Tambah Aturan</h3>
    </div>
</section>
<section class="content">
    <div class="container-fluid"><?php require __DIR__ . '/_form.php'; ?></div>
</section><?php require __DIR__ . '/../../includes/footer.php'; ?>