<?php
$isEdit = $isEdit ?? false;
$header = $header ?? null;
?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= $isEdit ? 'Edit Penarikan Dana' : 'Form Penarikan Dana' ?></h3>
            </div>
            <form action="<?= base_url($isEdit ? 'tarikdana/update/'.$header['trx_id'] : 'tarikdana/store') ?>" method="post" id="form-tarik">
                <?= csrf_field() ?>
                <?php if ($isEdit): ?>
                    <input type="hidden" name="trx_id" value="<?= $header['trx_id'] ?>">
                <?php endif; ?>
                <div class="card-body">

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>
                
                    <div class="form-group">
                        <label>Nasabah</label>
                        <select name="nasabah_id" id="nasabah_id" class="form-control" required>
                            <option value="">Pilih Nasabah</option>
                            <?php foreach ($nasabah as $n): ?>
                            <option value="<?= $n['nasabah_id'] ?>" <?= ($isEdit && $header['nasabah_id'] == $n['nasabah_id']) ? 'selected' : '' ?>>
                                <?= esc($n['nasabah_nama']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" name="tanggal" id="tanggal" class="form-control" value="<?= $isEdit ? date('d-m-Y', strtotime($header['tanggal'])) : date('d-m-Y') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Saldo Nasabah (Rp)</label>
                        <input type="text" id="saldo" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Penarikan (Rp)</label>
                        <input type="number" name="jumlah_dana" id="jumlah_dana" class="form-control" step="100" value="<?= $isEdit ? $header['jumlah_dana'] : '' ?>" required>
                    </div>
                    <!--
                    <div class="form-group">
                        <label>Biaya Admin (Rp)</label>
                        <input type="number" name="biaya_admin" class="form-control" step="100" value="<?= $isEdit ? $header['biaya_admin'] : 0 ?>">disabled
                    </div>
                    -->

                    <div class="form-group">
                        <label>Biaya Admin (Rp)</label>
                        <input type="number" name="biaya_admin" class="form-control" step="100" 
                               value="<?= $isEdit ? $header['biaya_admin'] : 0 ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"><?= $isEdit ? esc($header['catatan']) : '' ?></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('tarikdana') ?>" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Flatpickr untuk tanggal (opsional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
flatpickr("#tanggal", {
    dateFormat: "d-m-Y",
    altInput: true,
    altFormat: "d-m-Y",
    locale: "id",
    allowInput: true
});
</script>