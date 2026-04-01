            </div> <!-- /.container-fluid -->
        </div> <!-- /.content -->
    </div> <!-- /.content-wrapper -->

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; <?= date('Y') ?> Bank Sampah (GEMPOL ASRI - Meidy 0822 2841 1686).</strong> All rights reserved.
    </footer>
</div> <!-- ./wrapper -->

<!-- jQuery -->
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>"></script>

<!-- DataTables (combined CSS+JS) -->
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.css">
<script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.js"></script>

<!-- Inisialisasi DataTables untuk halaman laporan saldo -->
<script>
$(document).ready(function() {
    // Pastikan tabel dengan id 'tabel-saldo' ada
    if ($('#tabel-saldo').length) {
        $('#tabel-saldo').DataTable({
            responsive: true,
            order: [[0, 'asc']],   // urut berdasarkan kolom nama
            searching: false,          
            columnDefs: [
                { orderable: false, targets: [1,2,3,4,5,6] }  // 
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            }
        });
    }
});
</script>

</body>
</html>