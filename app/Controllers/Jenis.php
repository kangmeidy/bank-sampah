<?php

namespace App\Controllers;

class Jenis extends BaseController
{
    public function index()
    {
        $data['title'] = 'Informasi - Jenis/Kategori Sampah';
        return view('layouts/header', $data)
             . view('partials/belum_ada')
             . view('layouts/footer');
    }
}