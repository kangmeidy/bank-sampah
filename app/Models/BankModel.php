<?php namespace App\Models;

use CodeIgniter\Model;

class BankModel extends Model
{
    protected $table = 'tb_bank';
    protected $primaryKey = 'bank_id';
    protected $allowedFields = ['bank_nama', 'nick_name', 'alamat', 'nama_kontak', 'hp_kontak', 'email'];
}