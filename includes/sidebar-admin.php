<aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
  <div class="sidebar-brand"><a href="<?= BASE_URL ?>/admin/dashboard.php" class="brand-link"><span class="brand-text fw-semibold">EdukasiCode</span></a></div>
  <div class="sidebar-wrapper">
    <div class="sidebar-app-name px-3 pt-3">Sistem Pakar Diagnosis Motor</div>
    <form class="sidebar-search px-3 pt-3" action="<?= BASE_URL ?>/admin/dashboard.php" method="get" role="search" aria-label="Cari seluruh data">
      <label class="visually-hidden" for="sidebar-global-search">Cari seluruh data</label>
      <div class="input-group input-group-sm">
        <input id="sidebar-global-search" class="form-control" type="search" name="q" maxlength="120" placeholder="Cari semua data..." value="<?= e((string)($_GET['q'] ?? '')) ?>">
        <button class="btn btn-outline-light" type="submit" aria-label="Cari"><i class="bi bi-search" aria-hidden="true"></i></button>
      </div>
    </form>
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/dashboard.php" class="nav-link"><i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a></li>
        <li class="nav-header">MASTER DATA</li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/motor/index.php" class="nav-link"><i class="nav-icon bi bi-bicycle"></i>
            <p>Data Motor</p>
          </a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/gejala/index.php" class="nav-link"><i class="nav-icon bi bi-clipboard2-pulse"></i>
            <p>Data Gejala</p>
          </a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/kerusakan/index.php" class="nav-link"><i class="nav-icon bi bi-tools"></i>
            <p>Data Kerusakan</p>
          </a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/solusi/index.php" class="nav-link"><i class="nav-icon bi bi-lightbulb"></i>
            <p>Data Solusi</p>
          </a></li>
        <li class="nav-header">PENGETAHUAN</li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/aturan/index.php" class="nav-link"><i class="nav-icon bi bi-diagram-3"></i>
            <p>Aturan</p>
          </a></li>
        <li class="nav-header">DIAGNOSIS</li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/diagnosis/index.php" class="nav-link"><i class="nav-icon bi bi-clipboard-check"></i>
            <p>Data Diagnosis</p>
          </a></li>
        <li class="nav-header">SISTEM</li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/pengguna/index.php" class="nav-link"><i class="nav-icon bi bi-people"></i>
            <p>Pengguna</p>
          </a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/admin/laporan/index.php" class="nav-link"><i class="nav-icon bi bi-file-earmark-text"></i>
            <p>Laporan</p>
          </a></li>
      </ul>
    </nav>
  </div>
</aside>