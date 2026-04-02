<?php namespace App\Controllers;

use App\Models\NasabahModel;
use App\Models\SampahModel;
use App\Models\TerimaHeaderModel;
use App\Models\TerimaDetailModel;
use App\Models\BankModel;

class Setoran extends BaseController
{
    public function index()
    {
        // List of transactions (header only) – optional
        $headerModel = new TerimaHeaderModel();
        $bankId = session()->get('bank_id');
        $data['transactions'] = $headerModel
            ->select('tb_terima_header.*, tb_nasabah.nasabah_nama,
                (SELECT SUM(jumlah * harga) FROM tb_terima_detail WHERE trx_id = tb_terima_header.trx_id) as total')
            ->join('tb_nasabah', 'tb_nasabah.nasabah_id = tb_terima_header.nasabah_id')
            ->where('tb_terima_header.bank_id', $bankId)
            ->orderBy('tb_terima_header.tanggal', 'DESC')
            ->findAll();
        $data['title'] = 'Daftar Setoran';
        return view('layouts/header', $data)
             . view('setoran/index', $data)
             . view('layouts/footer');
    }

    public function create()
    {
        $bankId = session()->get('bank_id');

        $nasabahModel = new NasabahModel();
        $data['nasabah'] = $nasabahModel->getByBank($bankId);

        $sampahModel = new SampahModel();
        $data['sampah'] = $sampahModel->getSampahByBank($bankId);

        $data['title'] = 'Form Setoran';
        $data['isEdit'] = false;
        $data['header'] = null;
        $data['details'] = [];

        return view('layouts/header', $data)
             . view('setoran/create', $data)
             . view('layouts/footer');
    }

    public function edit($trxId)
    {
        $bankId = session()->get('bank_id');
        $headerModel = new TerimaHeaderModel();
        $detailModel = new TerimaDetailModel();

        $header = $headerModel->where('trx_id', $trxId)->where('bank_id', $bankId)->first();
        if (!$header) {
            return redirect()->to('/setoran')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Fix: join tb_satuan to get satuan_nama
        $details = $detailModel
            ->select('tb_terima_detail.*, tb_satuan.satuan_nama')
            ->join('tb_sampah', 'tb_sampah.sampah_id = tb_terima_detail.sampah_id')
            ->join('tb_satuan', 'tb_satuan.satuan_id = tb_sampah.satuan_id', 'left')
            ->where('trx_id', $trxId)
            ->findAll();

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

    
    
    public function store()
    {
        $session = session();
        $userId = $session->get('user_id');
        $bankId = $session->get('bank_id');

        $post = $this->request->getPost();

        if (!isset($post['sampah_id']) || count($post['sampah_id']) == 0) {
            return redirect()->back()->with('error', 'Minimal satu detail harus diisi.');
        }
        if (empty($post['tanggal'])) {
            return redirect()->back()->with('error', 'Tanggal harus diisi.');
        }

        // Convert date from dd-mm-yyyy to yyyy-mm-dd
        $tanggal = \DateTime::createFromFormat('d-m-Y', $post['tanggal']);
        if (!$tanggal) {
            return redirect()->back()->with('error', 'Format tanggal salah. Gunakan dd-mm-yyyy.');
        }
        $tanggalDb = $tanggal->format('Y-m-d');

        // Generate trx_id from bank nick_name
        $bankModel = new BankModel();
        $bank = $bankModel->find($bankId);
        $nick = $bank ? $bank['nick_name'] : 'BANK';
        $trxId = $nick . '-' . date('YmdHis');

        $db = \Config\Database::connect();
        $db->transStart();

        $headerModel = new TerimaHeaderModel();
        $headerData = [
            'trx_id'      => $trxId,
            'tanggal'     => $tanggalDb,
            'user_id'     => $userId,
            'nasabah_id'  => $post['nasabah_id'],
            'bank_id'     => $bankId,
            'catatan'     => $post['catatan'] ?? null,
        ];
        $headerModel->insert($headerData);

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

        return redirect()->to('/setoran/detail')->with('success', 'Setoran berhasil disimpan.');
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
        if (empty($post['tanggal'])) {
            return redirect()->back()->with('error', 'Tanggal harus diisi.');
        }

        // Convert date from dd-mm-yyyy to yyyy-mm-dd
        $tanggal = \DateTime::createFromFormat('d-m-Y', $post['tanggal']);
        if (!$tanggal) {
            return redirect()->back()->with('error', 'Format tanggal salah. Gunakan dd-mm-yyyy.');
        }
        $tanggalDb = $tanggal->format('Y-m-d');


        $db = \Config\Database::connect();
        $db->transStart();

        // In store() and update(), before using $post['tanggal']:
        $tanggal = \DateTime::createFromFormat('d-m-Y', $post['tanggal']);
        if ($tanggal) {
            $tanggalDb = $tanggal->format('Y-m-d');
        } else {
            return redirect()->back()->with('error', 'Format tanggal salah.');
        }

        $headerModel = new TerimaHeaderModel();
        $headerData = [
            'nasabah_id' => $post['nasabah_id'],
            'tanggal'    => $tanggalDb,
            'catatan'    => $post['catatan'] ?? null,
        ];
        $headerModel->update($trxId, $headerData);

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

    public function delete($trxId)
    {
        $bankId = session()->get('bank_id');
        $headerModel = new TerimaHeaderModel();
        $detailModel = new TerimaDetailModel();

        $header = $headerModel->where('trx_id', $trxId)->where('bank_id', $bankId)->first();
        if (!$header) {
            return redirect()->to('/setoran')->with('error', 'Transaksi tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $detailModel->where('trx_id', $trxId)->delete();
        $headerModel->delete($trxId);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }

        return redirect()->to('/setoran/detail')->with('success', 'Setoran berhasil dihapus.');
    }

    public function detail()
    {
        $bankId = session()->get('bank_id');
        $db = \Config\Database::connect();
        $builder = $db->table('view_detail_terima'); // Make sure this view exists
        $builder->where('bank_id', $bankId);
        $data['transactions'] = $builder->get()->getResultArray();

        $data['title'] = 'Detail Setoran';
        return view('layouts/header', $data)
             . view('setoran/detail', $data)
             . view('layouts/footer');
    }
}