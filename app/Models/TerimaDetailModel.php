<?php namespace App\Models;

use CodeIgniter\Model;

class TerimaDetailModel extends Model
{
    protected $table = 'tb_terima_detail';
    protected $primaryKey = ['trx_id', 'sampah_id'];
    protected $allowedFields = ['trx_id', 'sampah_id', 'jumlah', 'harga'];
}