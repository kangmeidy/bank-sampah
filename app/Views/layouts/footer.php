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

<script>
$(document).ready(function() {
    // ----- Flatpickr (date picker) -----
    var dateInput = document.getElementById("tanggal");
    if (dateInput) {
        flatpickr(dateInput, {
            dateFormat: "d-m-Y",
            altInput: true,
            altFormat: "d-m-Y",
            locale: "id",
            allowInput: true
        });
    }

    // ----- Laporan Saldo Table -----
    if ($('#tabel-saldo').length) {
        $('#tabel-saldo').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            searching: false,
            columnDefs: [{ orderable: false, targets: [2,3,4,5,6] }],
            language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
        });
    }

    // ----- Detail Setoran Table -----
    if ($('#tabel-detail-setoran').length) {
        $('#tabel-detail-setoran').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            columnDefs: [{ orderable: false, targets: [4,5,6,7,8,9] }],
            language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
        });
    }

    // ----- Setoran Form -----
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

        // Event: input on jumlah/harga
        $(document).on('input', '.jumlah, .harga', function() {
            var row = $(this).closest('tr');
            updateSubtotal(row);
        });

        // Event: change on sampah-select
        $(document).on('change', '.sampah-select', function() {
            var row = $(this).closest('tr');
            var selected = $(this).find('option:selected');
            var hargaBeli = selected.data('harga-beli');
            var satuan = selected.data('satuan');
            row.find('.harga').val(hargaBeli);
            row.find('.satuan-cell').text(satuan);
            updateSubtotal(row);
            // Fokus ke kolom jumlah
            row.find('.jumlah').focus();
        });

        // Event: Enter pada .harga (tambah baris jika sudah terisi)
        $(document).on('keypress', '.harga', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                var currentRow = $(this).closest('tr');
                var currentJumlah = currentRow.find('.jumlah').val();
                var currentHarga = currentRow.find('.harga').val();
                if (currentJumlah && currentHarga) {
                    var nextRow = currentRow.next('tr');
                    if (nextRow.length) {
                        var nextJumlah = nextRow.find('.jumlah').val();
                        var nextHarga = nextRow.find('.harga').val();
                        if (!nextJumlah && !nextHarga) {
                            nextRow.find('.sampah-select').focus();
                        } else {
                            $('#add-row').click();
                            $('.detail-row:last .sampah-select').focus();
                        }
                    } else {
                        $('#add-row').click();
                        $('.detail-row:last .sampah-select').focus();
                    }
                }
            }
        });

        // Prevent form submit on Enter for subtotal and other inputs
        $(document).on('keypress', '.subtotal, input', function(e) {
            if (e.which === 13 && $(this).closest('form').length) {
                e.preventDefault();
            }
        });

        // Add new row
        $('#add-row').click(function() {
            var newRow = $('.detail-row:first').clone();
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

        // Initial total (for edit mode)
        updateTotal();
    }
});
</script>
</body>
</html>