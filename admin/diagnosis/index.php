<?php
require_once __DIR__ . '/../../includes/admin-check.php';
$search = trim((string)($_GET['q'] ?? ''));
$date = trim((string)($_GET['tanggal'] ?? ''));
$where = [];
$params = [];
if ($search !== '') {
    $where[] = '(u.nama LIKE ? OR u.email LIKE ? OR m.kode LIKE ? OR m.nama LIKE ? OR d.hasil LIKE ? OR k.kode LIKE ? OR k.nama LIKE ?)';
    $v = '%' . $search . '%';
    for ($i = 0; $i < 7; $i++) $params[] = $v;
}
if ($date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $where[] = 'DATE(d.created_at)=?';
    $params[] = $date;
}
$sql = 'SELECT d.id,d.created_at,d.hasil,u.nama user_nama,u.email,m.kode motor_kode,m.nama motor_nama,m.merek,m.tahun,k.kode kerusakan_kode,k.nama kerusakan_nama,COUNT(dg.id) jumlah_jawaban FROM diagnosis d JOIN users u ON u.id=d.user_id JOIN motor m ON m.id=d.motor_id LEFT JOIN kerusakan k ON k.id=d.kerusakan_id LEFT JOIN diagnosis_gejala dg ON dg.diagnosis_id=d.id';
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' GROUP BY d.id ORDER BY d.created_at DESC,d.id DESC';
$q = $pdo->prepare($sql);
$q->execute($params);
$rows = $q->fetchAll();
$page_title = 'Data Diagnosis';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php';
?><section class="app-content-header">
    <div class="container-fluid">
        <h3>Data Diagnosis Pengguna</h3>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Semua Riwayat Diagnosis</h3>
            </div>
            <div class="card-body">
                <form method="get" class="row g-2 mb-3">
                    <div class="col-md-8"><input class="form-control" type="search" name="q" placeholder="Cari nama/email pengguna, motor, atau hasil" value="<?= e($search) ?>"></div>
                    <div class="col-md-2"><input class="form-control" type="date" name="tanggal" value="<?= e($date) ?>"></div>
                    <div class="col-md-2"><button class="btn btn-primary">Filter</button> <a class="btn btn-outline-secondary" href="index.php">Reset</a></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pengguna</th>
                                <th>Motor</th>
                                <th>Hasil</th>
                                <th>Jawaban</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody><?php if ($rows): foreach ($rows as $i => $r): ?><tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= e(date('d-m-Y H:i', strtotime($r['created_at']))) ?></td>
                                        <td><?= e($r['user_nama'] . ' (' . $r['email'] . ')') ?></td>
                                        <td><?= e($r['motor_kode'] . ' - ' . $r['merek'] . ' ' . $r['motor_nama']) ?></td>
                                        <td><?= e($r['kerusakan_kode'] ? $r['kerusakan_kode'] . ' - ' . $r['kerusakan_nama'] : $r['hasil']) ?></td>
                                        <td><?= (int)$r['jumlah_jawaban'] ?></td>
                                        <td><a class="btn btn-info btn-sm" href="detail.php?id=<?= (int)$r['id'] ?>">Detail</a></td>
                                    </tr><?php endforeach;
                                    else: ?><tr>
                                    <td colspan="7" class="text-center">Tidak ada diagnosis pada filter tersebut.</td>
                                </tr><?php endif ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section><?php require __DIR__ . '/../../includes/footer.php'; ?>