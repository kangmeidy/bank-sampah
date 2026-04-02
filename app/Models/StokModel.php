<?php

namespace App\Models;

use CodeIgniter\Model;

class StokModel extends Model
{
    protected $table = 'tb_stok';
    protected $primaryKey = 'stok_id';
    protected $allowedFields = [];

    public function getAllStokWithDetails($bankId = null)
    {
        $builder = $this->db->table('tb_stok a');
        $builder->select('
            a.stok_id,
            a.bank_id,
            a.sampah_id,
            b.harga_jual,
            b.harga_beli,
            a.qty_awal,
            a.qty_masuk,
            a.qty_keluar,
            a.qty_akhir,
            a.last_update,
            b.sampah_nama,
            c.satuan_nama,
            d.jenis_nama
        ');
        $builder->join('tb_sampah b', 'a.sampah_id = b.sampah_id', 'left');
        $builder->join('tb_satuan c', 'b.satuan_id = c.satuan_id', 'left');
        $builder->join('tb_jenis d', 'b.jenis_id = d.jenis_id', 'left');

        // Filter by bank_id if provided
        if ($bankId !== null) {
            $builder->where('a.bank_id', $bankId);
        }

        $builder->orderBy('b.sampah_nama', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getKomposisiStok($bankId = null)
    {
        $builder = $this->db->table('tb_stok a');
        $builder->select('
            d.jenis_id,
            d.jenis_nama,
            SUM(a.qty_akhir * b.harga_jual) as total_nilai
        ');
        $builder->join('tb_sampah b', 'a.sampah_id = b.sampah_id', 'left');
        $builder->join('tb_jenis d', 'b.jenis_id = d.jenis_id', 'left');

        // Filter by bank_id if provided
        if ($bankId !== null) {
            $builder->where('a.bank_id', $bankId);
        }

        $builder->groupBy('d.jenis_id, d.jenis_nama');
        $builder->orderBy('total_nilai', 'DESC');
        return $builder->get()->getResultArray();
    }
}