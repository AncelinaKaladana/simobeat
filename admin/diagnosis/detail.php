<?php
require_once __DIR__ . '/../../includes/admin-check.php';
$id = (int)($_GET['id'] ?? 0);
$q = $pdo->prepare('SELECT d.*,u.nama user_nama,u.email,m.kode motor_kode,m.nama motor_nama,m.merek,m.tahun,k.kode kerusakan_kode,k.nama kerusakan_nama,k.deskripsi kerusakan_deskripsi FROM diagnosis d JOIN users u ON u.id=d.user_id JOIN motor m ON m.id=d.motor_id LEFT JOIN kerusakan k ON k.id=d.kerusakan_id WHERE d.id=?');
$q->execute([$id]);
$d = $q->fetch();
if (!$d) {
    http_response_code(404);
    exit('Diagnosis tidak ditemukan.');
}
$q = $pdo->prepare('SELECT g.kode,g.nama,dg.jawaban FROM diagnosis_gejala dg JOIN gejala g ON g.id=dg.gejala_id WHERE dg.diagnosis_id=? ORDER BY g.kode');
$q->execute([$id]);
$answers = $q->fetchAll();
$sol = [];
if ($d['kerusakan_id']) {
    $q = $pdo->prepare('SELECT deskripsi FROM solusi WHERE kerusakan_id=?');
    $q->execute([(int)$d['kerusakan_id']]);
    $sol = $q->fetchAll();
}
$page_title = 'Detail Diagnosis #' . $id;
require __DIR__ . '/../../includes/header.php';
?><section class="app-content-header">
    <div class="container-fluid">
        <h3>Detail Diagnosis #<?= (int)$id ?></h3>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <p><b>Pengguna:</b> <?= e($d['user_nama'] . ' · ' . $d['email']) ?></p>
                <p><b>Tanggal:</b> <?= e(date('d-m-Y H:i', strtotime($d['created_at']))) ?></p>
                <p><b>Motor:</b> <?= e($d['motor_kode'] . ' - ' . $d['merek'] . ' ' . $d['motor_nama'] . ($d['tahun'] ? ' ' . $d['tahun'] : '')) ?></p>
                <h4><?= e($d['kerusakan_kode'] ? $d['kerusakan_kode'] . ' - ' . $d['kerusakan_nama'] : $d['hasil']) ?></h4><?php if ($d['kerusakan_deskripsi']): ?><p><?= e($d['kerusakan_deskripsi']) ?></p><?php endif ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Fakta Jawaban Gejala</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Gejala</th>
                                <th>Jawaban</th>
                            </tr>
                        </thead>
                        <tbody><?php foreach ($answers as $a): ?><tr>
                                    <td><?= e($a['kode']) ?></td>
                                    <td><?= e($a['nama']) ?></td>
                                    <td><span class="badge text-bg-<?= $a['jawaban'] === 'YA' ? 'success' : 'secondary' ?>"><?= e($a['jawaban']) ?></span></td>
                                </tr><?php endforeach ?></tbody>
                    </table>
                </div>
            </div>
        </div><?php if ($d['kerusakan_id']): ?><div class="card">
                <div class="card-header">Solusi</div>
                <div class="card-body"><?php if ($sol): ?><ol><?php foreach ($sol as $s): ?><li><?= nl2br(e($s['deskripsi'])) ?></li><?php endforeach ?></ol><?php else: ?>Belum ada solusi terkait.<?php endif ?></div>
            </div><?php endif ?><a href="index.php" class="btn btn-secondary mb-3">Kembali ke Data Diagnosis</a>
    </div>
</section><?php require __DIR__ . '/../../includes/footer.php'; ?>