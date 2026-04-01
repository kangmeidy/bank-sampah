<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $data = [
            [
                'user_nama' => 'user1',
                'password'  => password_hash('1234', PASSWORD_DEFAULT),
                'level_id'  => 3,
                'bank_id'   => 1,
            ],
            [
                'user_nama' => 'user2',
                'password'  => password_hash('1234', PASSWORD_DEFAULT),
                'level_id'  => 3,
                'bank_id'   => 1,
            ],
            [
                'user_nama' => 'user3',
                'password'  => password_hash('1234', PASSWORD_DEFAULT),
                'level_id'  => 3,
                'bank_id'   => 2,
            ],
            [
                'user_nama' => 'user4',
                'password'  => password_hash('1234', PASSWORD_DEFAULT),
                'level_id'  => 3,
                'bank_id'   => 2,
            ],
            [
                'user_nama' => 'user5',
                'password'  => password_hash('1234', PASSWORD_DEFAULT),
                'level_id'  => 3,
                'bank_id'   => 3,
            ],
        ];

        foreach ($data as $row) {
            $userModel->save($row);
        }
    }
}