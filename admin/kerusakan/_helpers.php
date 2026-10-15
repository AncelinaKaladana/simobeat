<?php
function kerusakan_code_exists(PDO $pdo, string $code, ?int $excludeId = null): bool
{
    $sql = 'SELECT 1 FROM kerusakan WHERE kode = ?';
    $params = [$code];

    if ($excludeId !== null) {
        $sql .= ' AND id != ?';
        $params[] = $excludeId;
    }

    $query = $pdo->prepare($sql);
    $query->execute($params);

    return (bool) $query->fetchColumn();
}

function kerusakan_validation_errors(string $nama, string $deskripsi): array
{
    $errors = [];

    if ($nama === '') {
        $errors[] = 'Nama kerusakan wajib diisi.';
    } elseif (mb_strlen($nama) > 180) {
        $errors[] = 'Nama kerusakan tidak boleh melebihi 180 karakter.';
    }

    if (mb_strlen($deskripsi) > 5000) {
        $errors[] = 'Deskripsi tidak boleh melebihi 5.000 karakter.';
    }

    return $errors;
}
