<?php
require_once __DIR__ . '/../../includes/admin-check.php';

$page_title = 'Tambah Kerusakan';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php';
?>
<section class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center g-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah Kerusakan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Kerusakan</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0">Form Tambah Kerusakan</h3>
            </div>
            <form method="post" action="simpan.php" class="form">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-upc-scan me-1"></i>Kode kerusakan dibuat otomatis saat data disimpan.
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kerusakan</label>
                        <input id="nama" class="form-control" name="nama" maxlength="180" placeholder="Contoh: Kerusakan Aki" required>
                        <div class="form-text">Maksimal 180 karakter.</div>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" class="form-control" name="deskripsi" rows="5" placeholder="Jelaskan ciri atau penyebab kerusakan"></textarea>
                        <div class="form-text">Maksimal 5.000 karakter.</div>
                    </div>
                    <div class="mb-0">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" class="form-select" name="status">
                            <option value="aktif" selected>Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>