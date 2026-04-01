<?php

namespace App\Controllers;

class PenjualanDetail extends BaseController
{
    public function belumAda()
    {
        $data['title'] = 'Informasi - Detail Penjualan';
        return view('layouts/header', $data)
             . view('partials/belum_ada')
             . view('layouts/footer');
    }
}