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

<!-- Inisialisasi DataTables & script setoran -->
<script>
$(document).ready(function() {
    // ----- Laporan Saldo Table (if present) -----
    if ($('#tabel-saldo').length) {
        $('#tabel-saldo').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            searching: false,
            columnDefs: [{ orderable: false, targets: [2,3,4,5,6] }],
            language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
        });
    }

    // ----- Detail Setoran Table (if present) -----
    if ($('#tabel-detail-setoran').length) {
        $('#tabel-detail-setoran').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            columnDefs: [{ orderable: false, targets: [4,5,6,7,8,9] }],
            language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
        });
    }

    // ----- Setoran Form (if present) -----
    if ($('#form-setoran').length) {
        // Helper functions
        function updateSubtotal(row) {
            var jumlah = parseFloat(row.find('.jumlah').val()) || 0;
            var harga = parseFloat(row.find('.harga').val()) || 0;
            var subtotal = jumlah * harga;
            row.find('.subtotal').val(subtotal.toFixed(2));
            updateTotal();
        }

        function updateTotal() {
            var total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total').val(total.toFixed(2));
        }

        // Event: change in jumlah or harga
        $(document).on('input', '.jumlah, .harga', function() {
            var row = $(this).closest('tr');
            updateSubtotal(row);
        });

        // Event: change of sampah selection
        $(document).on('change', '.sampah-select', function() {
            var row = $(this).closest('tr');
            var selected = $(this).find('option:selected');
            var hargaBeli = selected.data('harga-beli');
            var satuan = selected.data('satuan');
            row.find('.harga').val(hargaBeli);
            row.find('.satuan-cell').text(satuan);
            updateSubtotal(row);
        });

        // Add new row
        $('#add-row').click(function() {
            var newRow = $('.detail-row:first').clone();
            // Clear values in the new row
            newRow.find('input').val('');
            newRow.find('.sampah-select').val('');
            newRow.find('.satuan-cell').text('');
            newRow.find('.subtotal').val('');
            $('#detail-table tbody').append(newRow);
        });

        // Remove row
        $(document).on('click', '.btn-remove', function() {
            if ($('.detail-row').length > 1) {
                $(this).closest('tr').remove();
                updateTotal();
            } else {
                alert('Minimal satu baris detail.');
            }
        });

        // Initial total calculation (important for edit mode)
        updateTotal();
    }
});
</script>

</body>
</html>