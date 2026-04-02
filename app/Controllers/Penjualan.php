<?php

namespace App\Controllers;

class Penjualan extends BaseController
{
    public function index()
    {
        $data['title'] = 'Informasi - Penjualan';
        return view('layouts/header', $data)
             . view('partials/belum_ada')
             . view('layouts/footer');
    }
}