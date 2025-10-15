<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - <?= $shipment->nomor_kontainer ?></title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .info-label {
            font-weight: bold;
        }
        
        .info-value {
            border-bottom: 1px dotted #ccc;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table th {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .signature-section {
            margin-top: 50px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-box .label {
            font-weight: bold;
            margin-bottom: 60px;
        }
        
        .signature-box .name {
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background-color: #45a049;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-persiapan { background-color: #FFF3CD; color: #856404; }
        .status-dalam-perjalanan { background-color: #CCE5FF; color: #004085; }
        .status-sampai-tujuan { background-color: #D4EDDA; color: #155724; }
        .status-selesai { background-color: #D1ECF1; color: #0C5460; }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        <i class="material-icons" style="vertical-align: middle;">print</i> Cetak Surat Jalan
    </button>

    <div class="header">
        <h1>SURAT JALAN</h1>
        <h2>Daftar Paket Pengiriman</h2>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-label">Nomor Kontainer:</div>
            <div class="info-value"><?= $shipment->nomor_kontainer ?></div>
            
            <div class="info-label">Nama Kontainer:</div>
            <div class="info-value"><?= $shipment->nama_kontainer ?? '-' ?></div>
            
            <div class="info-label">Tanggal Pengiriman:</div>
            <div class="info-value"><?= date('d F Y H:i', strtotime($shipment->tanggal_pengiriman)) ?></div>
            
            <div class="info-label">Estimasi Sampai:</div>
            <div class="info-value"><?= $shipment->estimasi_sampai ? date('d F Y', strtotime($shipment->estimasi_sampai)) : '-' ?></div>
            
            <div class="info-label">Status Pengiriman:</div>
            <div class="info-value">
                <?php
                    $statusClass = [
                        'Persiapan' => 'status-persiapan',
                        'Dalam Perjalanan' => 'status-dalam-perjalanan',
                        'Sampai Tujuan' => 'status-sampai-tujuan',
                        'Selesai' => 'status-selesai',
                    ];
                    $class = $statusClass[$shipment->status_pengiriman] ?? '';
                ?>
                <span class="status-badge <?= $class ?>"><?= $shipment->status_pengiriman ?></span>
            </div>
            
            <div class="info-label">Keterangan:</div>
            <div class="info-value"><?= $shipment->keterangan ?? '-' ?></div>
        </div>
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Paket</div>
                <div class="summary-value"><?= $shipment->total_paket ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Bobot</div>
                <div class="summary-value"><?= number_format($shipment->total_bobot, 2) ?> kg</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Biaya</div>
                <div class="summary-value">Rp <?= number_format($totalBiaya ?? 0, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 100px;">Nomor Resi</th>
                <th>Nama Penerima</th>
                <th>Alamat Tujuan</th>
                <th style="width: 100px;">No. Telepon</th>
                <th style="width: 60px;">Bobot (kg)</th>
                <th style="width: 100px;">Biaya (Rp)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $totalBiaya = 0;
            foreach ($packages as $package): 
                $totalBiaya += $package->biaya;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $package->nomor_resi ?></td>
                <td><?= $package->nama_penerima ?></td>
                <td><?= $package->alamat_penerima ?></td>
                <td><?= $package->no_telp_penerima ?></td>
                <td style="text-align: right;"><?= number_format($package->bobot, 2) ?></td>
                <td style="text-align: right;"><?= number_format($package->biaya, 0, ',', '.') ?></td>
                <td><?= $package->keterangan ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="5" style="text-align: right;">TOTAL:</td>
                <td style="text-align: right;"><?= number_format($shipment->total_bobot, 2) ?></td>
                <td style="text-align: right;"><?= number_format($totalBiaya, 0, ',', '.') ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="label">Pengirim</div>
            <div class="name">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div>
        </div>
        <div class="signature-box">
            <div class="label">Kurir/Sopir</div>
            <div class="name">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div>
        </div>
        <div class="signature-box">
            <div class="label">Penerima</div>
            <div class="name">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>
        <p>Dokumen ini sah tanpa tanda tangan dan cap</p>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
