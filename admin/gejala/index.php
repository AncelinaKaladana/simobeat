<?php
require_once __DIR__ . '/../../includes/admin-check.php';
$rows = $pdo->query('SELECT * FROM gejala ORDER BY id DESC')->fetchAll();
$page_title = 'Data Gejala';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php';
?>
<section class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="mb-0">Data Gejala</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end mb-0">
          <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Gejala</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Gejala</h3>
        <div class="card-tools"><a href="tambah.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>Tambah Gejala</a></div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
              <tr>
                <th style="width:60px">No</th>
                <th style="width:120px">Kode</th>
                <th>Nama Gejala</th>
                <th>Keterangan</th>
                <th style="width:180px">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($rows): $no = 1;
                foreach ($rows as $row): ?><tr>
                    <td><?= $no++ ?></td>
                    <td><span class="badge text-bg-primary"><?= e($row['kode']) ?></span></td>
                    <td><?= e($row['nama']) ?></td>
                    <td><?= e($row['deskripsi'] ?? '-') ?></td>
                    <td class="text-nowrap"><a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
                      <form class="d-inline" method="post" action="hapus.php" onsubmit="return confirm('Yakin ingin menghapus gejala ini?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash me-1"></i>Hapus</button></form>
                    </td>
                  </tr><?php endforeach;
                    else: ?><tr>
                  <td colspan="5" class="text-center text-body-secondary py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data gejala.</td>
                </tr><?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>