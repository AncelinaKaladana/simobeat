<?php
require_once __DIR__ . '/../../includes/admin-check.php';

$id = (int)($_GET['id'] ?? 0);
$query = $pdo->prepare('SELECT id, kode, nama, deskripsi, status FROM kerusakan WHERE id = ?');
$query->execute([$id]);
$data = $query->fetch();

if (!$data) {
    http_response_code(404);
    exit('Data kerusakan tidak ditemukan.');
}

$page_title = 'Edit Kerusakan';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php';
?>
<section class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center g-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Kerusakan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Kerusakan</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0">Form Edit Kerusakan</h3>
            </div>
            <form method="post" action="update.php" class="form">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$data['id'] ?>">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Kerusakan</label>
                        <input id="kode" class="form-control" value="<?= e($data['kode']) ?>" readonly>
                        <div class="form-text">Kode dibuat otomatis dan tidak dapat diubah.</div>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kerusakan</label>
                        <input id="nama" class="form-control" name="nama" value="<?= e($data['nama']) ?>" maxlength="180" required>
                        <div class="form-text">Maksimal 180 karakter.</div>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" class="form-control" name="deskripsi" rows="5" placeholder="Jelaskan ciri atau penyebab kerusakan"><?= e($data['deskripsi'] ?? '') ?></textarea>
                        <div class="form-text">Maksimal 5.000 karakter.</div>
                    </div>
                    <div class="mb-0">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" class="form-select" name="status">
                            <option value="aktif" <?= $data['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $data['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Perbarui</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>