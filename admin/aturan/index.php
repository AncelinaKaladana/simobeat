<?php
require_once __DIR__ . '/../../includes/admin-check.php';
$s = trim((string)($_GET['q'] ?? ''));
$kid = (int)($_GET['kerusakan_id'] ?? 0);
$ng = trim((string)($_GET['jumlah_gejala'] ?? ''));
$st = (string)($_GET['status'] ?? '');
if (!in_array($st, ['aktif', 'nonaktif'], true)) $st = '';
$w = [];
$p = [];
if ($s !== '') {
    $w[] = '(a.kode LIKE ? OR k.kode LIKE ? OR k.nama LIKE ?)';
    $v = '%' . $s . '%';
    array_push($p, $v, $v, $v);
}
if ($kid) {
    $w[] = 'a.kerusakan_id=?';
    $p[] = $kid;
}
if ($st !== '') {
    $w[] = 'a.status=?';
    $p[] = $st;
}
$sql = 'SELECT a.id,a.kode,a.status,k.id kid,k.kode kk,k.nama kn,COUNT(DISTINCT ad.gejala_id) gejala,COUNT(DISTINCT so.id) solusi FROM aturan a JOIN kerusakan k ON k.id=a.kerusakan_id LEFT JOIN aturan_detail ad ON ad.aturan_id=a.id LEFT JOIN solusi so ON so.kerusakan_id=k.id';
if ($w) $sql .= ' WHERE ' . implode(' AND ', $w);
$sql .= ' GROUP BY a.id,k.id';
if ($ng !== '' && ctype_digit($ng)) $sql .= ' HAVING gejala=' . (int)$ng;
$sql .= ' ORDER BY a.kode';
$q = $pdo->prepare($sql);
$q->execute($p);
$rows = $q->fetchAll();
$faults = $pdo->query('SELECT id,kode,nama FROM kerusakan ORDER BY kode')->fetchAll();
$stats = $pdo->query("SELECT COUNT(*) total,SUM(status='aktif') aktif,SUM(status='nonaktif') nonaktif FROM aturan")->fetch();
$page_title = 'Aturan / Basis Pengetahuan';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php'; ?>
<section class="app-content-header">
    <div class="container-fluid">
        <h3>Aturan / Basis Pengetahuan</h3>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row g-3 mb-3"><?php foreach (['total' => 'Total', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $k => $v): ?><div class="col-md-4">
                    <div class="small-box text-bg-<?= $k === 'aktif' ? 'success' : ($k === 'nonaktif' ? 'secondary' : 'primary') ?>">
                        <div class="inner">
                            <h3><?= (int)$stats[$k] ?></h3>
                            <p><?= e($v) ?> Aturan</p>
                        </div><i class="small-box-icon bi bi-diagram-3"></i>
                    </div>
                </div><?php endforeach ?></div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Daftar Aturan</h3><a class="btn btn-primary btn-sm" href="tambah.php">+ Tambah Aturan</a>
            </div>
            <div class="card-body">
                <form class="row g-2 mb-3" method="get">
                    <div class="col-md-4"><input class="form-control" name="q" placeholder="Cari kode atau kerusakan" value="<?= e($s) ?>"></div>
                    <div class="col-md-3"><select class="form-select" name="kerusakan_id">
                            <option value="0">Semua kerusakan</option><?php foreach ($faults as $f): ?><option value="<?= (int)$f['id'] ?>" <?= $kid === (int)$f['id'] ? 'selected' : '' ?>><?= e($f['kode'] . ' - ' . $f['nama']) ?></option><?php endforeach ?>
                        </select></div>
                    <div class="col-md-2"><input class="form-control" type="number" min="0" name="jumlah_gejala" placeholder="Jumlah gejala" value="<?= e($ng) ?>"></div>
                    <div class="col-md-2"><select class="form-select" name="status">
                            <option value="">Semua status</option>
                            <option value="aktif" <?= $st === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $st === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select></div>
                    <div class="col-md-1"><button class="btn btn-primary"><i class="bi bi-search"></i></button></div>
                </form>
                <div class="alert alert-info">Seluruh gejala aturan harus dijawab YA. Solusi mengikuti kerusakan melalui relasi solusi.kerusakan_id.</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Kerusakan</th>
                                <th>Gejala</th>
                                <th>Solusi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody><?php if ($rows): foreach ($rows as $i => $r): ?><tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= e($r['kode']) ?></td>
                                        <td><?= e($r['kk'] . ' - ' . $r['kn']) ?></td>
                                        <td><?= (int)$r['gejala'] ?></td>
                                        <td><?= (int)$r['solusi'] ?></td>
                                        <td><span class="badge text-bg-<?= $r['status'] === 'aktif' ? 'success' : 'secondary' ?>"><?= e($r['status']) ?></span></td>
                                        <td class="text-nowrap"><a class="btn btn-info btn-sm" href="detail.php?id=<?= (int)$r['id'] ?>">Detail</a> <a class="btn btn-warning btn-sm" href="edit.php?id=<?= (int)$r['id'] ?>">Edit</a>
                                            <form class="d-inline" method="post" action="status.php"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><input type="hidden" name="status" value="<?= $r['status'] === 'aktif' ? 'nonaktif' : 'aktif' ?>"><button class="btn btn-secondary btn-sm"><?= $r['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?></button></form>
                                            <form class="d-inline" method="post" action="hapus.php" onsubmit="return confirm('Hapus aturan beserta gejalanya?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn btn-danger btn-sm">Hapus</button></form>
                                        </td>
                                    </tr><?php endforeach;
                                    else: ?><tr>
                                    <td colspan="7">Tidak ada data cocok.</td>
                                </tr><?php endif ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section><?php require __DIR__ . '/../../includes/footer.php'; ?>