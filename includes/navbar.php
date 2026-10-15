<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <?php if (!empty($_SESSION['user'])): ?><li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Buka atau tutup menu"><i class="bi bi-list"></i></a></li><?php endif; ?>
      <li class="nav-item d-none d-md-block"><a class="nav-link fw-semibold" href="<?= BASE_URL ?>/"><?= e(APP_NAME) ?></a></li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <?php if (!empty($_SESSION['user'])): $currentUser = $_SESSION['user']; ?>
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