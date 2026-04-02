<?php namespace App\Controllers;

use App\Models\NasabahModel;
use App\Models\SampahModel;
use App\Models\TerimaHeaderModel;
use App\Models\TerimaDetailModel;
use App\Models\BankModel;

class Setoran extends BaseController
{

    public function create()
    {
        $userId = session()->get('user_id');
        $bankId = session()->get('bank_id');

        $nasabahModel = new NasabahModel();
        $data['nasabah'] = $nasabahModel->getByBank($bankId);

        $sampahModel = new SampahModel();
        $data['sampah'] = $sampahModel->getSampahByBank($bankId);

        $data['title'] = 'Form Setoran';
        $data['isEdit'] = false;   // <-- Add this
        $data['header'] = null;    // optional, but used in view conditionals
        $data['details'] = [];     // optional

        return view('layouts/header', $data)
             . view('setoran/create', $data)
             . view('layouts/footer');
    }

    public function update($trxId)
    {
        $session = session();
        $userId = $session->get('user_id');
        $bankId = $session->get('bank_id');

        $post = $this->request->getPost();

        if (!isset($post['sampah_id']) || count($post['sampah_id']) == 0) {
            return redirect()->back()->with('error', 'Minimal satu detail harus diisi.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Update header
        $headerModel = new TerimaHeaderModel();
        $headerData = [
            'nasabah_id' => $post['nasabah_id'],
            'catatan'    => $post['catatan'] ?? null,
        ];
        $headerModel->update($trxId, $headerData);

        // Delete old details and insert new ones
        $detailModel = new TerimaDetailModel();
        $detailModel->where('trx_id', $trxId)->delete();

        foreach ($post['sampah_id'] as $i => $sampahId) {
            $detailModel->insert([
                'trx_id'    => $trxId,
                'sampah_id' => $sampahId,
                'jumlah'    => $post['jumlah'][$i],
                'harga'     => $post['harga'][$i],
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }

        return redirect()->to('/setoran/detail')->with('success', 'Setoran berhasil diperbarui.');
    }

    public function edit($trxId)
    {
        $bankId = session()->get('bank_id');
        $headerModel = new TerimaHeaderModel();
        $detailModel = new TerimaDetailModel();

        // Load header and verify it belongs to this bank
        $header = $headerModel->where('trx_id', $trxId)->where('bank_id', $bankId)->first();
        if (!$header) {
            return redirect()->to('/setoran')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Load details (including satuan for display)      


        $details = $detailModel
            ->select('tb_terima_detail.*, tb_sampah.sampah_nama, tb_satuan.satuan_nama')
            ->join('tb_sampah', 'tb_sampah.sampah_id = tb_terima_detail.sampah_id')
            ->join('tb_satuan', 'tb_satuan.satuan_id = tb_sampah.satuan_id', 'left')
            ->where('trx_id', $trxId)
            ->findAll();    

        // Prepare dropdown data (nasabah, sampah) same as create
        $nasabahModel = new NasabahModel();
        $sampahModel = new SampahModel();
        $data['nasabah'] = $nasabahModel->getByBank($bankId);
        $data['sampah'] = $sampahModel->getSampahByBank($bankId);

        $data['title'] = 'Edit Setoran';
        $data['isEdit'] = true;
        $data['header'] = $header;
        $data['details'] = $details;

        return view('layouts/header', $data)
             . view('setoran/create', $data)
             . view('layouts/footer');
    }

    public function detail()
    {
        $bankId = session()->get('bank_id');

        // Query the database view
        $db = \Config\Database::connect();
        $builder = $db->table('view_detail_terima');
        $builder->where('bank_id', $bankId);   // filter by bank (if view contains bank_id)
        $data['transactions'] = $builder->get()->getResultArray();

        $data['title'] = 'Detail Setoran';
        return view('layouts/header', $data)
             . view('setoran/detail', $data)
             . view('layouts/footer');
    }

    

    public function store()
    {
        $session = session();
        $userId = $session->get('user_id');
        $bankId = $session->get('bank_id');

        $post = $this->request->getPost();

        // Validate at least one detail
        if (!isset($post['sampah_id']) || count($post['sampah_id']) == 0) {
            return redirect()->back()->with('error', 'Minimal satu detail harus diisi.');
        }

        // Generate trx_id: nick_name from bank
        $bankModel = new BankModel();
        $bank = $bankModel->find($bankId);
        $nick = $bank ? $bank['nick_name'] : 'BANK';
        $trxId = $nick . '-' . date('YmdHis');

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert header
        $headerModel = new TerimaHeaderModel();
        $headerData = [
            'trx_id'      => $trxId,
            'tanggal'     => date('Y-m-d'),
            'user_id'     => $userId,
            'nasabah_id'  => $post['nasabah_id'],
            'bank_id'     => $bankId,
            'catatan'     => $post['catatan'] ?? null,
        ];
        $headerModel->insert($headerData);

        // Insert details
        $detailModel = new TerimaDetailModel();
        foreach ($post['sampah_id'] as $i => $sampahId) {
            $detailModel->insert([
                'trx_id'   => $trxId,
                'sampah_id'=> $sampahId,
                'jumlah'   => $post['jumlah'][$i],
                'harga'    => $post['harga'][$i],
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan data. Coba lagi.');
        }

        return redirect()->to('/setoran')->with('success', 'Setoran berhasil disimpan.');
    }
}