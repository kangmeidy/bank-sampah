<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Saldo Nasabah</h3>
                <div class="card-tools">
                    <form method="get" class="form-inline">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama nasabah" value="<?= esc($search ?? '') ?>">
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-info btn-flat">Cari</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table id="tabel-saldo" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama Nasabah</th>
                            <th>Trx ID.</th>
                            <th>Tanggal Setoran</th>
                            <th>Setoran (Rp)</th>
                            <th>Tanggal Penarikan</th>
                            <th>Penarikan (Rp)</th>
                            <th>Saldo (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($saldo as $row): ?>
                        <tr>
                            <td><?= esc($row['nasabah_nama']) ?></td>
                            <td><?= $row['trx_id'] ?></td>
                            <td><?= $row['tanggal_setor'] ? date('d-m-Y', strtotime($row['tanggal_setor'])) : '-' ?></td>
                            <td class="text-right"><?= $row['total_nilai'] ? number_format($row['total_nilai'], 0, ',', '.') : '-' ?></td>
                            <td><?= $row['tanggal_tarik'] ? date('d-m-Y', strtotime($row['tanggal_tarik'])) : '-' ?></td>
                            <td class="text-right"><?= ($row['jumlah_dana'] !== null) ? number_format(abs($row['jumlah_dana']), 0, ',', '.') : '-' ?></td>
                            <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="info-box bg-success">
                            <div class="info-box-content">
                                <span class="info-box-text">Total Setoran</span>
                                <span class="info-box-number">Rp <?= number_format($totalSetoran, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-danger">
                            <div class="info-box-content">
                                <span class="info-box-text">Total Penarikan</span>
                                <span class="info-box-number">Rp <?= number_format($totalPenarikan, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- /.card-body -->
        </div> <!-- /.card -->
    </div> <!-- /.col-12 -->
</div> <!-- /.row -->