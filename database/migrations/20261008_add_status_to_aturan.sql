ALTER TABLE aturan
    ADD COLUMN status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif' AFTER kerusakan_id;
