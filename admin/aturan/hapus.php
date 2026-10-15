<?php require_once __DIR__ . '/../../includes/admin-check.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/admin/aturan/index.php');
verify_csrf();
$id = (int)($_POST['id'] ?? 0);
$q = $pdo->prepare('SELECT kode FROM aturan WHERE id=?');
$q->execute([$id]);
$code = $q->fetchColumn();
if (!$code) {
    flash('error', 'Aturan tidak ditemukan.');
    redirect('/admin/aturan/index.php');
}
$pdo->prepare('DELETE FROM aturan WHERE id=?')->execute([$id]);
log_activity($pdo, (int)$_SESSION['user']['id'], 'Hapus aturan ' . $code);
flash('success', 'Aturan dan detail gejalanya dihapus.');
redirect('/admin/aturan/index.php');
