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
        $builder->where('a.bank_id', $bankId);

        // Filter by bank_id if provided
        if ($bankId !== null) {
            $builder->where('a.bank_id', $bankId);
        }

        $builder->orderBy('b.sampah_nama', 'ASC');

        //echo $builder->getCompiledSelect(); die();  // Test 

        return $builder->get()->getResultArray();
    }




// public function getKomposisiStok($bankId = null)
// {
//     $builder = $this->db->table('tb_stok a');
//     $builder->select('
//         a.bank_id,
//         d.jenis_id,
//         d.jenis_nama,
//         SUM(a.qty_akhir * b.harga_jual) as total_nilai
//     ');
//     $builder->join('tb_sampah b', 'a.sampah_id = b.sampah_id', 'left');
//     $builder->join('tb_jenis d', 'b.jenis_id = d.jenis_id', 'left');
    
//     if ($bankId !== null) {
//         $builder->where('a.bank_id', $bankId);
//     }

//     $builder->groupBy('d.jenis_id, d.jenis_nama');
//     $builder->orderBy('total_nilai', 'DESC');
    
//     // Debug: tampilkan query
//     $query = $builder->getCompiledSelect();
//     var_dump($query); die();
    
//     return $builder->get()->getResultArray();
// }




public function getKomposisiStok($bankId = null)
{
    // Ambil bank_id langsung dari session (paling aman)
    $bankId = session()->get('bank_id');
    if (empty($bankId)) {
        $bankId = 1; // fallback default
    }

    $builder = $this->db->table('tb_stok a');
    $builder->select('
        a.bank_id,
        d.jenis_id,
        d.jenis_nama,
        SUM(a.qty_akhir * b.harga_jual) as total_nilai
    ');
    $builder->join('tb_sampah b', 'a.sampah_id = b.sampah_id', 'left');
    $builder->join('tb_jenis d', 'b.jenis_id = d.jenis_id', 'left');
    $builder->where('a.bank_id', $bankId);
    $builder->groupBy('d.jenis_id, d.jenis_nama');
    $builder->orderBy('total_nilai', 'DESC');
    
    return $builder->get()->getResultArray();
}


// public function getKomposisiStok($bankId = NULL)
// {
//     $builder = $this->db->table('tb_stok a');
//     $builder->select('
//         a.bank_id,
//         d.jenis_id,
//         d.jenis_nama,
//         SUM(a.qty_akhir * b.harga_jual) as total_nilai
//     ');
//     $builder->join('tb_sampah b', 'a.sampah_id = b.sampah_id', 'left');
//     $builder->join('tb_jenis d', 'b.jenis_id = d.jenis_id', 'left');


//      echo '<pre>';
//          print_r(session()->get());
//          echo '</pre>';
//          //die();
    
//     // Filter by bank_id if provided (PASTIKAN TIDAK ADA KOMENTAR)
//     //if ($bankId !== null) {
//         $builder->where('a.bank_id', $bankId);

//     //}

//     $builder->groupBy('d.jenis_id, d.jenis_nama');
//     $builder->orderBy('total_nilai', 'DESC');

//    echo $builder->getCompiledSelect(); 
//    die();  // Test 

//     return $builder->get()->getResultArray();
// }


    // public function getKomposisiStok($bankId = null)
    // {


    //     $builder = $this->db->table('tb_stok a');
    //     $builder->select('
    //         a.bank_id,
    //         d.jenis_id,
    //         d.jenis_nama,
    //         SUM(a.qty_akhir * b.harga_jual) as total_nilai
    //     ');
    //     $builder->join('tb_sampah b', 'a.sampah_id = b.sampah_id', 'left');
    //     $builder->join('tb_jenis d', 'b.jenis_id = d.jenis_id', 'left');
    //     $builder->where('a.bank_id', $bankId);

    //     // Filter by bank_id if provided
    //     // if ($bankId !== null) {
    //     //     $builder->where('a.bank_id', $bankId);
    //     // }

    //     $builder->groupBy('d.jenis_id, d.jenis_nama');
    //     $builder->orderBy('total_nilai', 'DESC');

    //     echo $builder->getCompiledSelect(); die();  // Test 

    //     return $builder->get()->getResultArray();
    // }




    // public function getKomposisiStok($bankId = null)
    // {
    //     $builder = $this->db->table('tb_stok a');
    //     $builder->select('
    //         a.bank_id,
    //         d.jenis_id,
    //         d.jenis_nama,
    //         SUM(a.qty_akhir * b.harga_jual) as total_nilai
    //     ');
    //     $builder->join('tb_sampah b', 'a.sampah_id = b.sampah_id', 'left');
    //     $builder->join('tb_jenis d', 'b.jenis_id = d.jenis_id', 'left');
        
    //     // Hanya tambahkan where jika $bankId tidak null
    //     if ($bankId !== null) {
    //         $builder->where('a.bank_id', $bankId);
    //     }

    //     $builder->groupBy('d.jenis_id, d.jenis_nama');
    //     $builder->orderBy('total_nilai', 'DESC');

    //     //echo $builder->getCompiledSelect(); die();  // Test 


    //     return $builder->get()->getResultArray();
    // }




    
}