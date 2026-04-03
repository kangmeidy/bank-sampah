<?php

namespace App\Models;

use CodeIgniter\Model;

class TarikDanaModel extends Model
{
   

    protected $table = 'tb_tarik_dana';
    protected $primaryKey = 'trx_id';
    protected $allowedFields = ['trx_id', 'tanggal', 'nasabah_id', 'jumlah_dana', 'catatan', 'user_id', 'bank_id', 'biaya_admin', 'printed_at'];
    protected $useTimestamps = false;

    /**
     * Mendapatkan total penarikan tahun ini untuk bank tertentu
     * @param int $bankId
     * @return float|int
     */
    public function getTotalPenarikanTahunan($bankId)
    {
        $tahunIni = date('Y'); // tahun sekarang

        $builder = $this->db->table('tb_tarik_dana');
        $builder->select('SUM(jumlah_dana) as total');
        $builder->where('YEAR(tanggal)', $tahunIni);
        $builder->where('bank_id', $bankId);
        $query = $builder->get();
        $result = $query->getRow();

        // Debug: tampilkan isi $result
        //var_dump($result); die();

        // Jika tidak ada data, hasil NULL, maka beri nilai 0
        return $result->total ?? 0;
    }
}


