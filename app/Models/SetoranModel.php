<?php

namespace App\Models;

use CodeIgniter\Model;

class SetoranModel extends Model
{
    protected $table = 'tb_terima_detail';
    protected $primaryKey = 'trx_id'; // sesuaikan dengan primary key tabel detail
    protected $allowedFields = [];

    /**
     * Mendapatkan total setoran (jumlah * harga) tahun ini untuk bank tertentu
     * @param int $bankId
     * @return float|int
     */
    public function getTotalSetoranTahunan($bankId)
    {
        $tahunIni = date('Y');

        $builder = $this->db->table('tb_terima_detail a');
        $builder->select('SUM(a.jumlah * a.harga) as total');
        $builder->join('tb_terima_header b', 'a.trx_id = b.trx_id', 'left');
        $builder->where('YEAR(b.tanggal)', $tahunIni);
        $builder->where('b.bank_id', $bankId);
        // group by sebenarnya tidak diperlukan karena kita hanya ambil satu total
        // tapi jika ingin tetap ada, bisa ditambahkan:
        // $builder->groupBy('YEAR(b.tanggal)');
        $query = $builder->get();
        $result = $query->getRow();

        return $result->total ?? 0;
    }

    public function getMonthlySetoran($bankId = null)
    {
        $tahunIni = date('Y');
        $builder = $this->db->table('tb_terima_detail a');
        $builder->select('MONTH(b.tanggal) as bulan, SUM(a.jumlah * a.harga) as total_nilai');
        $builder->join('tb_terima_header b', 'a.trx_id = b.trx_id', 'left');
        $builder->where('YEAR(b.tanggal)', $tahunIni);
        if ($bankId) {
            $builder->where('b.bank_id', $bankId);
        }
        $builder->groupBy('MONTH(b.tanggal)');
        $builder->orderBy('bulan', 'ASC');
        $result = $builder->get()->getResultArray();

        // Membuat array 12 bulan, isi default 0
        $monthlyData = array_fill(1, 12, 0);
        foreach ($result as $row) {
            $monthlyData[(int)$row['bulan']] = (float)$row['total_nilai'];
        }
        return $monthlyData; // array index 1..12
    }




    
}