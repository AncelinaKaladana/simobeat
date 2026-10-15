<?php require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/redirect.php';
require_once __DIR__ . '/../helpers/flash.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../config/database.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = post_string('nama');
    $email = post_string('email');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirmation'] ?? '';
    if (!$nama || !valid_email($email) || strlen($password) < 8 || $password !== $confirm) $error = 'Periksa nama, email, kata sandi minimal 8 karakter, dan konfirmasi kata sandi.';
    else {
        try {
            $q = $pdo->prepare("INSERT INTO users(nama,email,password,role) VALUES(?,?,?,'user')");
            $q->execute([$nama, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $id = (int)$pdo->lastInsertId();
            $pdo->prepare('INSERT INTO log_aktivitas(user_id,aktivitas) VALUES(?,?)')->execute([$id, 'Registrasi akun']);
            flash('success', 'Pendaftaran berhasil. Silakan masuk.');
            redirect('/auth/login.php');
        } catch (PDOException $e) {
            $error = 'Email sudah terdaftar.';
        }
    }
}
$pageTitle = 'Daftar';
require __DIR__ . '/../includes/header.php'; ?><section class="auth card">
    <h1>Buat akun</h1><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif ?><form method="post" class="form"><label>Nama<input name="nama" required></label><label>Email<input name="email" type="email" required></label><label>Kata sandi<input name="password" type="password" minlength="8" required></label><label>Konfirmasi kata sandi<input name="password_confirmation" type="password" minlength="8" required></label><button class="button">Daftar</button></form>
</section><?php require __DIR__ . '/../includes/footer.php'; ?>