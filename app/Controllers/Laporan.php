<?php

namespace App\Controllers;

use App\Models\LaporanModel;

class Laporan extends BaseController
{
    

    public function saldoNasabah()
    {
        $model = new LaporanModel();

        $search = $this->request->getGet('search');
        $bankId = session()->get('bank_id') ?? 1;

        $data['saldo'] = $model->getSaldoNasabah($search, $bankId);
        
        // Hitung total setoran dan total penarikan
        $totalSetoran = 0;
        $totalPenarikan = 0;
        foreach ($data['saldo'] as $row) {
            if ($row['total_nilai'] !== null) {
                $totalSetoran += $row['total_nilai'];
            }
            if ($row['jumlah_dana'] !== null) {
                $totalPenarikan += abs($row['jumlah_dana']); // positifkan
            }
        }
        
        $data['totalSetoran'] = $totalSetoran;
        $data['totalPenarikan'] = $totalPenarikan;
        $data['title'] = 'Laporan Saldo Nasabah';
        $data['search'] = $search;

        return view('layouts/header', $data)
             . view('laporan/saldo_nasabah', $data)
             . view('layouts/footer');
    }


  }  