<?php
require_once __DIR__ . '/../../includes/admin-check.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/admin/gejala/index.php');
verify_csrf();
$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) redirect('/admin/gejala/index.php');
$q = $pdo->prepare('SELECT COUNT(*) FROM aturan_detail WHERE gejala_id=?');
$q->execute([$id]);
$usedByRules = (int)$q->fetchColumn();
$q = $pdo->prepare('SELECT COUNT(*) FROM diagnosis_gejala WHERE gejala_id=?');
$q->execute([$id]);
$usedByDiagnoses = (int)$q->fetchColumn();
if ($usedByRules || $usedByDiagnoses) {
    flash('error', 'Gejala tidak dapat dihapus karena sudah digunakan oleh aturan atau riwayat diagnosis.');
    redirect('/admin/gejala/index.php');
}
$q = $pdo->prepare('DELETE FROM gejala WHERE id=?');
$q->execute([$id]);
log_activity($pdo, (int)$_SESSION['user']['id'], 'Hapus gejala ID ' . $id);
flash('success', 'Gejala berhasil dihapus.');
redirect('/admin/gejala/index.php');
