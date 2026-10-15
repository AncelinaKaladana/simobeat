# SIMOBEAT

SIMOBEAT adalah aplikasi sistem pakar berbasis web untuk membantu pengguna mengenali kemungkinan kerusakan sepeda motor dari gejala yang dialami. Aplikasi menyediakan konsultasi diagnosis, saran solusi, penyimpanan riwayat, serta halaman administrasi untuk mengelola basis pengetahuan.

> Hasil diagnosis merupakan bantuan awal, bukan pengganti pemeriksaan langsung oleh mekanik. Pastikan kondisi kendaraan dikonfirmasi oleh tenaga ahli, terutama jika berkaitan dengan keselamatan berkendara.

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Persyaratan](#persyaratan)
- [Instalasi Lokal](#instalasi-lokal)
- [Menyiapkan Akun Administrator](#menyiapkan-akun-administrator)
- [Panduan Penggunaan](#panduan-penggunaan)
- [Cara Kerja Diagnosis](#cara-kerja-diagnosis)
- [Struktur Basis Data](#struktur-basis-data)
- [Struktur Direktori](#struktur-direktori)
- [Konfigurasi dan Pemecahan Masalah](#konfigurasi-dan-pemecahan-masalah)
- [Batasan Saat Ini](#batasan-saat-ini)

## Fitur

### Pengguna

- Mendaftar dan masuk menggunakan akun.
- Memilih data motor yang akan diperiksa.
- Menjawab pertanyaan gejala satu per satu dengan pilihan **YA** atau **TIDAK**.
- Melihat hasil diagnosis, gejala yang dijawab, deskripsi kerusakan, dan solusi yang tersedia.
- Melihat kembali detail hasil diagnosis dan riwayat konsultasi.
- Mengelola informasi profil akun.

### Administrator

- Melihat ringkasan pada dashboard administrasi.
- Mengelola data motor, gejala, jenis kerusakan, solusi, dan aturan diagnosis.
- Mengaktifkan atau menonaktifkan aturan dan jenis kerusakan.
- Melihat data pengguna dan detail diagnosis.
- Melihat rekap laporan diagnosis dan mencetak laporan.

### Pencatatan

- Hasil diagnosis dan jawaban gejala disimpan agar dapat ditinjau kembali.
- Aktivitas aplikasi dicatat pada tabel log aktivitas sesuai proses yang tersedia di aplikasi.

## Teknologi

- PHP dengan PDO untuk akses database.
- MySQL atau MariaDB dengan storage engine InnoDB.
- HTML, CSS, dan JavaScript untuk antarmuka web.
- Aset antarmuka dan pustaka lokal tersedia di direktori `assets/`.

## Persyaratan

- Web server yang mendukung PHP, misalnya Apache pada XAMPP.
- PHP dengan ekstensi PDO MySQL aktif.
- MySQL atau MariaDB aktif.
- Browser web modern.

## Instalasi Lokal

Contoh berikut menggunakan XAMPP pada Windows.

1. Salin atau clone proyek ke direktori web server, misalnya:

   ```text
   C:\xampp\htdocs\simobeat
   ```

2. Jalankan Apache dan MySQL dari XAMPP Control Panel.

3. Buat database dan tabel dengan mengimpor berkas `database/database.sql` melalui phpMyAdmin atau klien MySQL. Berkas tersebut membuat database `dbs_simobeat` dan seluruh tabel yang diperlukan.

4. (Opsional) Impor `database/seed.sql` untuk mengisi data awal motor, gejala, kerusakan, solusi, serta aturan diagnosis contoh. Data awal ini dapat digunakan untuk mencoba alur diagnosis.

5. Periksa konfigurasi database pada `config/koneksi.php`. Nilai bawaan yang digunakan adalah:

   | Konfigurasi | Nilai bawaan |
   |---|---|
   | Host | `127.0.0.1` |
   | Database | `dbs_simobeat` |
   | Pengguna | `root` |
   | Kata sandi | kosong |

   Jika konfigurasi server berbeda, atur variabel lingkungan `DB_HOST`, `DB_NAME`, `DB_USER`, dan `DB_PASS`. Jangan menyimpan kredensial produksi ke repositori.

6. Pastikan `BASE_URL` di `config/app.php` sesuai dengan alamat instalasi. Untuk folder `C:\xampp\htdocs\simobeat`, nilai bawaan `/simobeat` sudah sesuai. Jika aplikasi dipasang di document root, ubah nilainya menjadi string kosong; jika foldernya berbeda, gunakan path folder tersebut dengan awalan `/`.

7. Buka aplikasi melalui browser:

   ```text
   http://localhost/simobeat/
   ```

8. Daftarkan akun pengguna melalui halaman pendaftaran. Ikuti bagian [Menyiapkan Akun Administrator](#menyiapkan-akun-administrator) jika membutuhkan akun admin.

## Menyiapkan Akun Administrator

Pendaftaran dari halaman aplikasi membuat akun dengan peran `user`. Untuk memberikan hak administrator:

1. Daftarkan akun melalui aplikasi seperti biasa.
2. Jalankan perintah berikut pada database `dbs_simobeat`, ganti alamat email dengan email akun yang terdaftar:

   ```sql
   UPDATE users
   SET role = 'admin'
   WHERE email = 'admin@example.com';
   ```

3. Keluar lalu masuk kembali agar aplikasi membaca peran akun yang baru.

Jaga akses administrator dan kredensial database. Berkas `admin/setup-admin.php` hanya membatasi akses berdasarkan peran admin; berkas tersebut bukan wizard pembuatan akun admin.

## Panduan Penggunaan

### Melakukan diagnosis

1. Daftar atau masuk sebagai pengguna.
2. Dari dashboard, pilih **Mulai Diagnosis**.
3. Pilih motor dari data yang tersedia.
4. Jawab seluruh pertanyaan gejala dengan **YA** atau **TIDAK**. Selama proses, pengguna dapat kembali ke pertanyaan sebelumnya atau membatalkan diagnosis.
5. Setelah semua pertanyaan dijawab, sistem menyimpan diagnosis dan menampilkan hasil.
6. Jika aturan terpenuhi, hasil mencakup kerusakan yang terdeteksi dan solusi yang telah dicatat. Jika tidak ada aturan yang cocok, sistem menyatakan bahwa kerusakan belum dapat ditentukan dari basis pengetahuan yang tersedia.
7. Buka **Riwayat Diagnosis** untuk melihat hasil sebelumnya.

### Mengelola basis pengetahuan

Administrator dapat menambahkan atau memperbarui data melalui menu administrasi:

- **Motor**: kode dan informasi motor yang dapat dipilih saat konsultasi.
- **Gejala**: daftar pertanyaan gejala yang digunakan dalam konsultasi.
- **Kerusakan**: jenis kerusakan, deskripsi, dan status aktif.
- **Solusi**: satu atau lebih saran penanganan yang terkait dengan kerusakan.
- **Aturan**: hubungan antara satu kerusakan dan kumpulan gejala yang menjadi syarat diagnosis. Aturan juga memiliki status aktif/nonaktif.

Pastikan aturan aktif memiliki setidaknya satu gejala dan terhubung ke data kerusakan yang benar. Hasil diagnosis bergantung pada kelengkapan dan ketepatan data tersebut.

## Cara Kerja Diagnosis

SIMOBEAT menggunakan metode **Forward Chaining** berbasis aturan:

1. Jawaban **YA** diperlakukan sebagai fakta gejala yang dialami; jawaban **TIDAK** tidak menjadi fakta.
2. Sistem memeriksa aturan yang berstatus aktif.
3. Suatu aturan terpenuhi hanya jika **semua** gejala yang terhubung ke aturan tersebut dijawab **YA**.
4. Aturan diperiksa berdasarkan kode aturan. Jika lebih dari satu aturan terpenuhi, sistem mengembalikan aturan aktif pertama menurut urutan tersebut.
5. Jika tidak ada aturan yang seluruh syaratnya terpenuhi, sistem menyimpan hasil bahwa kerusakan belum dapat ditentukan dan tidak memaksakan hasil.

Metode yang diterapkan bersifat terpenuhi/tidak terpenuhi. Aplikasi tidak menghitung persentase kecocokan atau tingkat keyakinan diagnosis.

## Struktur Basis Data

Skema utama berada di `database/database.sql`. Tabel-tabelnya adalah:

| Tabel | Kegunaan |
|---|---|
| `users` | Akun, email, hash kata sandi, dan peran `admin` atau `user`. |
| `motor` | Data motor yang dipilih pengguna saat diagnosis. |
| `gejala` | Daftar gejala yang ditanyakan saat konsultasi. |
| `kerusakan` | Data kerusakan dan status aktif/nonaktif. |
| `solusi` | Saran penanganan yang terhubung ke kerusakan. |
| `aturan` | Aturan diagnosis dan kerusakan yang dihasilkan. |
| `aturan_detail` | Gejala-gejala syarat untuk setiap aturan. |
| `diagnosis` | Riwayat hasil diagnosis, pengguna, motor, dan waktu diagnosis. |
| `diagnosis_gejala` | Jawaban YA/TIDAK untuk gejala dalam suatu diagnosis. |
| `log_aktivitas` | Catatan aktivitas pengguna yang direkam aplikasi. |

Relasi utama menggunakan foreign key. Menghapus kerusakan akan menghapus solusi dan aturan terkait; menghapus aturan akan menghapus detail gejalanya. Hasil diagnosis yang merujuk pada kerusakan yang dihapus tetap tersimpan, tetapi relasi kerusakannya menjadi kosong.

## Struktur Direktori

```text
admin/       Halaman dashboard dan pengelolaan data/laporan administrator
assets/      CSS, JavaScript, gambar, serta pustaka antarmuka lokal
auth/        Pendaftaran, login, logout, dan halaman bantuan kata sandi
config/      Konfigurasi aplikasi dan koneksi database
database/    Skema, data contoh, migrasi, dan backup database
helpers/     Autentikasi, validasi, format, redirect, dan Forward Chaining
includes/    Komponen layout dan pemeriksaan akses
uploads/     Lokasi berkas motor dan profil yang diunggah
user/        Dashboard, konsultasi, hasil, profil, dan riwayat pengguna
```

## Konfigurasi dan Pemecahan Masalah

- **Koneksi database gagal**: pastikan MySQL aktif, database `dbs_simobeat` sudah dibuat, serta nilai `DB_HOST`, `DB_NAME`, `DB_USER`, dan `DB_PASS` benar.
- **Halaman atau aset tidak ditemukan setelah pindah folder**: sesuaikan `BASE_URL` di `config/app.php` dengan path aplikasi pada web server.
- **Daftar pilihan motor kosong**: isi tabel `motor` melalui menu admin atau impor data contoh dari `database/seed.sql`.
- **Hasil diagnosis tidak ditemukan**: periksa bahwa gejala sudah ditautkan ke aturan, seluruh gejala aturan dijawab YA, dan aturan tersebut aktif.
- **Solusi tidak tampil**: tambahkan solusi yang terkait dengan data kerusakan melalui menu administrasi.
- **Waktu tampilan berbeda**: aplikasi mengatur zona waktu PHP ke `Asia/Jakarta` pada `config/app.php`.

## Batasan Saat Ini

- Hasil diagnosis hanya berdasarkan aturan dan data yang dimasukkan administrator; kualitas hasil bergantung pada kualitas basis pengetahuan.
- Pemilihan motor digunakan untuk mencatat kendaraan pada diagnosis. Aturan Forward Chaining yang tersedia memeriksa gejala dan tidak menghitung kecocokan berdasarkan merek atau model motor.
- Tidak ada nilai persentase atau tingkat keyakinan diagnosis.
- Fitur reset kata sandi melalui email belum aktif. Pengguna perlu menghubungi administrator untuk bantuan reset kata sandi.
- Rekomendasi diagnosis harus dikonfirmasi melalui pemeriksaan mekanik.
