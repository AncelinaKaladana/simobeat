<div class="card">
    <div class="card-header"><?= $edit ? 'Edit' : 'Tambah' ?> Aturan</div>
    <form method="post" action="<?= $edit ? 'update.php' : 'simpan.php' ?>"><?= csrf_field() ?><?php if ($edit): ?><input type="hidden" name="id" value="<?= (int)$data['id'] ?>"><?php endif ?><div class="card-body"><label class="form-label">Kode aturan</label><input class="form-control mb-3" name="kode" maxlength="30" pattern="R[0-9]+" value="<?= e($data['kode'] ?? '') ?>" placeholder="R004" required><label class="form-label">Kerusakan</label><select class="form-select mb-3" name="kerusakan_id" required>
            <option value="">Pilih</option><?php foreach ($faults as $f): ?><option value="<?= (int)$f['id'] ?>" <?= (int)($data['kerusakan_id'] ?? 0) === (int)$f['id'] ? 'selected' : '' ?>><?= e($f['kode'] . ' - ' . $f['nama']) ?></option><?php endforeach ?>
        </select>
        <fieldset class="mb-3">
            <legend class="fs-6">Gejala wajib (minimal satu)</legend><?php foreach ($symptoms as $g): ?><div class="form-check"><input class="form-check-input" type="checkbox" name="gejala[]" value="<?= (int)$g['id'] ?>" id="g<?= (int)$g['id'] ?>" <?= in_array((int)$g['id'], $selected, true) ? 'checked' : '' ?>><label class="form-check-label" for="g<?= (int)$g['id'] ?>"><?= e($g['kode'] . ' - ' . $g['nama']) ?></label></div><?php endforeach ?>
        </fieldset><label class="form-label">Solusi terkait kerusakan</label><?php if ($solutions): ?><ul><?php foreach ($solutions as $so): ?><li><?= e($so['deskripsi']) ?></li><?php endforeach ?></ul><?php else: ?><p>Belum ada solusi.</p><?php endif ?><div class="form-text mb-3">Solusi terhubung ke kerusakan pada skema database ini.</div><label class="form-label">Status</label><select class="form-select" name="status">
            <option value="aktif" <?= ($data['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="nonaktif" <?= ($data['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
        </select>
    </div>
    <div class="card-footer"><button class="btn btn-primary">Simpan</button> <a class="btn btn-secondary" href="index.php">Batal</a></div>
    </form>
</div>