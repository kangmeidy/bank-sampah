<?php

namespace App\Models;

use CodeIgniter\Model;

class NasabahModel extends Model
{
    


    protected $table = 'tb_nasabah';
    protected $primaryKey = 'nasabah_id';
    protected $allowedFields = ['nasabah_nama', 'alamat', 'no_hp1'];  // Kalo untuk Count kosongkan saja.

    
    // Tambahkan method ini
    //public function getByBank($bankId, $orderBy = 'nasabah_id', $orderDir = 'ASC')
    //{
    //    return $this->where('bank_id', $bankId)
    //                ->orderBy($orderBy, $orderDir)
    //                ->findAll();
    //}

    public function countByBank($bankId)
	{
	    return $this->where('bank_id', $bankId)->countAllResults();
	}

    public function getByBank($bankId)
    {
        return $this->where('bank_id', $bankId)->orderBy('nasabah_nama', 'ASC')->findAll();
    }

}