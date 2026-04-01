<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Nasabah</h3>
            </div>
            <div class="card-body">
                <table id="table-nasabah" class="table table-bordered table-striped">
                    <thead>
                        <tr><th>Nama</th><th>Alamat</th><th>No. HP</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nasabah as $row): ?>
                        <tr>
                            
                            <td><?= $row['nasabah_nama'] ?></td>
                            <td><?= $row['alamat'] ?></td>
                            <td><?= $row['no_hp1'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Inisialisasi DataTables -->
<script>
$(document).ready(function() {
    $('#table-nasabah').DataTable({
        responsive: true,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
    });
});
</script>