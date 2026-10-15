<?php
require_once __DIR__ . '/../../includes/admin-check.php';

$search = trim((string)($_GET['q'] ?? ''));
$statusFilter = (string)($_GET['status'] ?? '');
$ruleFilter = (string)($_GET['aturan'] ?? '');
$perPage = max(1, min(100, (int)($_GET['per_page'] ?? 10)));
$page = max(1, (int)($_GET['page'] ?? 1));

$sql = 'SELECT k.id, k.kode, k.nama, k.deskripsi, k.status,
               COUNT(DISTINCT a.id) jumlah_aturan,
               COUNT(DISTINCT ad.gejala_id) jumlah_gejala,
               COUNT(DISTINCT s.id) jumlah_solusi
        FROM kerusakan k
        LEFT JOIN aturan a ON a.kerusakan_id = k.id
        LEFT JOIN aturan_detail ad ON ad.aturan_id = a.id
        LEFT JOIN solusi s ON s.kerusakan_id = k.id
        WHERE 1 = 1';
$params = [];

if ($search !== '') {
    $sql .= ' AND (k.kode LIKE ? OR k.nama LIKE ? OR k.deskripsi LIKE ?)';
    $like = '%' . $search . '%';
    $params = array_merge($params, [$like, $like, $like]);
}

if (in_array($statusFilter, ['aktif', 'nonaktif'], true)) {
    $sql .= ' AND k.status = ?';
    $params[] = $statusFilter;
}

if ($ruleFilter === 'ada') {
    $sql .= ' HAVING COUNT(DISTINCT a.id) > 0';
} elseif ($ruleFilter === 'belum') {
    $sql .= ' HAVING COUNT(DISTINCT a.id) = 0';
}

$sql .= ' GROUP BY k.id ORDER BY k.id DESC';
$countQuery = $pdo->prepare('SELECT COUNT(*) FROM (' . $sql . ') AS data');
$countQuery->execute($params);
$totalRows = (int)$countQuery->fetchColumn();
$totalPages = max(1, (int)ceil($totalRows / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$query = $pdo->prepare($sql . ' LIMIT ' . $perPage . ' OFFSET ' . $offset);
$query->execute($params);
$rows = $query->fetchAll();

$stats = $pdo->query('SELECT COUNT(*) total, SUM(status = "aktif") aktif, SUM(status = "nonaktif") nonaktif, (SELECT COUNT(*) FROM aturan) aturan FROM kerusakan')->fetch();
$page_title = 'Data Kerusakan';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/flash-message.php';
?>
<section class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center g-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Data Kerusakan</h3>
                <p class="text-muted mb-0">Pengelolaan sistem diagnosis dan aturan motor.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kerusakan</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total kerusakan</span>
                        <span class="badge text-bg-primary fs-5"><?= (int)($stats['total'] ?? 0) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="text-muted">Aktif</span>
                        <span class="badge text-bg-success fs-5"><?= (int)($stats['aktif'] ?? 0) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="text-muted">Nonaktif</span>
                        <span class="badge text-bg-secondary fs-5"><?= (int)($stats['nonaktif'] ?? 0) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3 d-none d-md-block">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="text-muted">Aturan</span>
                        <span class="badge text-bg-danger fs-5"><?= (int)($stats['aturan'] ?? 0) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h3 class="card-title mb-0">Daftar Kerusakan</h3>
                </div>
                <a href="tambah.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>Tambah</a>
            </div>
            <div class="card-body">
                <form method="get" class="row g-2 mb-3" id="filter-form">
                    <div class="col-lg-4">
                        <label class="visually-hidden" for="q">Cari kerusakan</label>
                        <input id="q" class="form-control" type="search" name="q" placeholder="Cari kode, nama, atau deskripsi..." value="<?= e($search) ?>">
                    </div>
                    <div class="col-lg-2">
                        <label class="visually-hidden" for="status">Filter status</label>
                        <select id="status" class="form-select" name="status">
                            <option value="">Semua status</option>
                            <option value="aktif" <?= $statusFilter === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $statusFilter === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="visually-hidden" for="aturan">Filter aturan</label>
                        <select id="aturan" class="form-select" name="aturan">
                            <option value="">Semua aturan</option>
                            <option value="ada" <?= $ruleFilter === 'ada' ? 'selected' : '' ?>>Sudah dipakai</option>
                            <option value="belum" <?= $ruleFilter === 'belum' ? 'selected' : '' ?>>Belum dipakai</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1" type="submit"><i class="bi bi-search me-1"></i>Cari</button>
                        <a href="index.php" class="btn btn-outline-secondary" title="Reset filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                    <div class="col-lg-2">
                        <label class="visually-hidden" for="per_page">Jumlah baris</label>
                        <select id="per_page" class="form-select" name="per_page" onchange="document.getElementById('filter-form').submit()">
                            <?php foreach ([5, 10, 25, 50] as $option): ?>
                                <option value="<?= $option ?>" <?= $perPage === $option ? 'selected' : '' ?>><?= $option ?> baris</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode</th>
                                <th scope="col">Kerusakan</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Gejala</th>
                                <th scope="col">Solusi</th>
                                <th scope="col">Aturan</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($rows): $number = $offset + 1;
                            foreach ($rows as $row): ?>
                                <tr>
                                    <td class="text-muted"><?= $number++ ?></td>
                                    <td><span class="badge text-bg-danger"><?= e($row['kode']) ?></span></td>
                                    <td><strong><?= e($row['nama']) ?></strong></td>
                                    <td class="text-truncate" style="max-width: 220px;" title="<?= e($row['deskripsi'] ?? '') ?>"><?= e($row['deskripsi'] ?? '-') ?></td>
                                    <td><?= (int)$row['jumlah_gejala'] ?></td>
                                    <td><?= (int)$row['jumlah_solusi'] ?></td>
                                    <td><?= (int)$row['jumlah_aturan'] ?></td>
                                    <td>
                                        <span class="badge <?= $row['status'] === 'aktif' ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a class="btn btn-info btn-sm" href="detail.php?id=<?= (int)$row['id'] ?>" title="Detail"><i class="bi bi-eye"></i></a>
                                        <a class="btn btn-warning btn-sm" href="edit.php?id=<?= (int)$row['id'] ?>" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form class="d-inline" method="post" action="hapus.php" onsubmit="return confirm('Hapus kerusakan dan data terkait?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                            <button class="btn btn-danger btn-sm" type="submit" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-search display-1 d-block mb-2"></i>
                                        Tidak ada kerusakan yang cocok dengan filter.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalRows > $perPage): ?>
                    <nav class="mt-3" aria-label="Navigasi halaman">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($statusFilter) ?>&aturan=<?= urlencode($ruleFilter) ?>&per_page=<?= $perPage ?>">Sebelum</a>
                            </li>
                            <li class="page-item active"><span class="page-link"><?= $page ?> / <?= $totalPages ?></span></li>
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($statusFilter) ?>&aturan=<?= urlencode($ruleFilter) ?>&per_page=<?= $perPage ?>">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>