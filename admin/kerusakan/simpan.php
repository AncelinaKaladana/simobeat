<?php
require_once __DIR__ . '/../../includes/admin-check.php';
require_once __DIR__ . '/_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/kerusakan/index.php');
}

verify_csrf();

$nama = post_string('nama');
$deskripsi = post_string('deskripsi');
$status = (string)($_POST['status'] ?? 'aktif');
$errors = kerusakan_validation_errors($nama, $deskripsi);

if ($errors !== [] || !in_array($status, ['aktif', 'nonaktif'], true)) {
    flash('error', $errors[0] ?? 'Status tidak valid.');
    redirect('/admin/kerusakan/tambah.php');
}

$last = $pdo->query("SELECT kode FROM kerusakan WHERE kode REGEXP '^K[0-9]+$' ORDER BY CAST(SUBSTRING(kode, 2) AS UNSIGNED) DESC LIMIT 1")->fetchColumn();
$next = $last ? (int)substr($last, 1) + 1 : 1;
$kode = sprintf('K%03d', $next);

try {
    $pdo->beginTransaction();
    $query = $pdo->prepare('INSERT INTO kerusakan (kode, nama, deskripsi, status) VALUES (?, ?, ?, ?)');
    $query->execute([$kode, $nama, $deskripsi, $status]);
    $pdo->commit();

    log_activity($pdo, (int)$_SESSION['user']['id'], 'Tambah kerusakan ' . $kode);
    flash('success', 'Kerusakan ' . $kode . ' berhasil ditambahkan.');
} catch (PDOException $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Gagal menambah kerusakan: ' . $exception->getMessage());
    flash('error', 'Gagal menyimpan kerusakan. Silakan coba lagi.');
    redirect('/admin/kerusakan/tambah.php');
}

redirect('/admin/kerusakan/index.php');
