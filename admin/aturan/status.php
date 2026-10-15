<?php require_once __DIR__ . '/../../includes/admin-check.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/admin/aturan/index.php');
verify_csrf();
$id = (int)($_POST['id'] ?? 0);
$st = (string)($_POST['status'] ?? '');
if (!$id || !in_array($st, ['aktif', 'nonaktif'], true)) {
    flash('error', 'Status tidak valid.');
    redirect('/admin/aturan/index.php');
}
$q = $pdo->prepare('SELECT kode FROM aturan WHERE id=?');
$q->execute([$id]);
$code = $q->fetchColumn();
if (!$code) {
    flash('error', 'Aturan tidak ditemukan.');
    redirect('/admin/aturan/index.php');
}
$q = $pdo->prepare('UPDATE aturan SET status=? WHERE id=?');
$q->execute([$st, $id]);
log_activity($pdo, (int)$_SESSION['user']['id'], 'Status ' . $st . ' aturan ' . $code);
flash('success', 'Status ' . $code . ' menjadi ' . $st . '.');
redirect('/admin/aturan/index.php');
