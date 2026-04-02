<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Setoran</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 10px;
        }

           table td.text-right {
            text-align: right;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
        }
        .info {
            margin-bottom: 15px;
        }
        .info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: rotate(-45deg);
            opacity: 0.1;
            font-size: 60px;
            white-space: nowrap;
            z-index: 999;
        }
        .signature {
            margin-top: 40px;
            text-align: left;
        }
    </style>
</head>
<body>
<?php if ($isReprint): ?>
<div class="watermark">COPY</div>
<?php endif; ?>

<div class="container">
    <div class="header">
        <h2>BUKTI SETORAN NASABAH</h2>
        <p><?= esc($bank_nama) ?></p>
    </div>

    <div class="info">
        <p><strong>ID Transaksi:</strong> <?= $header['trx_id'] ?></p>
        <p><strong>Tanggal Setoran:</strong> <?= date('d-m-Y', strtotime($header['tanggal'])) ?></p>
        <p><strong>Nasabah:</strong> <?= esc($header['nasabah_nama']) ?></p>
        <p><strong>Petugas:</strong> <?= esc($petugas) ?></p>
        <p><strong>Catatan:</strong> <?= esc($header['catatan'] ?? '-') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sampah</th>
                <th>Jumlah</th>
                <th>Harga (Rp)</th>
                <th>Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($details as $det): ?>
            <tr>
                <td><?= esc($det['sampah_nama']) ?></td>
                <td class="text-right"><?= number_format($det['jumlah'], 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($det['harga'], 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($det['jumlah'] * $det['harga'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        <strong>Total Setoran: Rp <?= number_format($total, 0, ',', '.') ?></strong>
    </div>

    <div class="signature">
        <p>Paraf Petugas: _______________</p>
        <p>Cap Bank Sampah (jika ada)</p>
    </div>

    
    <div class="footer">
    <p>Dicetak pada: <?= $waktu_cetak ?></p>
    <p>Bukti ini sah jika ditandatangani petugas.</p>
    <?php if ($isReprint): ?>
    <p><strong>Cetak ulang - bukan bukti asli</strong></p>
    <?php endif; ?>
</div>




</div>
</body>
</html>