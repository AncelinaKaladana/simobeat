# Sistem Pakar Diagnosis Kerusakan Motor

Aplikasi PHP 8+ dan MySQL/MariaDB untuk pengelolaan basis pengetahuan motor, gejala, kerusakan, solusi, aturan Forward Chaining, dan riwayat diagnosis.

## Menjalankan pada XAMPP
1. Letakkan folder pada `C:\\xampp\\htdocs\\edukasicode` sehingga alamat proyek menjadi `http://localhost/edukasicode/`.
2. Nyalakan Apache dan MySQL dari XAMPP.
3. Buka phpMyAdmin, buat database bernama persis `dbs_simobeat`.
4. Pilih database itu, lalu impor `database/database.sql` dan `database/seed.sql` secara berurutan. Seed menambahkan 3 motor, 6 gejala, 3 kerusakan, solusi, serta aturan contoh.
5. Pengaturan XAMPP default ada di `config/koneksi.php`. Kredensial dapat diubah dengan environment `DB_HOST`, `DB_NAME`, `DB_USER`, dan `DB_PASS`.
6. Buat admin awal dengan membuka `/setup-admin.php` satu kali. Akunnya `admin@edukasicode.local` dengan kata sandi awal `admin123`. Setelah berhasil, segera hapus `setup-admin.php`. File setup awal sudah dijalankan dan dihapus dari salinan proyek ini; admin tersebut sudah dibuat pada database lokal.
7. Masuk melalui `/auth/login.php`. Pengguna baru dapat mendaftar; role pendaftaran selalu `user`, dan password disimpan melalui `password_hash()`.

## Modul
- Admin mengelola motor, gejala, kerusakan, solusi, aturan, pengguna, diagnosis, dan laporan.
- Setiap aturan mempunyai satu kerusakan dan beberapa gejala wajib di `aturan_detail`.
- Saat diagnosis, pengguna memilih motor dan menjawab YA/TIDAK untuk tiap gejala. Forward Chaining mencari aturan yang seluruh gejala syaratnya dijawab YA. Diagnosis dan semua jawaban disimpan pada `diagnosis` dan `diagnosis_gejala`.
- Tabel aktivitas mencatat login, registrasi, perubahan data, dan proses diagnosis.

## Catatan
- `BASE_URL` pada `config/app.php` disetel ke `/edukasicode`; ubah jika nama folder berbeda.
- Hasil adalah bantuan awal dan perlu dikonfirmasi oleh mekanik.
- Reset password email belum diaktifkan; hubungi administrator untuk reset manual.
- Aset SB Admin/vendor yang sudah ada tetap disimpan di `assets/`; tampilan aplikasi menggunakan CSS lokal dan tidak menduplikasi pustaka vendor.
