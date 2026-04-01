<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tb_user';          // your table name
    protected $primaryKey = 'user_id';     // adjust if different
    protected $allowedFields = ['user_nama', 'password', 'level_id', 'bank_id'];
}