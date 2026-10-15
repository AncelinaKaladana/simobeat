<?php require_once __DIR__ . '/../../includes/admin-check.php';
$page_title = 'Tambah Gejala';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php'; ?>
<section class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah Gejala</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Gejala</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Gejala</h3>
            </div>
            <form action="simpan.php" method="post"><?= csrf_field() ?><div class="card-body">
                    <div class="mb-3"><label for="kode" class="form-label">Kode Gejala</label><input id="kode" type="text" name="kode" class="form-control" placeholder="Contoh: G007" maxlength="20" required>
                        <div class="form-text">Gunakan kode unik untuk setiap gejala.</div>
                    </div>
                    <div class="mb-3"><label for="nama" class="form-label">Nama Gejala</label><input id="nama" type="text" name="nama" class="form-control" placeholder="Contoh: Starter elektrik tidak berfungsi" maxlength="180" required></div>
                    <div class="mb-3"><label for="deskripsi" class="form-label">Keterangan</label><textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" placeholder="Masukkan keterangan gejala (opsional)"></textarea></div>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button> <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>