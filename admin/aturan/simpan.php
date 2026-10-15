<?php require_once __DIR__ . '/../../includes/admin-check.php';
require_once __DIR__ . '/_helpers.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/admin/aturan/index.php');
verify_csrf();
$code = strtoupper(post_string('kode'));
$kid = (int)($_POST['kerusakan_id'] ?? 0);
$st = (string)($_POST['status'] ?? 'aktif');
$raw = $_POST['gejala'] ?? [];
$ids = is_array($raw) ? array_values(array_unique(array_filter(array_map('intval', $raw), fn($x) => $x > 0))) : [];
$back = '/admin/aturan/tambah.php';
if (!preg_match('/^R[0-9]{1,27}$/', $code) || !$kid || !$ids || !in_array($st, ['aktif', 'nonaktif'], true)) {
    flash('error', 'Isi kode R+angka, pilih kerusakan dan minimal satu gejala.');
    redirect($back);
}
$q = $pdo->prepare('SELECT COUNT(*) FROM kerusakan WHERE id=?');
$q->execute([$kid]);
if (!(int)$q->fetchColumn()) {
    flash('error', 'Kerusakan tidak ditemukan.');
    redirect($back);
}
$m = implode(',', array_fill(0, count($ids), '?'));
$q = $pdo->prepare("SELECT COUNT(*) FROM gejala WHERE id IN ($m)");
$q->execute($ids);
if ((int)$q->fetchColumn() !== count($ids) || rule_set_exists($pdo, $kid, $ids)) {
    flash('error', 'Gejala tidak valid atau kombinasi aturan duplikat.');
    redirect($back);
}
$pdo->beginTransaction();
try {
    $q = $pdo->prepare('INSERT INTO aturan(kode,kerusakan_id,status) VALUES(?,?,?)');
    $q->execute([$code, $kid, $st]);
    $rid = (int)$pdo->lastInsertId();
    $q = $pdo->prepare('INSERT INTO aturan_detail(aturan_id,gejala_id) VALUES(?,?)');
    foreach ($ids as $g) $q->execute([$rid, $g]);
    log_activity($pdo, (int)$_SESSION['user']['id'], 'Tambah aturan ' . $code);
    $pdo->commit();
    flash('success', 'Aturan ' . $code . ' berhasil disimpan.');
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    if ($e instanceof PDOException && $e->getCode() === '23000') {
        flash('error', 'Kode aturan sudah dipakai.');
        redirect($back);
    }
    throw $e;
}
redirect('/admin/aturan/index.php');
