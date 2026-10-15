<?php
require_once __DIR__ . '/../../includes/admin-check.php';
$page_title = 'Tambah Motor';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php';
?>
<section class="app-content-header"><div class="container-fluid"><div class="row align-items-center">
  <div class="col-sm-6"><h3 class="mb-0">Tambah Motor</h3></div>
  <div class="col-sm-6"><ol class="breadcrumb float-sm-end mb-0"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Motor</a></li><li class="breadcrumb-item active" aria-current="page">Tambah</li></ol></div>
</div></div></section>
<section class="content"><div class="container-fluid"><div class="card">
  <div class="card-header"><h3 class="card-title">Form Tambah Motor</h3></div>
  <form action="simpan.php" method="post">
    <?= csrf_field() ?>
    <div class="card-body">
      <div class="mb-3"><label for="kode" class="form-label">Kode Motor</label><input id="kode" type="text" name="kode" class="form-control" placeholder="Contoh: M004" maxlength="20" required></div>
      <div class="mb-3"><label for="merek" class="form-label">Merk</label><input id="merek" type="text" name="merek" class="form-control" placeholder="Contoh: Honda" maxlength="100" required></div>
      <div class="mb-3"><label for="nama" class="form-label">Model</label><input id="nama" type="text" name="nama" class="form-control" placeholder="Contoh: BeAT Deluxe" maxlength="120" required></div>
      <div class="mb-3"><label for="tipe" class="form-label">Jenis</label><input id="tipe" type="text" name="tipe" class="form-control" placeholder="Contoh: Matic" maxlength="100" required></div>
      <div class="mb-3"><label for="tahun" class="form-label">Tahun</label><input id="tahun" type="number" name="tahun" class="form-control" placeholder="Contoh: 2023" min="1900" max="2100"></div>
      <div class="mb-3"><label for="deskripsi" class="form-label">Deskripsi</label><textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" placeholder="Informasi tambahan tentang motor (opsional)"></textarea></div>
    </div>
    <div class="card-footer"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button><a href="index.php" class="btn btn-secondary ms-1">Kembali</a></div>
  </form>
</div></div></section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
