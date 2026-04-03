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

    // public function getSaldo($nasabahId, $bankId, $excludeTrxId = null)
    // {
    //     $db = \Config\Database::connect();
        
    //     // Total setoran (RAW QUERY, karena query builder bermasalah)
    //     $sqlSetoran = "SELECT SUM(d.jumlah * d.harga) as total 
    //                    FROM tb_terima_detail d 
    //                    JOIN tb_terima_header h ON d.trx_id = h.trx_id 
    //                    WHERE h.nasabah_id = ? AND h.bank_id = ?";
    //     $querySetoran = $db->query($sqlSetoran, [$nasabahId, $bankId]);
    //     $totalSetoran = $querySetoran->getRow()->total ?? 0;
        
    //     // Total penarikan (bisa pakai query builder atau raw)
    //     $builder = $db->table('tb_tarik_dana')
    //         ->select('SUM(jumlah_dana) as total')
    //         ->where('nasabah_id', $nasabahId)
    //         ->where('bank_id', $bankId);
    //     if ($excludeTrxId) {
    //         $builder->where('trx_id !=', $excludeTrxId);
    //     }
    //     $totalPenarikan = $builder->get()->getRow()->total ?? 0;
        
    //     return $totalSetoran - $totalPenarikan;
    // }


public function getSaldo($nasabahId, $bankId, $excludeTrxId = null)
{
    $db = \Config\Database::connect();
    
    // Total setoran (raw query)
    $sqlSetoran = "SELECT SUM(d.jumlah * d.harga) as total 
                   FROM tb_terima_detail d 
                   JOIN tb_terima_header h ON d.trx_id = h.trx_id 
                   WHERE h.nasabah_id = ? AND h.bank_id = ?";
    $querySetoran = $db->query($sqlSetoran, [$nasabahId, $bankId]);
    $totalSetoran = $querySetoran->getRow()->total ?? 0;
    
    // Total penarikan (kecuali excludeTrxId)
    $builder = $db->table('tb_tarik_dana')
        ->select('SUM(jumlah_dana) as total')
        ->where('nasabah_id', $nasabahId)
        ->where('bank_id', $bankId);
    if ($excludeTrxId) {
        $builder->where('trx_id !=', $excludeTrxId);
    }
    $totalPenarikan = $builder->get()->getRow()->total ?? 0;
    
    return $totalSetoran - $totalPenarikan;
}

        // public function getSaldo($nasabahId, $bankId, $excludeTrxId = null)
        // {
        //     $db = \Config\Database::connect();
        //     $query = $db->query("
        //         SELECT SUM(d.jumlah * d.harga) as total
        //         FROM tb_terima_detail d
        //         JOIN tb_terima_header h ON d.trx_id = h.trx_id
        //         WHERE h.nasabah_id = ? AND h.bank_id = ?
        //     ", [$nasabahId, $bankId]);
        //     $totalSetoran = $query->getRow()->total ?? 0;
        //     // ... hitung penarikan
        //     return $totalSetoran - $totalPenarikan;
        // }

        // //========================================
        // public function getSaldo($nasabahId, $bankId, $excludeTrxId = null)
        // {
        //     $db = \Config\Database::connect();
            
        //     // ----- Debug query total setoran -----
        //     $builderSetoran = $db->table('tb_terima_detail d')
        //         ->select('SUM(d.jumlah * d.harga) as total')
        //         ->join('tb_terima_header h', 'd.trx_id = h.trx_id')
        //         ->where('h.nasabah_id', $nasabahId)
        //         ->where('h.bank_id', $bankId);
            
        //     // Tampilkan SQL
        //     $sqlSetoran = $builderSetoran->getCompiledSelect();
        //     echo "<pre>SQL SETORAN: " . htmlspecialchars($sqlSetoran) . "</pre>";
            
        //     // Eksekusi query
        //     $resultSetoran = $builderSetoran->get()->getRow();
        //     $totalSetoran = $resultSetoran->total ?? 0;
        //     echo "<pre>HASIL TOTAL SETORAN: " . $totalSetoran . "</pre>";
            
        //     // ----- Debug query total penarikan -----
        //     $builderPenarikan = $db->table('tb_tarik_dana')
        //         ->select('SUM(jumlah_dana) as total')
        //         ->where('nasabah_id', $nasabahId)
        //         ->where('bank_id', $bankId);
        //     if ($excludeTrxId) {
        //         $builderPenarikan->where('trx_id !=', $excludeTrxId);
        //     }
        //     $sqlPenarikan = $builderPenarikan->getCompiledSelect();
        //     echo "<pre>SQL PENARIKAN: " . htmlspecialchars($sqlPenarikan) . "</pre>";
            
        //     $totalPenarikan = $builderPenarikan->get()->getRow()->total ?? 0;
        //     echo "<pre>HASIL TOTAL PENARIKAN: " . $totalPenarikan . "</pre>";
            
        //     $saldo = $totalSetoran - $totalPenarikan;
        //     echo "<pre>SALDO AKHIR: " . $saldo . "</pre>";
            
        //     die(); // Hentikan eksekusi
        // }
        // //========================================


            
        //     public function getSaldo($nasabahId, $bankId, $excludeTrxId = null)
        //     {


        //             var_dump($nasabahId, $bankId); die();
        //         $db = \Config\Database::connect();
                
        //         // // Total setoran
        //         // $totalSetoran = $db->table('tb_terima_detail d')
        //         //     ->select('SUM(d.jumlah * d.harga) as total')
        //         //     ->join('tb_terima_header h', 'd.trx_id = h.trx_id')
        //         //     ->where('h.nasabah_id', $nasabahId)
        //         //     ->where('h.bank_id', $bankId)
        //         //     ->get()
        //         //     ->getRow()
        //         //     ->total ?? 0;



        //             // Debug: lihat query total setoran
        // $builderSetoran = $db->table('tb_terima_detail d')
        //     ->select('SUM(d.jumlah * d.harga) as total')
        //     ->join('tb_terima_header h', 'd.trx_id = h.trx_id')
        //     ->where('h.nasabah_id', $nasabahId)
        //     ->where('h.bank_id', $bankId);

        // // Tampilkan SQL
        // $sql = $builderSetoran->getCompiledSelect();
        // echo "<pre>SQL Setoran: " . htmlspecialchars($sql) . "</pre>";

        // // Jalankan query untuk mendapatkan hasil
        // $resultSetoran = $builderSetoran->get()->getRow();
        // $totalSetoran = $resultSetoran->total ?? 0;
        // echo "<pre>Hasil total setoran: " . $totalSetoran . "</pre>";

        // // Hentikan eksekusi sementara
        // //die();

        //         // Total penarikan (kecuali transaksi yang sedang diedit)
        //         // $builder = $db->table('tb_tarik_dana')
        //         //     ->select('SUM(jumlah_dana) as total')
        //         //     ->where('nasabah_id', $nasabahId)
        //         //     ->where('bank_id', $bankId);
        //         // if ($excludeTrxId) {
        //         //     $builder->where('trx_id !=', $excludeTrxId);
        //         // }
        //         // $totalPenarikan = $builder->get()->getRow()->total ?? 0;







        //         // Total penarikan (kecuali transaksi yang sedang diedit)
        //         $builder = $db->table('tb_tarik_dana')
        //             ->select('SUM(jumlah_dana) as total')
        //             ->where('nasabah_id', $nasabahId)
        //             ->where('bank_id', $bankId);
        //         if ($excludeTrxId) {
        //             $builder->where('trx_id !=', $excludeTrxId);
        //         }

        //         // Debug: lihat SQL
        //         $sql = $builder->getCompiledSelect();
        //         echo "<pre>SQL Penarikan: " . htmlspecialchars($sql) . "</pre>";

        //         // Eksekusi query untuk mendapatkan hasil
        //         $result = $builder->get()->getRow();
        //         $totalPenarikan = $result->total ?? 0;
        //         echo "<pre>Hasil total penarikan: " . $totalPenarikan . "</pre>";
        //         //die(); // Hentikan sementara untuk melihat output


        //         return $totalSetoran - $totalPenarikan;
        //     }
            

        //     // public function getSaldo($nasabahId, $bankId, $excludeTrxId = null)
        //     // {
        //     //     // Total setoran
        //     //     // $db = \Config\Database::connect();
        //     //     // $totalSetoran = $db->table('tb_terima_detail d')
        //     //     //     ->select('SUM(d.jumlah * d.harga) as total')
        //     //     //     ->join('tb_terima_header h', 'd.trx_id = h.trx_id')
        //     //     //     ->where('h.nasabah_id', $nasabahId)
        //     //     //     ->where('h.bank_id', $bankId)
        //     //     //     ->get()
        //     //     //     ->getRow()
        //     //     //     ->total ?? 0;


        //     //      $bankId = session()->get('bank_id');
        //     // $db = \Config\Database::connect();
            
        //     // // Query total setoran
        //     // $querySetoran = $db->table('tb_terima_detail d')
        //     //     ->select('SUM(d.jumlah * d.harga) as total')
        //     //     ->join('tb_terima_header h', 'd.trx_id = h.trx_id')
        //     //     ->where('h.nasabah_id', $nasabahId)
        //     //     ->where('h.bank_id', $bankId)
        //     //     ->getCompiledSelect();
            
        //     // // Tampilkan query dan hentikan
        //     // echo "Query Setoran: " . $querySetoran;
        //     // die();   

        //     //     // Total penarikan (kecuali transaksi yang sedang diedit)
        //     //     $builder = $db->table('tb_tarik_dana')
        //     //         ->select('SUM(jumlah_dana) as total')
        //     //         ->where('nasabah_id', $nasabahId)
        //     //         ->where('bank_id', $bankId);
        //     //     if ($excludeTrxId) {
        //     //         $builder->where('trx_id !=', $excludeTrxId);
        //     //     }
        //     //     $totalPenarikan = $builder->get()->getRow()->total ?? 0;

        //     //     return $totalSetoran - $totalPenarikan;
        //     // }


}