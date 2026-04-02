<?php

namespace App\Controllers;

class Tarikdana extends BaseController
{
    public function index()
    {
        $data['title'] = 'Informasi - Tarik dana';
        return view('layouts/header', $data)
             . view('partials/belum_ada')
             . view('layouts/footer');
    }
}