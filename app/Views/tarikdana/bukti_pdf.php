<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Penarikan</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; margin: 10px; }
        .container { width: 100%; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; }
        .info { margin-bottom: 15px; }
        .total { text-align: right; font-weight: bold; margin-top: 10px; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; }
        .watermark { position: fixed; top: 50%; left: 50%; transform: rotate(-45deg); opacity: 0.1; font-size: 60px; }
        .signature { margin-top: 40px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
<?php if ($isReprint): ?>
<div class="watermark">COPY</div>
<?php endif; ?>
<div class="container">
    <div class="header">
        <h2>BUKTI PENARIKAN DANA</h2>
        <p><?= esc($bank_nama) ?></p>
    </div>
    <div class="info">
        <p><strong>ID Transaksi:</strong> <?= $header['trx_id'] ?></p>
        <p><strong>Tanggal Penarikan:</strong> <?= date('d-m-Y', strtotime($header['tanggal'])) ?></p>
        <p><strong>Nasabah:</strong> <?= esc($header['nasabah_nama']) ?></p>
        <p><strong>Petugas:</strong> <?= esc($petugas) ?></p>
        <p><strong>Jumlah Penarikan:</strong> Rp <?= number_format($header['jumlah_dana'], 0, ',', '.') ?></p>
        <p><strong>Biaya Admin:</strong> Rp <?= number_format($header['biaya_admin'], 0, ',', '.') ?></p>
        <p><strong>Catatan:</strong> <?= esc($header['catatan'] ?? '-') ?></p>
    </div>
    <div class="signature">
        <p>Paraf Petugas: _______________</p>
        <p>Cap Bank Sampah</p>
    </div>
    <div class="footer">
        <p>Dicetak pada: <?= $waktu_cetak ?></p>
        <p>Bukti ini sah jika ditandatangani petugas.</p>
        <?php if ($isReprint): ?><p><strong>Cetak ulang - bukan bukti asli</strong></p><?php endif; ?>
    </div>
</div>
</body>
</html>