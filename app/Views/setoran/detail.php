<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Setoran (per baris sampah)</h3>
                <div class="card-tools">
                    <a href="<?= base_url('setoran/create') ?>" class="btn btn-primary btn-sm">Tambah Setoran</a>
                </div>
            </div>
            <div class="card-body">
                <table id="tabel-detail-setoran" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Nasabah</th>
                            <th>Sampah</th>
                            <th>Jumlah</th>
                            <th>Harga (Rp)</th>
                            <th>Subtotal (Rp)</th>
                            <th>Satuan</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $row): ?>
                        <tr>
                            <td><?= $row['trx_id'] ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td><?= esc($row['nasabah_nama']) ?></td>
                            <td><?= esc($row['sampah_nama']) ?></td>
                            <td class="text-right"><?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($row['jumlah'] * $row['harga'], 0, ',', '.') ?></td>
                            <td><?= esc($row['satuan_nama']) ?></td>
                            <td><?= esc($row['jenis_nama']) ?></td>
                            <td>
                                <a href="<?= base_url('setoran/edit/'.$row['trx_id']) ?>" class="btn btn-sm btn-info">Edit Transaksi</a>
                                <a href="<?= base_url('setoran/cetak/'.$row['trx_id']) ?>" target="_blank" class="btn btn-sm btn-success">Cetak</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Cetak -->
<div class="modal fade" id="modalCetak" tabindex="-1" role="dialog" aria-labelledby="modalCetakLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCetakLabel">Cetak Bukti Setoran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda ingin mencetak bukti setoran?
            </div>
            
            <!--
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="button" class="btn btn-primary" id="btnCetakYa">Ya, Cetak</button>
            </div>
            -->


            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
            <button type="button" class="btn btn-primary" id="btnCetakYa">Ya, Cetak</button>
            <button type="button" class="btn btn-success" id="btnCetakTambah">Cetak & Tambah Lagi</button>
</div>
        </div>
    </div>
</div>

