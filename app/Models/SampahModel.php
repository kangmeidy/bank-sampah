<?php

namespace App\Models;

use CodeIgniter\Model;

class SampahModel extends Model
{
    protected $table = 'tb_sampah';
    protected $primaryKey = 'sampah_id';
    protected $allowedFields = []; // we won't insert/update via this model for now

    /**
     * Get all sampah for a specific bank, with satuan name joined.
     * @param int $bankId
     * @return array
     */
    public function getSampahByBank($bankId)
    {
        $builder = $this->db->table('tb_sampah s');
        $builder->select('s.*, sat.satuan_nama');
        $builder->join('tb_satuan sat', 's.satuan_id = sat.satuan_id', 'left');
        $builder->where('s.bank_id', $bankId);
        $builder->orderBy('s.sampah_nama', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getByBank($bankId)
    {
        return $this->where('bank_id', $bankId)->orderBy('nasabah_nama', 'ASC')->findAll();
    }

    


}


