<?php
require_once __DIR__ . '/../../includes/admin-check.php';
require_once __DIR__ . '/_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/kerusakan/index.php');
}

verify_csrf();

$id = (int)($_POST['id'] ?? 0);
$nama = post_string('nama');
$deskripsi = post_string('deskripsi');
$status = (string)($_POST['status'] ?? '');
$errors = kerusakan_validation_errors($nama, $deskripsi);

if ($id <= 0) {
    flash('error', 'Data kerusakan tidak ditemukan.');
    redirect('/admin/kerusakan/index.php');
}

if ($errors !== [] || !in_array($status, ['aktif', 'nonaktif'], true)) {
    flash('error', $errors[0] ?? 'Status tidak valid.');
    redirect('/admin/kerusakan/edit.php?id=' . $id);
}

try {
    $query = $pdo->prepare('UPDATE kerusakan SET nama = ?, deskripsi = ?, status = ? WHERE id = ?');
    $query->execute([$nama, $deskripsi, $status, $id]);

    if ($query->rowCount() === 0) {
        flash('error', 'Data kerusakan tidak ditemukan.');
        redirect('/admin/kerusakan/index.php');
    }

    log_activity($pdo, (int)$_SESSION['user']['id'], 'Ubah kerusakan ID ' . $id);
    flash('success', 'Kerusakan berhasil diperbarui.');
} catch (PDOException $exception) {
    error_log('Gagal memperbarui kerusakan ID ' . $id . ': ' . $exception->getMessage());
    flash('error', 'Gagal memperbarui kerusakan. Silakan coba lagi.');
}

redirect('/admin/kerusakan/index.php');
