<?php
require_once __DIR__ . '/../../includes/admin-check.php';
$rows = $pdo->query('SELECT * FROM motor ORDER BY id DESC')->fetchAll();
$page_title = 'Data Motor';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php';
?>
<section class="app-content-header">
  <div class="container-fluid"><div class="row align-items-center">
    <div class="col-sm-6"><h3 class="mb-0">Data Motor</h3></div>
    <div class="col-sm-6"><ol class="breadcrumb float-sm-end mb-0"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li><li class="breadcrumb-item active" aria-current="page">Motor</li></ol></div>
  </div></div>
</section>
<section class="content"><div class="container-fluid">
  <div class="card">
    <div class="card-header"><h3 class="card-title">Daftar Motor</h3><div class="card-tools"><a href="tambah.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>Tambah Motor</a></div></div>
    <div class="card-body"><div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead><tr><th scope="col" style="width:60px">No.</th><th scope="col">Kode</th><th scope="col">Merk</th><th scope="col">Model</th><th scope="col">Jenis</th><th scope="col">Tahun</th><th scope="col">Deskripsi</th><th scope="col" style="width:160px">Aksi</th></tr></thead>
        <tbody>
        <?php if ($rows): $no = 1; foreach ($rows as $row): ?>
          <tr>
            <td><?= $no++ ?></td><td><?= e((string)$row['kode']) ?></td><td><?= e((string)$row['merek']) ?></td><td><?= e((string)$row['nama']) ?></td><td><?= e((string)($row['tipe'] ?? '')) ?></td><td><?= e((string)($row['tahun'] ?? '')) ?></td><td><?= e((string)($row['deskripsi'] ?? '')) ?></td>
            <td class="text-nowrap"><a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
              <form class="d-inline" method="post" action="hapus.php" onsubmit="return confirm('Yakin ingin menghapus data motor ini?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash me-1"></i>Hapus</button></form>
            </td>
          </tr>
        <?php endforeach; else: ?><tr><td colspan="8" class="text-center text-body-secondary py-4">Belum ada data motor.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div></div>
  </div>
</div></section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
