<?php
require_once __DIR__ . '/../includes/admin-check.php';
$page_title = 'Dashboard Admin';
$counts = [];
foreach (['motor' => 'Data Motor', 'gejala' => 'Data Gejala', 'kerusakan' => 'Data Kerusakan', 'solusi' => 'Solusi', 'aturan' => 'Aturan', 'diagnosis' => 'Diagnosis'] as $table => $label) {
    $counts[$table] = ['label' => $label, 'value' => (int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn()];
}
require __DIR__ . '/../includes/header.php';
?>
<section class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">Dashboard</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <?php $colors = ['motor' => 'primary', 'gejala' => 'warning', 'kerusakan' => 'danger', 'solusi' => 'success', 'aturan' => 'info', 'diagnosis' => 'secondary'];
    $links = ['motor' => 'motor', 'gejala' => 'gejala', 'kerusakan' => 'kerusakan', 'solusi' => 'solusi', 'aturan' => 'aturan', 'diagnosis' => 'diagnosis'];
    $icons = ['motor' => 'bicycle', 'gejala' => 'clipboard2-pulse', 'kerusakan' => 'tools', 'solusi' => 'lightbulb', 'aturan' => 'diagram-3', 'diagnosis' => 'clipboard-check'];
    foreach ($counts as $key => $item): ?>
        <div class="col-lg-4 col-md-6">
            <div class="card text-bg-<?= e($colors[$key]) ?> mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title"><?= e($item['label']) ?></h5>
                            <p class="display-6 mb-0"><?= $item['value'] ?></p>
                        </div><i class="bi bi-<?= e($icons[$key]) ?> fs-1 opacity-50" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="card-footer"><a class="link-light text-decoration-none" href="<?= BASE_URL ?>/admin/<?= e($links[$key]) ?>/index.php">Kelola data <i class="bi bi-arrow-right"></i></a></div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-info-circle me-2"></i>Sistem Pakar Kerusakan Sepeda Motor</h3>
    </div>
    <div class="card-body">
        <p>Selamat datang, <strong><?= e($_SESSION['user']['nama']) ?></strong>.</p>
        <p class="mb-0">Kelola data motor, gejala, kerusakan, solusi, dan aturan pengetahuan melalui menu di samping.</p>
    </div>
</div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>