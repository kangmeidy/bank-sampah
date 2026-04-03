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

<!-- DataTables (combined) -->
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.css">
<script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.js"></script>

<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
$(document).ready(function() {

    // ----- TARIK DANA FORM (create & edit) -----
    if ($('#form-tarik').length) {
        var nasabahSelect = $('#nasabah_id');
        var saldoField = $('#saldo');
        var jumlahInput = $('#jumlah_dana');
        var form = $('#form-tarik');
        
        <?php if (isset($isEdit) && $isEdit && isset($header['trx_id'])): ?>
        var excludeTrx = '<?= $header['trx_id'] ?>';
        <?php else: ?>
        var excludeTrx = '';
        <?php endif; ?>

        function loadSaldo(nasabahId) {
            if (nasabahId) {
                var url = '<?= base_url('tarikdana/getSaldo/') ?>' + nasabahId;
                if (excludeTrx) {
                    url += '?exclude=' + excludeTrx;
                }
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.saldo !== undefined) {
                            saldoField.val('Rp ' + response.saldo.toLocaleString('id-ID'));
                            saldoField.data('saldo_numeric', response.saldo);
                        } else if (response.error) {
                            console.error('Server error:', response.error);
                            saldoField.val('Error: ' + response.error);
                        } else {
                            saldoField.val('Invalid response');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error, xhr.responseText);
                        saldoField.val('Gagal load saldo (status ' + status + ')');
                    }
                });
            } else {
                saldoField.val('');
                saldoField.data('saldo_numeric', 0);
            }
        }

        nasabahSelect.off('change').on('change', function() {
            loadSaldo($(this).val());
        });

        // Validasi dengan alert biasa
        form.on('submit', function(e) {
            var jumlah = parseFloat(jumlahInput.val()) || 0;
            var saldo = saldoField.data('saldo_numeric') || 0;
            if (jumlah > saldo) {
                e.preventDefault();
                alert('Jumlah penarikan melebihi saldo nasabah (Saldo: Rp ' + saldo.toLocaleString('id-ID') + ')');
                return false;
            }
            return true;
        });

        if (nasabahSelect.val()) {
            loadSaldo(nasabahSelect.val());
        }
    }

    // ----- Flatpickr (tanggal) -----
    if ($('#tanggal').length) {
        flatpickr("#tanggal", {
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
            columnDefs: [{ orderable: false, targets: [1,2,3,4,5,6] }],
            language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
        });
    }

    // ----- Detail Setoran Table -----
    if ($('#tabel-detail-setoran').length) {
        $('#tabel-detail-setoran').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            columnDefs: [{ orderable: false, targets: [9] }],
            language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
        });
    }

    // ----- Setoran Form dynamic rows & calculations -----
    if ($('#form-setoran').length) {
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

        $(document).on('input', '.jumlah, .harga', function() {
            var row = $(this).closest('tr');
            updateSubtotal(row);
        });

        $(document).on('change', '.sampah-select', function() {
            var row = $(this).closest('tr');
            var selected = $(this).find('option:selected');
            var hargaBeli = selected.data('harga-beli');
            var satuan = selected.data('satuan');
            row.find('.harga').val(hargaBeli);
            row.find('.satuan-cell').text(satuan);
            updateSubtotal(row);
            row.find('.jumlah').focus();
        });

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

        $(document).on('keypress', '.subtotal, input', function(e) {
            if (e.which === 13 && $(this).closest('form').length) {
                e.preventDefault();
            }
        });

        $('#add-row').click(function() {
            var newRow = $('.detail-row:first').clone();
            newRow.find('input').val('');
            newRow.find('.sampah-select').val('');
            newRow.find('.satuan-cell').text('');
            newRow.find('.subtotal').val('');
            $('#detail-table tbody').append(newRow);
        });

        $(document).on('click', '.btn-remove', function() {
            if ($('.detail-row').length > 1) {
                $(this).closest('tr').remove();
                updateTotal();
            } else {
                alert('Minimal satu baris detail.');
            }
        });

        updateTotal();
    }

    // ----- Modal Cetak setelah simpan (halaman detail setoran) -----
    if ($('#tabel-detail-setoran').length) {
        const urlParams = new URLSearchParams(window.location.search);
        const trxId = urlParams.get('cetak');
        if (trxId) {
            $('#modalCetak').modal('show');
            $('#btnCetakYa').click(function() {
                window.open('<?= base_url('setoran/cetak/') ?>' + trxId, '_blank');
                $('#modalCetak').modal('hide');
            });
            $('#btnCetakTambah').click(function() {
                window.open('<?= base_url('setoran/cetak/') ?>' + trxId, '_blank');
                window.location.href = '<?= base_url('setoran/create') ?>';
            });
            history.replaceState(null, '', window.location.pathname);
        }
    }
});
</script>
</body>
</html>