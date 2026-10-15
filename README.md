
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
