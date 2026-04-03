<?php
namespace App\Controllers;

use App\Models\SampahModel;

class Sampah extends BaseController
{
    public function index()
    {
        // Ambil bank_id dari session login
        $bankId = session()->get('bank_id');
        if (empty($bankId)) {
            $bankId = 1; // fallback default
        }

        $model = new SampahModel();
        $data['sampah'] = $model->getSampahByBank($bankId);
        $data['title'] = 'Data Sampah';

        return view('layouts/header', $data)
             . view('sampah/index', $data)
             . view('layouts/footer');
    }
}