<?php require_once __DIR__ . '/../../includes/admin-check.php';
$id = (int)($_GET['id'] ?? 0);
$q = $pdo->prepare('SELECT a.*,k.kode kk,k.nama kn FROM aturan a JOIN kerusakan k ON k.id=a.kerusakan_id WHERE a.id=?');
$q->execute([$id]);
$r = $q->fetch();
if (!$r) {
    http_response_code(404);
    exit('Aturan tidak ditemukan.');
}
$q = $pdo->prepare('SELECT g.kode,g.nama,g.deskripsi FROM aturan_detail ad JOIN gejala g ON g.id=ad.gejala_id WHERE ad.aturan_id=? ORDER BY g.kode');
$q->execute([$id]);
$gs = $q->fetchAll();
$q = $pdo->prepare('SELECT deskripsi FROM solusi WHERE kerusakan_id=?');
$q->execute([(int)$r['kerusakan_id']]);
$ss = $q->fetchAll();
$page_title = 'Detail ' . $r['kode'];
require __DIR__ . '/../../includes/header.php'; ?><section class="app-content-header">
    <div class="container-fluid">
        <h3>Detail Aturan <?= e($r['kode']) ?></h3>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <p><b>Status:</b> <?= e($r['status']) ?></p>
                <p><b>Kerusakan:</b> <?= e($r['kk'] . ' - ' . $r['kn']) ?></p>
                <p><b>Gejala (semua YA):</b></p>
                <ol><?php foreach ($gs as $g): ?><li><?= e($g['kode'] . ' - ' . $g['nama'] . ' ' . $g['deskripsi']) ?></li><?php endforeach ?></ol>
                <p><b>Solusi terkait:</b></p>
                <ol><?php foreach ($ss as $s): ?><li><?= nl2br(e($s['deskripsi'])) ?></li><?php endforeach ?></ol><a class="btn btn-warning" href="edit.php?id=<?= (int)$id ?>">Edit</a> <a class="btn btn-secondary" href="index.php">Kembali</a>
                <p class="small text-body-secondary mt-3">Solusi mengikuti kerusakan melalui solusi.kerusakan_id.</p>
            </div>
        </div>
    </div>
</section><?php require __DIR__ . '/../../includes/footer.php'; ?>