<?php

namespace App\Controllers;

class Sampah extends BaseController
{
    public function index()
    {
        $data['title'] = 'Informasi - Sampah';
        return view('layouts/header', $data)
             . view('partials/belum_ada')
             . view('layouts/footer');
    }
}