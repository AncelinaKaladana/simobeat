<?php require_once __DIR__ . '/../../includes/admin-check.php';
$id = (int)($_GET['id'] ?? 0);
$q = $pdo->prepare('SELECT * FROM aturan WHERE id=?');
$q->execute([$id]);
$data = $q->fetch();
if (!$data) {
    http_response_code(404);
    exit('Aturan tidak ditemukan.');
}
$q = $pdo->prepare('SELECT gejala_id FROM aturan_detail WHERE aturan_id=?');
$q->execute([$id]);
$selected = array_map('intval', $q->fetchAll(PDO::FETCH_COLUMN));
$faults = $pdo->query('SELECT id,kode,nama FROM kerusakan ORDER BY kode')->fetchAll();
$symptoms = $pdo->query('SELECT id,kode,nama FROM gejala ORDER BY kode')->fetchAll();
$q = $pdo->prepare('SELECT deskripsi FROM solusi WHERE kerusakan_id=?');
$q->execute([(int)$data['kerusakan_id']]);
$solutions = $q->fetchAll();
$edit = true;
$page_title = 'Edit ' . $data['kode'];
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php'; ?><section class="app-content-header">
    <div class="container-fluid">
        <h3>Edit Aturan</h3>
    </div>
</section>
<section class="content">
    <div class="container-fluid"><?php require __DIR__ . '/_form.php'; ?></div>
</section><?php require __DIR__ . '/../../includes/footer.php'; ?>