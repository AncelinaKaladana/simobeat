<?php require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/redirect.php';
require_once __DIR__ . '/../helpers/flash.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../config/database.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (attempt_login($pdo, trim($_POST['email'] ?? ''), $_POST['password'] ?? '')) redirect($_SESSION['user']['role'] === 'admin' ? '/admin/dashboard.php' : '/user/dashboard.php');
    $error = 'Email atau kata sandi salah.';
}
$pageTitle = 'Masuk';
$bodyClass = 'auth-page';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/flash-message.php'; ?>
<section class="auth-login" aria-labelledby="login-title">
    <div class="auth-login-story">
        <a class="auth-brand" href="<?= BASE_URL ?>/" aria-label="Beranda EdukasiCode">
            <span class="auth-brand-mark"><i class="bi bi-gear-wide-connected" aria-hidden="true"></i></span>
            <span><strong>EdukasiCode</strong><small>SISTEM PAKAR</small></span>
        </a>
        <div class="auth-story-copy">
            <p class="auth-eyebrow">DIAGNOSIS MOTOR</p>
            <h2>Perawatan dimulai dari memahami gejalanya.</h2>
            <p>Kelola pengetahuan kerusakan, aturan diagnosis, dan riwayat layanan dalam satu tempat.</p>
        </div>
        <div class="auth-story-foot"><i class="bi bi-shield-check me-2" aria-hidden="true"></i>Ruang kerja diagnosis yang terarah</div>
    </div>
    <div class="auth-login-panel">
        <div class="auth-login-heading">
            <p class="auth-eyebrow">PORTAL SISTEM</p>
            <h1 id="login-title">Selamat datang</h1>
            <p>Masuk menggunakan email dan kata sandi akun Anda.</p>
        </div>
        <?php if ($error): ?><div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-circle me-2" aria-hidden="true"></i><?= e($error) ?></div><?php endif; ?>
        <form method="post" class="auth-form">
            <div class="auth-field">
                <label for="login-email">Email</label>
                <div class="auth-input-wrap"><i class="bi bi-envelope" aria-hidden="true"></i><input id="login-email" type="email" name="email" autocomplete="username" placeholder="nama@email.com" required></div>
            </div>
            <div class="auth-field">
                <label for="login-password">Kata sandi</label>
                <div class="auth-input-wrap auth-password-wrap"><i class="bi bi-lock" aria-hidden="true"></i><input id="login-password" type="password" name="password" autocomplete="current-password" placeholder="Masukkan kata sandi" required><button class="auth-password-toggle" type="button" data-password-toggle="login-password" aria-label="Tampilkan kata sandi" title="Tampilkan kata sandi"><i class="bi bi-eye" aria-hidden="true"></i></button></div>
            </div>
            <button class="auth-submit" type="submit"><span>Masuk ke dashboard</span><i class="bi bi-arrow-right" aria-hidden="true"></i></button>
        </form>
        <div class="auth-login-links"><a href="register.php">Buat akun</a><a href="lupa-password.php">Lupa kata sandi?</a></div>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>