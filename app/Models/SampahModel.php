<?php
namespace App\Models;

use CodeIgniter\Model;

class SampahModel extends Model
{
    protected $table = 'tb_sampah';
    protected $primaryKey = 'sampah_id';
    protected $allowedFields = [];

    public function getSampahByBank($bankId)
    {
        $builder = $this->db->table('tb_sampah s');
        $builder->select('s.*, sat.satuan_nama, j.jenis_nama,k.qty_akhir');
        $builder->join('tb_satuan sat', 's.satuan_id = sat.satuan_id', 'left');
        $builder->join('tb_jenis j', 's.jenis_id = j.jenis_id', 'left');
        $builder->join('tb_stok k', 's.sampah_id = k.sampah_id', 'left');    
        $builder->where('s.bank_id', $bankId);
        $builder->orderBy('s.sampah_nama', 'ASC');
        return $builder->get()->getResultArray();
    }
}