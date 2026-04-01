<?php namespace App\Controllers;
use App\Models\NasabahModel;

class Nasabah extends BaseController
{
    //public function index()
    //{
    //    $model = new NasabahModel();
    //    $data['nasabah'] = $model->findAll(); // atau paginate jika banyak
    //    $data['title'] = 'Data Nasabah';
    //    return view('layouts/header', $data)
    //         . view('nasabah/index', $data)
    //         . view('layouts/footer');
    //}


    

	public function index()
	{
	    $bankId = session()->get('bank_id') ?? 1; // asumsi bank_id dari session

	    $model = new NasabahModel();
	    $data['nasabah'] = $model->getByBank($bankId, 'nasabah_nama', 'ASC'); // cukup sekali dengan order
	    $data['title'] = 'Data Nasabah';

	    return view('layouts/header', $data)
	         . view('nasabah/index', $data)
	         . view('layouts/footer');
	}


}