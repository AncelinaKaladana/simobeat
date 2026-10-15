<?php function rule_set_exists(PDO $pdo, int $kid, array $ids, int $except = 0): bool
{
    sort($ids);
    $sql = 'SELECT id FROM aturan WHERE kerusakan_id=?';
    $p = [$kid];
    if ($except) {
        $sql .= ' AND id<>?';
        $p[] = $except;
    }
    $q = $pdo->prepare($sql);
    $q->execute($p);
    foreach ($q->fetchAll(PDO::FETCH_COLUMN) as $id) {
        $d = $pdo->prepare('SELECT gejala_id FROM aturan_detail WHERE aturan_id=? ORDER BY gejala_id');
        $d->execute([(int)$id]);
        if (array_map('intval', $d->fetchAll(PDO::FETCH_COLUMN)) === $ids) return true;
    }
    return false;
}
