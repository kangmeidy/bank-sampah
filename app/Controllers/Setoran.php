<?php namespace App\Controllers;

use App\Models\NasabahModel;
use App\Models\SampahModel;
use App\Models\TerimaHeaderModel;
use App\Models\TerimaDetailModel;
use App\Models\BankModel;

use Dompdf\Dompdf;
use Dompdf\Options;

class Setoran extends BaseController
{
    public function index()
    {
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
        $sampahModel = new SampahModel();
        $data['nasabah'] = $nasabahModel->getByBank($bankId);
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

        // Convert date
        $tanggal = \DateTime::createFromFormat('d-m-Y', $post['tanggal']);
        if (!$tanggal) {
            return redirect()->back()->with('error', 'Format tanggal salah. Gunakan dd-mm-yyyy.');
        }
        $tanggalDb = $tanggal->format('Y-m-d');

        // Generate trx_id
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

        // Simpan flashdata untuk modal cetak
        //session()->setFlashdata('new_trx_id', $trxId);

        // Menjadi:
        session()->set('new_trx_id', $trxId);

        ////////
        //return redirect()->to('/setoran/detail')->with('success', 'Setoran berhasil disimpan.');
        /////// 
           
        // Ganti baris redirect menjadi:
        return redirect()->to('/setoran/detail?cetak=' . $trxId)->with('success', 'Setoran berhasil disimpan.');



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

        // Convert date
        $tanggal = \DateTime::createFromFormat('d-m-Y', $post['tanggal']);
        if (!$tanggal) {
            return redirect()->back()->with('error', 'Format tanggal salah. Gunakan dd-mm-yyyy.');
        }
        $tanggalDb = $tanggal->format('Y-m-d');

        $db = \Config\Database::connect();
        $db->transStart();

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
        $builder = $db->table('view_detail_terima');
        $builder->where('bank_id', $bankId);
        $data['transactions'] = $builder->get()->getResultArray();
        $data['title'] = 'Detail Setoran';
        return view('layouts/header', $data)
             . view('setoran/detail', $data)
             . view('layouts/footer');
    }

    public function cetakBukti($trxId)
    {
        date_default_timezone_set('Asia/Jakarta');
        $waktu_cetak = date('d-m-Y H:i:s');

        $bankId = session()->get('bank_id');
        $headerModel = new TerimaHeaderModel();
        $detailModel = new TerimaDetailModel();

        $header = $headerModel
            ->select('tb_terima_header.*, tb_nasabah.nasabah_nama')
            ->join('tb_nasabah', 'tb_nasabah.nasabah_id = tb_terima_header.nasabah_id')
            ->where('tb_terima_header.trx_id', $trxId)
            ->where('tb_terima_header.bank_id', $bankId)
            ->first();

        if (!$header) {
            return redirect()->to('/setoran/detail')->with('error', 'Transaksi tidak ditemukan.');
        }

        $details = $detailModel
            ->select('tb_terima_detail.*, tb_sampah.sampah_nama')
            ->join('tb_sampah', 'tb_sampah.sampah_id = tb_terima_detail.sampah_id')
            ->where('trx_id', $trxId)
            ->findAll();

        $isReprint = !empty($header['printed_at']);
        if (!$isReprint) {
            $headerModel->update($trxId, ['printed_at' => date('Y-m-d H:i:s')]);
            $header['printed_at'] = date('Y-m-d H:i:s');
        }

        $total = 0;
        foreach ($details as $d) {
            $total += $d['jumlah'] * $d['harga'];
        }

        $data = [
            'header'      => $header,
            'details'     => $details,
            'isReprint'   => $isReprint,
            'bank_nama'   => session()->get('bank_nama'),
            'petugas'     => session()->get('user_nama'),
            'total'       => $total,
            'waktu_cetak' => $waktu_cetak
        ];

        $html = view('setoran/bukti_pdf', $data);

        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("bukti_setoran_{$trxId}.pdf", ['Attachment' => false]);
        exit;
    }
}