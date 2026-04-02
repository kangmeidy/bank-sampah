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
                        </thead>
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
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

