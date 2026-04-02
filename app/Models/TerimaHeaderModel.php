<?php namespace App\Models;

use CodeIgniter\Model;

class TerimaHeaderModel extends Model
{
    protected $table = 'tb_terima_header';
    protected $primaryKey = 'trx_id';    
    protected $allowedFields = ['trx_id', 'tanggal', 'user_id', 'nasabah_id', 'bank_id', 'catatan', 'printed_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_date';
    protected $updatedField  = 'updated_date';
    protected $dateFormat    = 'datetime';
}