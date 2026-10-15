<?php
require_once __DIR__ . '/../../includes/admin-check.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/admin/gejala/index.php');
verify_csrf();
$id = (int)($_POST['id'] ?? 0);
$kode = post_string('kode');
$nama = post_string('nama');
$deskripsi = post_string('deskripsi');
if ($id <= 0 || $kode === '' || $nama === '') {
    flash('error', 'Kode dan nama gejala wajib diisi.');
    redirect('/admin/gejala/index.php');
}
try {
    $q = $pdo->prepare('UPDATE gejala SET kode=?,nama=?,deskripsi=? WHERE id=?');
    $q->execute([$kode, $nama, $deskripsi, $id]);
    log_activity($pdo, (int)$_SESSION['user']['id'], 'Ubah gejala ' . $kode);
    flash('success', 'Gejala berhasil diperbarui.');
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        flash('error', 'Kode gejala sudah digunakan.');
        redirect('/admin/gejala/edit.php?id=' . $id);
    }
    throw $e;
}
redirect('/admin/gejala/index.php');
