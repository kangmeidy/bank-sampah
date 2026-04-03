<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Sampah</h3>
            </div>
            <div class="card-body">
                <table id="table-sampah" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Nama Sampah</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Harga Beli (Rp)</th>
                            <th>Harga Jual (Rp)</th>                            
                            <th>Gambar</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sampah as $row): ?>
                        <tr>
                            <td><?= esc($row['jenis_nama']) ?></td>
                            <td><?= esc($row['sampah_nama']) ?></td>
                            <td class="text-right"><?= number_format($row['qty_akhir'], 0, ',', '.') ?></td>  
                            <td><?= esc($row['satuan_nama']) ?></td>
                            <td class="text-right"><?= number_format($row['harga_beli'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($row['harga_jual'], 0, ',', '.') ?></td>                             
                            <td>
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="<?= base_url('uploads/sampah/' . $row['gambar']) ?>" style="width: 50px; height: 50px; object-fit: cover;" alt="Gambar Sampah">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>                    
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#table-sampah').DataTable({
        responsive: true,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
    });
});
</script>