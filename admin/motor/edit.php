<?php
require_once __DIR__ . '/../../includes/admin-check.php';
$id = (int)($_GET['id'] ?? 0);
$q = $pdo->prepare('SELECT * FROM motor WHERE id = ?');
$q->execute([$id]);
$data = $q->fetch();
if (!$data) { http_response_code(404); exit('Data motor tidak ditemukan.'); }
$page_title = 'Edit Motor';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php';
?>
<section class="app-content-header"><div class="container-fluid"><div class="row align-items-center">
  <div class="col-sm-6"><h3 class="mb-0">Edit Motor</h3></div>
  <div class="col-sm-6"><ol class="breadcrumb float-sm-end mb-0"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Motor</a></li><li class="breadcrumb-item active" aria-current="page">Edit</li></ol></div>
</div></div></section>
<section class="content"><div class="container-fluid"><div class="card">
  <div class="card-header"><h3 class="card-title">Form Edit Motor</h3></div>
  <form action="update.php" method="post">
    <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$data['id'] ?>">
    <div class="card-body">
      <div class="mb-3"><label for="kode" class="form-label">Kode Motor</label><input id="kode" type="text" name="kode" class="form-control" value="<?= e((string)$data['kode']) ?>" maxlength="20" required></div>
      <div class="mb-3"><label for="merek" class="form-label">Merk</label><input id="merek" type="text" name="merek" class="form-control" value="<?= e((string)$data['merek']) ?>" maxlength="100" required></div>
      <div class="mb-3"><label for="nama" class="form-label">Model</label><input id="nama" type="text" name="nama" class="form-control" value="<?= e((string)$data['nama']) ?>" maxlength="120" required></div>
      <div class="mb-3"><label for="tipe" class="form-label">Jenis</label><input id="tipe" type="text" name="tipe" class="form-control" value="<?= e((string)($data['tipe'] ?? '')) ?>" maxlength="100" required></div>
      <div class="mb-3"><label for="tahun" class="form-label">Tahun</label><input id="tahun" type="number" name="tahun" class="form-control" value="<?= e((string)($data['tahun'] ?? '')) ?>" min="1900" max="2100"></div>
      <div class="mb-3"><label for="deskripsi" class="form-label">Deskripsi</label><textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= e((string)($data['deskripsi'] ?? '')) ?></textarea></div>
    </div>
    <div class="card-footer"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button><a href="index.php" class="btn btn-secondary ms-1">Kembali</a></div>
  </form>
</div></div></section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
