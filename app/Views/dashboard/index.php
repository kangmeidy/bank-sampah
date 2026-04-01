<!-- Info boxes (baris pertama) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">                
                
                <h3><?= number_format($total_nasabah, 0, ',', '.') ?> Orang</h3>
                <p>Total Nasabah</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?= base_url('nasabah') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">                
                <h3>Rp <?= number_format($total_setoran, 0, ',', '.') ?></h3>
                <p>Setoran Nasabah Tahun Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-trash-alt"></i>
            </div>
            
            <a href="<?= base_url('laporan/saldo-nasabah') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Rp <?= number_format($total_penjualan, 0, ',', '.') ?></h3>
                <p>Penjualan Bank Sampah Tahun Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            
            <!--
            <a href="javascript:void(0)" onclick="alert('Fitur detail penjualan sedang dalam pengembangan.')" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            -->


            <a href="<?= base_url('penjualan-detail/belum-ada') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!--
    //<div class="col-lg-3 col-6">    
    //    <div class="small-box bg-danger">
    //        <div class="inner">
    //            <h3>Rp 5.200.000</h3>
    //            <p>Total Pendapatan</p>
    //        </div>
    //        <div class="icon">
    //            <i class="fas fa-money-bill-wave"></i>
    //        </div>
    //        <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
    //    </div>
    //</div>    
    -->

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp <?= number_format($total_penarikan, 0, ',', '.') ?></h3>
                <p>Penarikan Dana Nasabah Tahun Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
    
            <a href="<?= base_url('laporan/saldo-nasabah') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>



</div>


<!--
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Sampah Terbaru</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Jenis Sampah</th>
                            <th>Harga/kg</th>
                            <th>Stok (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1</td><td>Plastik</td><td>Rp 2.000</td><td>500</td></tr>
                        <tr><td>2</td><td>Kertas</td><td>Rp 1.500</td><td>300</td></tr>
                        <tr><td>3</td><td>Botol Kaca</td><td>Rp 1.200</td><td>100</td></tr>
                        <tr><td>4</td><td>Logam</td><td>Rp 5.000</td><td>50</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

-->


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Sampah Terbaru</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            
                            <th>Kategori</th>
                            <th>Nama Sampah</th>

                            <th>Harga Jual (Ref.)</th>
                            <th>Harga Beli (Ref.)</th>
                            <th>Stok</th>
                            <th>Satuan</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stok_list)): ?>
                            <?php foreach ($stok_list as $item): ?>
                            <tr>
                                
                                <td><?= $item['jenis_nama'] ?? $item['sampah_nama'] ?></td>
                                <td><?= $item['sampah_nama'] ?? $item['sampah_nama'] ?></td>
                                <td>Rp <?= number_format($item['harga_jual'] ?? 0, 2, ',', '.') ?></td>
                                <td>Rp <?= number_format($item['harga_beli'] ?? 0, 2, ',', '.') ?></td>
                                <td><?= number_format($item['qty_akhir'] ?? 0, 2, ',', '.') ?></td>
                                <td><?= $item['satuan_nama'] ?? $item['satuan_nama'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




<!-- Grafik sederhana (opsional) 
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Setoran (kg)</h3>
            </div>
            <div class="card-body">
                <canvas id="barChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Komposisi Sampah</h3>
            </div>
            <div class="card-body">
                <canvas id="pieChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>
-->

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Setoran Nasabah per Bulan (Tahun <?= date('Y') ?>)</h3>
            </div>
            <div class="card-body">
                <canvas id="barChartSetoran" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Penjualan per Bulan (Tahun <?= date('Y') ?>)</h3>
            </div>
            <div class="card-body">
                <canvas id="barChartPenjualan" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Komposisi Stok Berdasarkan Nilai (Rp)</h3>
            </div>
            <div class="card-body">
                <canvas id="pieChartKomposisi" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <!-- bisa isi dengan grafik lain atau tabel -->
    </div>
</div>





<!-- Script untuk Chart.js (AdminLTE 3 tidak include, jadi kita tambahkan) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    
    <?php if (!empty($komposisi)): ?>
    var komposisiData = <?= json_encode($komposisi) ?>;
    // Konversi total_nilai ke number
    var labels = komposisiData.map(item => item.jenis_nama);
    var values = komposisiData.map(item => parseFloat(item.total_nilai) || 0);
    var totalNilai = values.reduce((a, b) => a + b, 0);
    var colors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'];

    var ctx = document.getElementById('pieChartKomposisi').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors.slice(0, labels.length)
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw;
                            let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            let percent = total > 0 ? ((value / total) * 100).toFixed(2) : 0;
                            return `${label}: Rp ${value.toLocaleString()} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
    <?php endif; ?>

    <?php if (!empty($monthlySetoran)): ?>
    var setoranData = <?= json_encode(array_values($monthlySetoran)) ?>; // ambil nilai saja
    var bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    var ctxBar = document.getElementById('barChartSetoran').getContext('2d');
    new Chart(ctxBar, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Total Setoran (Rp)',
                data: setoranData,
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    <?php endif; ?>


    <?php if (!empty($monthlyPenjualan)): ?>
    var setoranData = <?= json_encode(array_values($monthlyPenjualan)) ?>; // ambil nilai saja
    var bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    var ctxBar = document.getElementById('barChartPenjualan').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: setoranData,
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    <?php endif; ?>





</script>