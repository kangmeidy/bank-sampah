<?php

namespace App\Controllers;

use App\Models\NasabahModel;    // Jumlah nasabah
use App\Models\TarikDanaModel;  // Total penarikan dana per tahun berjalan
use App\Models\SetoranModel;    // Total setoran sampah dari nasabah
use App\Models\PenjualanModel;  // Total penjualan sampah 
use App\Models\StokModel;       // Data stok sampah 


class Dashboard extends BaseController
{
    public function index()
    {

        $bankId = session()->get('bank_id'); // ambil dari session


        //die('Controller Dashboard dipanggil'); // tambahkan ini untuk test

        $bank_id = session()->get('bank_id') ?? 1; // asumsi bank_id dari session SEMENTARA

        // Hitung total nasabah
        //$model = new NasabahModel();
        //$total_nasabah = $model->countAll();   // Untuk ambil all


        // app/Controllers/Dashboard.php
        $nasabahModel = new NasabahModel();
        $total_nasabah = $nasabahModel->countByBank($bank_id);  // Filter by bank_id

        // Hitung total penarikan tahun ini
        $tarikModel = new TarikDanaModel();        
        $total_penarikan = $tarikModel->getTotalPenarikanTahunan($bank_id);


        // Total setoran (jumlah * harga)
        $setoranModel = new SetoranModel();
        $total_setoran = $setoranModel->getTotalSetoranTahunan($bank_id);

        
        $monthlySetoran = $setoranModel->getMonthlySetoran($bank_id);

        // Total penjualan (jumlah * harga)
        $penjualanModel = new PenjualanModel();
        $total_penjualan = $penjualanModel->getTotalPenjualanTahunan($bank_id);

        $monthlyPenjualan = $penjualanModel->getMonthlyPenjualan($bank_id);

        //$stokModel = new StokModel();
        //$stok_list = $stokModel->getAllStokWithDetails();


        // Ambil data komposisi stok
        //$komposisistokModel = new StokModel();
        //$komposisi = $komposisistokModel->getKomposisiStok();


        $stokModel = new StokModel();
        $stok_list = $stokModel->getAllStokWithDetails();
        $komposisi = $stokModel->getKomposisiStok();   // pakai instance yang sama

        //var_dump($komposisi); die();  // Cek sementara

        // Hitung total nilai keseluruhan
        $totalNilai = array_sum(array_column($komposisi, 'total_nilai'));

        // Hitung persentase per jenis
        foreach ($komposisi as &$item) {
            $item['persentase'] = $totalNilai > 0 ? ($item['total_nilai'] / $totalNilai) * 100 : 0;
            $item['persentase_dibulatkan'] = round($item['persentase'], 2);
        }

        
        $data['total_nilai_stok'] = $totalNilai;





        $data = [
            'title' => 'Dashboard',
            'total_nasabah' => $total_nasabah,
            'total_penarikan' => $total_penarikan,
            'total_setoran' => $total_setoran,
            'total_penjualan' => $total_penjualan,
            'stok_list'      => $stok_list,
            'komposisi' => $komposisi,
            'monthlySetoran' => $monthlySetoran,   // array 12 elemen
            'monthlyPenjualan' => $monthlyPenjualan  // array 12 elemen


        ];

        return view('layouts/header', $data)
             . view('dashboard/index', $data) // kirim data ke view index
             . view('layouts/footer');
    }
}