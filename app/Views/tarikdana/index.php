<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Penarikan Dana</h3>
                <div class="card-tools">
                    <a href="<?= base_url('tarikdana/create') ?>" class="btn btn-primary btn-sm">Tambah Penarikan</a>
                </div>
            </div>
            <div class="card-body">


<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

            
                <table id="tabel-tarik" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Nasabah</th>
                            <th>Jumlah (Rp)</th>
                            <th>Biaya Admin</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $row): ?>
                        <tr>
                            <td><?= $row['trx_id'] ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td><?= esc($row['nasabah_nama']) ?></td>
                            <td class="text-right"><?= number_format($row['jumlah_dana'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($row['biaya_admin'], 0, ',', '.') ?></td>
                            <td><?= esc($row['catatan']) ?></td>
                            <td>
                                <a href="<?= base_url('tarikdana/edit/'.$row['trx_id']) ?>" class="btn btn-sm btn-info">Edit</a>
                                <a href="<?= base_url('tarikdana/cetak/'.$row['trx_id']) ?>" target="_blank" class="btn btn-sm btn-success">Cetak</a>
                                <a href="<?= base_url('tarikdana/delete/'.$row['trx_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Cetak (sama seperti setoran) -->
<div class="modal fade" id="modalCetak" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Bukti Penarikan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">Apakah Anda ingin mencetak bukti penarikan?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="button" class="btn btn-primary" id="btnCetakYa">Ya, Cetak</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tabel-tarik').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
    });

    // Modal cetak jika ada parameter cetak
    const urlParams = new URLSearchParams(window.location.search);
    const trxId = urlParams.get('cetak');
    if (trxId) {
        $('#modalCetak').modal('show');
        $('#btnCetakYa').click(function() {
            window.open('<?= base_url('tarikdana/cetak/') ?>' + trxId, '_blank');
            $('#modalCetak').modal('hide');
            history.replaceState(null, '', window.location.pathname);
        });
    }
});
</script>