<?php
namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('login');
    }


    public function authenticate()
    {
        $session = session();
        $model = new UserModel();

        $user_nama = $this->request->getVar('user_nama');
        $password = $this->request->getVar('password');

        $user = $model->where('user_nama', $user_nama)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Get bank name
                $bankModel = new \App\Models\BankModel();
                $bank = $bankModel->find($user['bank_id']);
                $bank_nama = $bank ? $bank['bank_nama'] : 'Unknown Bank';

                $sessionData = [
                    'user_id'   => $user['user_id'],
                    'user_nama' => $user['user_nama'],
                    'level_id'  => $user['level_id'],
                    'bank_id'   => $user['bank_id'],
                    'bank_nama' => $bank_nama,
                    'isLoggedIn'=> true
                ];
                $session->set($sessionData);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('msg', 'Password salah');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Username tidak ditemukan');
            return redirect()->to('/login');
        }
    }


    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
   
 




}