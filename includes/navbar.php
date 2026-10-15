<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <?php if (!empty($_SESSION['user'])): ?><li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Buka atau tutup menu"><i class="bi bi-list"></i></a></li><?php endif; ?>
      <li class="nav-item d-none d-md-block"><a class="nav-link fw-semibold" href="<?= BASE_URL ?>/"><?= e(APP_NAME) ?></a></li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <?php if (!empty($_SESSION['user'])): $currentUser = $_SESSION['user']; ?>
        <?php if (($currentUser['role'] ?? '') === 'admin' && isset($pdo)): ?>
          <?php
          $activityCount = (int)$pdo->query("SELECT COUNT(*) FROM log_aktivitas WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)")->fetchColumn();
          $recentActivity = $pdo->query('SELECT la.aktivitas,la.created_at,COALESCE(u.nama,\'Sistem\') AS nama FROM log_aktivitas la LEFT JOIN users u ON u.id=la.user_id ORDER BY la.created_at DESC,la.id DESC LIMIT 5')->fetchAll();
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link notification-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifikasi aktivitas, <?= $activityCount ?> aktivitas dalam 24 jam terakhir">
              <i class="bi bi-bell" aria-hidden="true"></i>
              <?php if ($activityCount > 0): ?><span class="notification-count"><?= $activityCount > 99 ? '99+' : $activityCount ?></span><?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end notification-menu p-0">
              <div class="notification-heading"><strong>Aktivitas terbaru</strong><span><?= $activityCount ?> dalam 24 jam</span></div>
              <?php if ($recentActivity): ?>
                <div class="notification-list">
                  <?php foreach ($recentActivity as $activity): ?>
                    <div class="notification-item">
                      <span class="notification-icon"><i class="bi bi-activity" aria-hidden="true"></i></span>
                      <span class="notification-copy"><strong><?= e($activity['aktivitas']) ?></strong><small><?= e($activity['nama']) ?> · <?= e(date('d/m H:i', strtotime($activity['created_at']))) ?></small></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p class="notification-empty">Belum ada aktivitas tercatat.</p>
              <?php endif; ?>
              <a class="notification-footer" href="<?= BASE_URL ?>/admin/dashboard.php">Buka dashboard <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div>
          </li>
        <?php endif; ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle me-1"></i><?= e($currentUser['nama'] ?? 'Pengguna') ?></a>
          <ul class="dropdown-menu dropdown-menu-end">
            <?php if (($currentUser['role'] ?? '') === 'admin'): ?><li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li><?php else: ?><li><a class="dropdown-item" href="<?= BASE_URL ?>/user/profil.php"><i class="bi bi-person me-2"></i>Profil</a></li><?php endif; ?>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="<?= BASE_URL ?>/auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
          </ul>
        </li>
      <?php else: ?><li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/login.php">Masuk</a></li><?php endif; ?>
    </ul>
  </div>
</nav>