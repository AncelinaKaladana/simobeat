<?php
require_once __DIR__ . '/../../includes/admin-check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/kerusakan/index.php');
}

verify_csrf();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    flash('error', 'Data kerusakan tidak ditemukan.');
    redirect('/admin/kerusakan/index.php');
}

try {
    $query = $pdo->prepare('SELECT kode FROM kerusakan WHERE id = ?');
    $query->execute([$id]);
    $kode = $query->fetchColumn();

    if (!$kode) {
        flash('error', 'Data kerusakan tidak ditemukan.');
        redirect('/admin/kerusakan/index.php');
    }

    $pdo->beginTransaction();
    $pdo->prepare('DELETE FROM kerusakan WHERE id = ?')->execute([$id]);
    $pdo->commit();

    log_activity($pdo, (int)$_SESSION['user']['id'], 'Hapus kerusakan ' . $kode);
    flash('success', 'Kerusakan dan data terkait berhasil dihapus.');
} catch (PDOException $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Gagal menghapus kerusakan ID ' . $id . ': ' . $exception->getMessage());
    flash('error', 'Gagal menghapus kerusakan. Silakan coba lagi.');
}

redirect('/admin/kerusakan/index.php');
