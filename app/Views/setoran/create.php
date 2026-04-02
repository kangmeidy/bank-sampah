<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= $isEdit ? 'Edit Setoran' : 'Form Setoran Nasabah' ?></h3>
            </div>
            <form action="<?= base_url($isEdit ? 'setoran/update/'.$header['trx_id'] : 'setoran/store') ?>" method="post" id="form-setoran">
                <?= csrf_field() ?>
                <?php if ($isEdit): ?>
                    <input type="hidden" name="trx_id" value="<?= $header['trx_id'] ?>">
                <?php endif; ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nasabah</label>
                                <select name="nasabah_id" class="form-control" required>
                                    <option value="">Pilih Nasabah</option>
                                    <?php foreach ($nasabah as $n): ?>
                                    <option value="<?= $n['nasabah_id'] ?>" <?= ($isEdit && $header['nasabah_id'] == $n['nasabah_id']) ? 'selected' : '' ?>>
                                        <?= esc($n['nasabah_nama']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Catatan (opsional)</label>
                                <input type="text" name="catatan" class="form-control" placeholder="Catatan..." value="<?= $isEdit ? esc($header['catatan']) : '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Detail Setoran</label>
                        <table class="table table-bordered table-striped" id="detail-table">
                            <thead>
                                <tr>
                                    <th>Sampah</th>
                                    <th>Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Harga (Rp)</th>
                                    <th>Subtotal (Rp)</th>
                                    <th width="60">Aksi</th>
                                </thead>
                            </thead>
                            <tbody>
                                <?php if ($isEdit && isset($details) && count($details) > 0): ?>
                                    <?php foreach ($details as $det): ?>
                                    <tr class="detail-row">
                                         <td>
                                            <select name="sampah_id[]" class="form-control sampah-select" required>
                                                <option value="">Pilih Sampah</option>
                                                <?php foreach ($sampah as $s): ?>
                                                <option value="<?= $s['sampah_id'] ?>" data-harga-beli="<?= $s['harga_beli'] ?>" data-satuan="<?= esc($s['satuan_nama']) ?>" <?= ($det['sampah_id'] == $s['sampah_id']) ? 'selected' : '' ?>>
                                                    <?= esc($s['sampah_nama']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="satuan-cell"><?= esc($det['satuan_nama'] ?? '') ?></td>
                                        <td><input type="number" name="jumlah[]" class="form-control jumlah" step="0.01" value="<?= $det['jumlah'] ?>" required></td>
                                        <td><input type="number" name="harga[]" class="form-control harga" step="0.01" value="<?= $det['harga'] ?>" required></td>
                                        <td><input type="text" name="subtotal[]" class="form-control subtotal" readonly value="<?= $det['jumlah'] * $det['harga'] ?>"></td>
                                        <td class="text-center"><button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr class="detail-row">
                                        <td>
                                            <select name="sampah_id[]" class="form-control sampah-select" required>
                                                <option value="">Pilih Sampah</option>
                                                <?php foreach ($sampah as $s): ?>
                                                <option value="<?= $s['sampah_id'] ?>" data-harga-beli="<?= $s['harga_beli'] ?>" data-satuan="<?= esc($s['satuan_nama']) ?>">
                                                    <?= esc($s['sampah_nama']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="satuan-cell"></td>
                                        <td><input type="number" name="jumlah[]" class="form-control jumlah" step="0.01" required></td>
                                        <td><input type="number" name="harga[]" class="form-control harga" step="0.01" required></td>
                                        <td><input type="text" name="subtotal[]" class="form-control subtotal" readonly></td>
                                        <td class="text-center"><button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <button type="button" id="add-row" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah Baris</button>
                    </div>

                    <div class="form-group">
                        <label>Total Setoran (Rp)</label>
                        <input type="text" name="total" id="total" class="form-control" readonly>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('setoran/detail') ?>" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>


