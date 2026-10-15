<?php
require_once __DIR__ . '/../../includes/admin-check.php';
$id = (int)($_GET['id'] ?? 0);
$q = $pdo->prepare('SELECT id,kode,nama,deskripsi,status FROM kerusakan WHERE id=?');
$q->execute([$id]);
$fault = $q->fetch();
if (!$fault) {
  http_response_code(404);
  exit('Data kerusakan tidak ditemukan.');
}
$q = $pdo->prepare('SELECT id,deskripsi FROM solusi WHERE kerusakan_id=? ORDER BY id');
$q->execute([$id]);
$solutions = $q->fetchAll();
$q = $pdo->prepare('SELECT a.id,a.kode,g.kode gejala_kode,g.nama gejala_nama FROM aturan a LEFT JOIN aturan_detail ad ON ad.aturan_id=a.id LEFT JOIN gejala g ON g.id=ad.gejala_id WHERE a.kerusakan_id=? ORDER BY a.kode,g.kode');
$q->execute([$id]);
$ruleRows = $q->fetchAll();
$rules = [];
foreach ($ruleRows as $row) {
  $key = (int)$row['id'];
  if (!isset($rules[$key])) $rules[$key] = ['id' => $key, 'kode' => $row['kode'], 'gejala' => []];
  if ($row['gejala_kode'] !== null) $rules[$key]['gejala'][] = ['kode' => $row['gejala_kode'], 'nama' => $row['gejala_nama']];
}
$rules = array_values($rules);
$page_title = 'Detail Kerusakan';
require __DIR__ . '/../../includes/header.php';
?>
<section class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="mb-0">Detail Kerusakan</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end mb-0">
          <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="index.php">Kerusakan</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-5">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Informasi Kerusakan</h3>
          </div>
          <div class="card-body">
            <dl class="row mb-0">
              <dt class="col-sm-4">Kode</dt>
              <dd class="col-sm-8"><span class="badge text-bg-danger"><?= e($fault['kode']) ?></span></dd>
              <dt class="col-sm-4">Nama</dt>
              <dd class="col-sm-8"><?= e($fault['nama']) ?></dd>
              <dt class="col-sm-4">Deskripsi</dt>
              <dd class="col-sm-8"><?= nl2br(e($fault['deskripsi'] ?? '-')) ?></dd>
              <dt class="col-sm-4">Status</dt>
              <dd class="col-sm-8"><span class="badge <?= $fault['status'] === 'aktif' ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= ucfirst($fault['status']) ?></span></dd>
              <dt class="col-sm-4">Aturan</dt>
              <dd class="col-sm-8"><?= count($rules) ?></dd>
            </dl>
          </div>
          <div class="card-footer"><a href="edit.php?id=<?= (int)$fault['id'] ?>" class="btn btn-warning btn-sm">Edit</a> <a href="index.php" class="btn btn-secondary btn-sm">Kembali</a></div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Gejala dan Aturan Terkait</h3>
          </div>
          <div class="card-body">
            <?php if ($rules): foreach ($rules as $rule): ?><div class="border rounded p-3 mb-3">
                  <h5><?= e($rule['kode']) ?> → <?= e($fault['kode']) ?></h5><?php if ($rule['gejala']): ?><ul class="mb-2"><?php foreach ($rule['gejala'] as $symptom): ?><li><?= e($symptom['kode'] . ' - ' . $symptom['nama']) ?></li><?php endforeach; ?></ul>
                    <p class="mb-0"><strong>Rangkaian gejala:</strong> <?= e(implode(' + ', array_column($rule['gejala'], 'kode'))) ?></p><?php else: ?><p class="text-body-secondary mb-0">Belum ada gejala pada aturan ini.</p><?php endif; ?>
                </div><?php endforeach;
                  else: ?><p class="text-body-secondary mb-0">Belum ada aturan yang mengarah ke kerusakan ini.</p><?php endif; ?>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Solusi Terkait</h3>
          </div>
          <div class="card-body"><?php if ($solutions): ?><ol class="mb-0"><?php foreach ($solutions as $solution): ?><li><?= nl2br(e($solution['deskripsi'])) ?></li><?php endforeach; ?></ol><?php else: ?><p class="text-body-secondary mb-0">Belum ada solusi terkait.</p><?php endif; ?></div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>