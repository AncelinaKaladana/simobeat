<?php
require_once __DIR__ . '/../includes/admin-check.php';
$page_title = 'Dashboard Admin';
$search = trim((string)($_GET['q'] ?? ''));
$counts = [];
foreach (['motor' => 'Data Motor', 'gejala' => 'Data Gejala', 'kerusakan' => 'Data Kerusakan', 'solusi' => 'Solusi', 'aturan' => 'Aturan', 'diagnosis' => 'Diagnosis', 'users' => 'Pengguna'] as $table => $label) {
    $counts[$table] = ['label' => $label, 'value' => (int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn()];
}
$searchResults = [];
if ($search !== '') {
    $like = '%' . $search . '%';
    $searchQueries = [
        ['type' => 'Motor', 'route' => '/admin/motor/edit.php?id=', 'sql' => "SELECT id,kode AS code,nama AS title,CONCAT_WS(' · ',merek,tipe,tahun,deskripsi) AS detail FROM motor WHERE CONCAT_WS(' ',kode,nama,merek,tipe,tahun,deskripsi) LIKE ? ORDER BY nama LIMIT 6"],
        ['type' => 'Gejala', 'route' => '/admin/gejala/edit.php?id=', 'sql' => "SELECT id,kode AS code,nama AS title,deskripsi AS detail FROM gejala WHERE CONCAT_WS(' ',kode,nama,deskripsi) LIKE ? ORDER BY kode LIMIT 6"],
        ['type' => 'Kerusakan', 'route' => '/admin/kerusakan/detail.php?id=', 'sql' => "SELECT id,kode AS code,nama AS title,CONCAT_WS(' · ',deskripsi,status) AS detail FROM kerusakan WHERE CONCAT_WS(' ',kode,nama,deskripsi,status) LIKE ? ORDER BY kode LIMIT 6"],
        ['type' => 'Solusi', 'route' => '/admin/solusi/edit.php?id=', 'sql' => "SELECT s.id,k.kode AS code,k.nama AS title,s.deskripsi AS detail FROM solusi s JOIN kerusakan k ON k.id=s.kerusakan_id WHERE CONCAT_WS(' ',k.kode,k.nama,s.deskripsi) LIKE ? ORDER BY s.id DESC LIMIT 6"],
        ['type' => 'Aturan', 'route' => '/admin/aturan/detail.php?id=', 'sql' => "SELECT a.id,a.kode AS code,CONCAT(a.kode,' · ',k.nama) AS title,CONCAT('Status: ',a.status,' · Gejala: ',COALESCE(GROUP_CONCAT(DISTINCT g.nama ORDER BY g.nama SEPARATOR ', '),'Belum ada gejala')) AS detail FROM aturan a JOIN kerusakan k ON k.id=a.kerusakan_id LEFT JOIN aturan_detail ad ON ad.aturan_id=a.id LEFT JOIN gejala g ON g.id=ad.gejala_id WHERE CONCAT_WS(' ',a.kode,k.kode,k.nama,g.kode,g.nama) LIKE ? GROUP BY a.id,k.id ORDER BY a.kode LIMIT 6"],
        ['type' => 'Pengguna', 'route' => '/admin/pengguna/detail.php?id=', 'sql' => "SELECT id,'' AS code,nama AS title,CONCAT(email,' · ',role) AS detail FROM users WHERE CONCAT_WS(' ',nama,email,role) LIKE ? ORDER BY nama LIMIT 6"],
        ['type' => 'Diagnosis', 'route' => '/admin/diagnosis/detail.php?id=', 'sql' => "SELECT d.id,CONCAT('D-',d.id) AS code,COALESCE(k.nama,d.hasil) AS title,CONCAT_WS(' · ',u.nama,CONCAT(m.merek,' ',m.nama),DATE_FORMAT(d.created_at,'%d-%m-%Y')) AS detail FROM diagnosis d JOIN users u ON u.id=d.user_id JOIN motor m ON m.id=d.motor_id LEFT JOIN kerusakan k ON k.id=d.kerusakan_id WHERE CONCAT_WS(' ',d.hasil,u.nama,u.email,m.kode,m.nama,k.kode,k.nama) LIKE ? ORDER BY d.created_at DESC,d.id DESC LIMIT 6"],
    ];
    foreach ($searchQueries as $query) {
        $statement = $pdo->prepare($query['sql']);
        $statement->execute([$like]);
        foreach ($statement->fetchAll() as $result) {
            $result['type'] = $query['type'];
            $result['url'] = BASE_URL . $query['route'] . (int)$result['id'];
            $searchResults[] = $result;
        }
    }
}
$recentDiagnoses = $pdo->query("SELECT d.id,d.created_at,d.hasil,u.nama AS user_name,m.nama AS motor_name,m.merek,COALESCE(k.nama,d.hasil) AS diagnosis_name FROM diagnosis d JOIN users u ON u.id=d.user_id JOIN motor m ON m.id=d.motor_id LEFT JOIN kerusakan k ON k.id=d.kerusakan_id ORDER BY d.created_at DESC,d.id DESC LIMIT 6")->fetchAll();
$quality = $pdo->query("SELECT
    (SELECT COUNT(*) FROM gejala g WHERE NOT EXISTS (SELECT 1 FROM aturan_detail ad WHERE ad.gejala_id=g.id)) AS symptoms_without_rules,
    (SELECT COUNT(*) FROM kerusakan k WHERE NOT EXISTS (SELECT 1 FROM aturan a WHERE a.kerusakan_id=k.id AND a.status='aktif')) AS faults_without_rules,
    (SELECT COUNT(*) FROM aturan a WHERE a.status='aktif' AND NOT EXISTS (SELECT 1 FROM aturan_detail ad WHERE ad.aturan_id=a.id)) AS rules_without_symptoms,
    (SELECT COUNT(*) FROM kerusakan k WHERE NOT EXISTS (SELECT 1 FROM solusi s WHERE s.kerusakan_id=k.id)) AS faults_without_solutions")->fetch();
$qualityChecks = [
    ['count' => (int)$quality['symptoms_without_rules'], 'label' => 'Gejala belum dipakai aturan', 'url' => BASE_URL . '/admin/gejala/index.php', 'icon' => 'clipboard2-pulse'],
    ['count' => (int)$quality['faults_without_rules'], 'label' => 'Kerusakan tanpa aturan aktif', 'url' => BASE_URL . '/admin/kerusakan/index.php', 'icon' => 'tools'],
    ['count' => (int)$quality['rules_without_symptoms'], 'label' => 'Aturan aktif tanpa gejala', 'url' => BASE_URL . '/admin/aturan/index.php', 'icon' => 'diagram-3'],
    ['count' => (int)$quality['faults_without_solutions'], 'label' => 'Kerusakan tanpa solusi', 'url' => BASE_URL . '/admin/solusi/index.php', 'icon' => 'lightbulb'],
];
require __DIR__ . '/../includes/header.php';
?>
<section class="app-content-header dashboard-heading">
<section class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-7">
                <h3 class="mb-1">Dashboard</h3>
                <p class="text-body-secondary mb-0">Ringkasan operasional sistem pakar sepeda motor.</p>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card dashboard-search mb-4">
            <div class="card-body">
                <form method="get" role="search" aria-label="Pencarian seluruh data dashboard">
                    <label class="form-label fw-semibold" for="dashboard-search">Cari seluruh data</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-body"><i class="bi bi-search" aria-hidden="true"></i></span>
                        <input id="dashboard-search" class="form-control" type="search" name="q" maxlength="120" value="<?= e($search) ?>" placeholder="Motor, gejala, kerusakan, solusi, aturan, pengguna, diagnosis...">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
                <?php if ($search !== ''): ?>
                    <div class="mt-3" aria-live="polite">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="h6 mb-0">Hasil untuk “<?= e($search) ?>”</h4>
                            <a class="small" href="<?= BASE_URL ?>/admin/dashboard.php">Hapus pencarian</a>
                        </div>
                        <?php if ($searchResults): ?>
                            <div class="dashboard-search-results">
                                <?php foreach ($searchResults as $result): ?>
                                    <a class="dashboard-search-result" href="<?= e($result['url']) ?>">
                                        <span class="badge text-bg-light border"><?= e($result['type']) ?></span>
                                        <span class="dashboard-result-copy"><strong><?= e(trim($result['code'] . ' ' . $result['title'])) ?></strong><small><?= e((string)($result['detail'] ?? '')) ?></small></span>
                                        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                                    </a>
                                <?php endforeach ?>
                            </div>
                            <p class="small text-body-secondary mb-0 mt-2">Maksimal 6 hasil ditampilkan untuk setiap kategori.</p>
                        <?php else: ?>
                            <p class="text-body-secondary mb-0">Tidak ada data yang cocok. Coba kata kunci lain.</p>
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <?php $colors = ['motor' => 'primary', 'gejala' => 'warning', 'kerusakan' => 'danger', 'solusi' => 'success', 'aturan' => 'info', 'diagnosis' => 'secondary', 'users' => 'dark'];
            $links = ['motor' => 'motor', 'gejala' => 'gejala', 'kerusakan' => 'kerusakan', 'solusi' => 'solusi', 'aturan' => 'aturan', 'diagnosis' => 'diagnosis', 'users' => 'pengguna'];
            $icons = ['motor' => 'bicycle', 'gejala' => 'clipboard2-pulse', 'kerusakan' => 'tools', 'solusi' => 'lightbulb', 'aturan' => 'diagram-3', 'diagnosis' => 'clipboard-check', 'users' => 'people'];
            foreach ($counts as $key => $item): ?>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card dashboard-stat dashboard-stat-<?= e($colors[$key]) ?> h-100 mb-0">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-body-secondary mb-1"><?= e($item['label']) ?></p>
                                <p class="h2 fw-bold mb-0"><?= $item['value'] ?></p>
                            </div>
                            <i class="bi bi-<?= e($icons[$key]) ?> fs-1 opacity-50" aria-hidden="true"></i>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0"><a class="text-decoration-none" href="<?= BASE_URL ?>/admin/<?= e($links[$key]) ?>/index.php">Buka data <i class="bi bi-arrow-right"></i></a></div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center gap-2">
                        <h3 class="card-title"><i class="bi bi-clock-history me-2"></i>Diagnosis terbaru</h3>
                        <a class="btn btn-outline-primary btn-sm" href="<?= BASE_URL ?>/admin/diagnosis/index.php">Semua diagnosis</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead><tr><th>Waktu</th><th>Pengguna</th><th>Motor</th><th>Hasil</th><th><span class="visually-hidden">Aksi</span></th></tr></thead>
                                <tbody>
                                    <?php if ($recentDiagnoses): foreach ($recentDiagnoses as $diagnosis): ?>
                                        <tr>
                                            <td class="text-nowrap"><?= e(date('d/m/Y H:i', strtotime($diagnosis['created_at']))) ?></td>
                                            <td><?= e($diagnosis['user_name']) ?></td>
                                            <td><?= e($diagnosis['merek'] . ' ' . $diagnosis['motor_name']) ?></td>
                                            <td><?= e($diagnosis['diagnosis_name']) ?></td>
                                            <td><a class="btn btn-light btn-sm" href="<?= BASE_URL ?>/admin/diagnosis/detail.php?id=<?= (int)$diagnosis['id'] ?>" aria-label="Lihat diagnosis"><i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center text-body-secondary py-4">Belum ada riwayat diagnosis.</td></tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-shield-check me-2"></i>Kualitas basis pengetahuan</h3></div>
                    <div class="card-body">
                        <p class="small text-body-secondary">Pemeriksaan otomatis untuk membantu menjaga hasil diagnosis tetap didukung aturan dan solusi.</p>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($qualityChecks as $check): ?>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center gap-2">
                                    <a class="text-decoration-none" href="<?= e($check['url']) ?>"><i class="bi bi-<?= e($check['icon']) ?> me-2" aria-hidden="true"></i><?= e($check['label']) ?></a>
                                    <span class="badge rounded-pill <?= $check['count'] > 0 ? 'text-bg-warning' : 'text-bg-success' ?>"><?= $check['count'] ?></span>
                                </li>
                            <?php endforeach ?>
                        </ul>
                        <div class="small text-body-secondary mt-3"><i class="bi bi-person-check me-1" aria-hidden="true"></i>Selamat datang, <?= e($_SESSION['user']['nama']) ?>.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>