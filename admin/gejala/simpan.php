<?php
require_once __DIR__ . '/../../includes/admin-check.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/admin/gejala/index.php');
verify_csrf();
$kode = post_string('kode');
$nama = post_string('nama');
$deskripsi = post_string('deskripsi');
if ($kode === '' || $nama === '') {
    flash('error', 'Kode dan nama gejala wajib diisi.');
    redirect('/admin/gejala/tambah.php');
}
try {
    $q = $pdo->prepare('INSERT INTO gejala(kode,nama,deskripsi) VALUES(?,?,?)');
    $q->execute([$kode, $nama, $deskripsi]);
    log_activity($pdo, (int)$_SESSION['user']['id'], 'Tambah gejala ' . $kode);
    flash('success', 'Gejala berhasil ditambahkan.');
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        flash('error', 'Kode gejala sudah digunakan.');
        redirect('/admin/gejala/tambah.php');
    }
    throw $e;
}
redirect('/admin/gejala/index.php');
