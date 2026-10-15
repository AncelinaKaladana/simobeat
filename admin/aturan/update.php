<?php
require_once __DIR__ . '/../../includes/admin-check.php';
require_once __DIR__ . '/_helpers.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/admin/aturan/index.php');
verify_csrf();
$id = (int)($_POST['id'] ?? 0);
$code = strtoupper(post_string('kode'));
$kid = (int)($_POST['kerusakan_id'] ?? 0);
$status = (string)($_POST['status'] ?? '');
$raw = $_POST['gejala'] ?? [];
$ids = is_array($raw) ? array_values(array_unique(array_filter(array_map('intval', $raw), static fn($v) => $v > 0))) : [];
$back = '/admin/aturan/edit.php?id=' . $id;
if (!$id || !preg_match('/^R[0-9]{1,27}$/', $code) || !$kid || !$ids || !in_array($status, ['aktif', 'nonaktif'], true)) {
    flash('error', 'Periksa kode, kerusakan, minimal satu gejala, dan status.');
    redirect($back);
}
$q = $pdo->prepare('SELECT COUNT(*) FROM aturan WHERE id=?');
$q->execute([$id]);
if (!(int)$q->fetchColumn()) {
    flash('error', 'Aturan tidak ditemukan.');
    redirect('/admin/aturan/index.php');
}
$q = $pdo->prepare('SELECT COUNT(*) FROM aturan WHERE kode=? AND id<>?');
$q->execute([$code, $id]);
if ((int)$q->fetchColumn()) {
    flash('error', 'Kode aturan sudah digunakan.');
    redirect($back);
}
$q = $pdo->prepare('SELECT COUNT(*) FROM kerusakan WHERE id=?');
$q->execute([$kid]);
if (!(int)$q->fetchColumn()) {
    flash('error', 'Kerusakan tidak ditemukan.');
    redirect($back);
}
$marks = implode(',', array_fill(0, count($ids), '?'));
$q = $pdo->prepare("SELECT COUNT(*) FROM gejala WHERE id IN ($marks)");
$q->execute($ids);
if ((int)$q->fetchColumn() !== count($ids)) {
    flash('error', 'Satu atau lebih gejala tidak valid.');
    redirect($back);
}
if (rule_set_exists($pdo, $kid, $ids, $id)) {
    flash('error', 'Kombinasi kerusakan dan gejala yang sama sudah dipakai aturan lain.');
    redirect($back);
}
$pdo->beginTransaction();
try {
    $q = $pdo->prepare('UPDATE aturan SET kode=?,kerusakan_id=?,status=? WHERE id=?');
    $q->execute([$code, $kid, $status, $id]);
    $pdo->prepare('DELETE FROM aturan_detail WHERE aturan_id=?')->execute([$id]);
    $q = $pdo->prepare('INSERT INTO aturan_detail(aturan_id,gejala_id) VALUES(?,?)');
    foreach ($ids as $gid) $q->execute([$id, $gid]);
    log_activity($pdo, (int)$_SESSION['user']['id'], 'Ubah aturan ' . $code);
    $pdo->commit();
    flash('success', 'Aturan ' . $code . ' berhasil diperbarui.');
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    if ($e instanceof PDOException && $e->getCode() === '23000') {
        flash('error', 'Kode aturan sudah digunakan atau relasi tidak valid.');
        redirect($back);
    }
    throw $e;
}
redirect('/admin/aturan/index.php');
