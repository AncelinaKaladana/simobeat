ALTER TABLE kerusakan
    ADD COLUMN status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif' AFTER deskripsi;
