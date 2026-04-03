<?php

namespace App\Controllers;

use App\Models\NasabahModel;
use App\Models\TarikDanaModel;
use App\Models\BankModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class TarikDana extends BaseController
{
    public function index()
    {
        $bankId = session()->get('bank_id');
        $model = new TarikDanaModel();
        $data['transactions'] = $model
            ->select('tb_tarik_dana.*, tb_nasabah.nasabah_nama')
            ->join('tb_nasabah', 'tb_nasabah.nasabah_id = tb_tarik_dana.nasabah_id')
            ->where('tb_tarik_dana.bank_id', $bankId)
            ->orderBy('tb_tarik_dana.tanggal', 'DESC')
            ->findAll();
        $data['title'] = 'Daftar Penarikan';
        return view('layouts/header', $data)
            . view('tarikdana/index', $data)
            . view('layouts/footer');
    }

    public function create()
    {
        $bankId = session()->get('bank_id');
        $nasabahModel = new NasabahModel();
        $data['nasabah'] = $nasabahModel->where('bank_id', $bankId)->findAll();
        $data['title'] = 'Form Penarikan Dana';
        $data['isEdit'] = false;
        return view('layouts/header', $data)
            . view('tarikdana/create', $data)
            . view('layouts/footer');
    }

    public function edit($trxId)
    {
        $bankId = session()->get('bank_id');
        $model = new TarikDanaModel();
        $data['header'] = $model
            ->select('tb_tarik_dana.*, tb_nasabah.nasabah_nama')
            ->join('tb_nasabah', 'tb_nasabah.nasabah_id = tb_tarik_dana.nasabah_id')
            ->where('tb_tarik_dana.trx_id', $trxId)
            ->where('tb_tarik_dana.bank_id', $bankId)
            ->first();
        if (!$data['header']) {
            return redirect()->to('/tarikdana')->with('error', 'Transaksi tidak ditemukan.');
        }
        $nasabahModel = new NasabahModel();
        $data['nasabah'] = $nasabahModel->where('bank_id', $bankId)->findAll();
        $data['title'] = 'Edit Penarikan Dana';
        $data['isEdit'] = true;
        return view('layouts/header', $data)
            . view('tarikdana/create', $data)
            . view('layouts/footer');
    }

    public function store()
    {
        $session = session();
        $userId = $session->get('user_id');
        $bankId = $session->get('bank_id');
        $post = $this->request->getPost();

        if (empty($post['nasabah_id']) || empty($post['jumlah_dana']) || empty($post['tanggal'])) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        // Konversi tanggal dd-mm-yyyy ke yyyy-mm-dd
        $tanggal = \DateTime::createFromFormat('d-m-Y', $post['tanggal']);
        if (!$tanggal) {
            return redirect()->back()->with('error', 'Format tanggal salah. Gunakan dd-mm-yyyy.');
        }
        $tanggalDb = $tanggal->format('Y-m-d');

        // Cek saldo nasabah (tanpa exclude karena transaksi baru)
        $nasabahModel = new NasabahModel();
        $saldo = $nasabahModel->getSaldo($post['nasabah_id'], $bankId);
        $jumlah = (float) $post['jumlah_dana'];
        if ($jumlah > $saldo) {
            return redirect()->back()->with('error', 'Jumlah penarikan melebihi saldo nasabah (Saldo: Rp ' . number_format($saldo, 0, ',', '.') . ')')->withInput();
        }

        // Generate trx_id dari nick_name bank
        $bankModel = new BankModel();
        $bank = $bankModel->find($bankId);
        $nick = $bank ? $bank['nick_name'] : 'BANK';
        $trxId = $nick . '-' . date('YmdHis');

        $model = new TarikDanaModel();
        $data = [
            'trx_id'      => $trxId,
            'tanggal'     => $tanggalDb,
            'nasabah_id'  => $post['nasabah_id'],
            'jumlah_dana' => $jumlah,
            'catatan'     => $post['catatan'] ?? null,
            'user_id'     => $userId,
            'bank_id'     => $bankId,
            'biaya_admin' => 0, // hardcode 0
        ];
        if ($model->insert($data)) {
            return redirect()->to('/tarikdana?cetak=' . $trxId)->with('success', 'Penarikan berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function update($trxId)
    {
        $session = session();
        $userId = $session->get('user_id');
        $bankId = $session->get('bank_id');
        $post = $this->request->getPost();

        if (empty($post['nasabah_id']) || empty($post['jumlah_dana']) || empty($post['tanggal'])) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        $tanggal = \DateTime::createFromFormat('d-m-Y', $post['tanggal']);
        if (!$tanggal) {
            return redirect()->back()->with('error', 'Format tanggal salah. Gunakan dd-mm-yyyy.');
        }
        $tanggalDb = $tanggal->format('Y-m-d');

        // Cek saldo dengan mengecualikan transaksi yang sedang diedit
        $nasabahModel = new NasabahModel();
        $saldo = $nasabahModel->getSaldo($post['nasabah_id'], $bankId, $trxId);
        $jumlah = (float) $post['jumlah_dana'];
        if ($jumlah > $saldo) {
            return redirect()->back()->with('error', 'Jumlah penarikan melebihi saldo nasabah (Saldo: Rp ' . number_format($saldo, 0, ',', '.') . ')')->withInput();
        }

        $model = new TarikDanaModel();
        $data = [
            'tanggal'     => $tanggalDb,
            'nasabah_id'  => $post['nasabah_id'],
            'jumlah_dana' => $jumlah,
            'catatan'     => $post['catatan'] ?? null,
            'user_id'     => $userId,
            'bank_id'     => $bankId,
            'biaya_admin' => 0,
        ];
        if ($model->update($trxId, $data)) {
            return redirect()->to('/tarikdana')->with('success', 'Penarikan berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    public function delete($trxId)
    {
        $bankId = session()->get('bank_id');
        $model = new TarikDanaModel();
        $trans = $model->where('trx_id', $trxId)->where('bank_id', $bankId)->first();
        if (!$trans) {
            return redirect()->to('/tarikdana')->with('error', 'Transaksi tidak ditemukan.');
        }
        $model->delete($trxId);
        return redirect()->to('/tarikdana')->with('success', 'Penarikan dihapus.');
    }

    public function getSaldo($nasabahId)
    {
        $bankId = session()->get('bank_id');
        if (empty($bankId)) {
            return $this->response->setJSON(['error' => 'Bank ID tidak ditemukan di session']);
        }
        $exclude = $this->request->getGet('exclude');
        $nasabahModel = new NasabahModel();
        $saldo = $nasabahModel->getSaldo($nasabahId, $bankId, $exclude);
        return $this->response->setJSON(['saldo' => $saldo]);
    }

    public function cetakBukti($trxId)
    {
        date_default_timezone_set('Asia/Jakarta');
        $waktu_cetak = date('d-m-Y H:i:s');

        $bankId = session()->get('bank_id');
        $model = new TarikDanaModel();
        $header = $model
            ->select('tb_tarik_dana.*, tb_nasabah.nasabah_nama')
            ->join('tb_nasabah', 'tb_nasabah.nasabah_id = tb_tarik_dana.nasabah_id')
            ->where('tb_tarik_dana.trx_id', $trxId)
            ->where('tb_tarik_dana.bank_id', $bankId)
            ->first();

        if (!$header) {
            return redirect()->to('/tarikdana')->with('error', 'Transaksi tidak ditemukan.');
        }

        $isReprint = !empty($header['printed_at']);
        if (!$isReprint) {
            $model->update($trxId, ['printed_at' => date('Y-m-d H:i:s')]);
        }

        $data = [
            'header'      => $header,
            'isReprint'   => $isReprint,
            'bank_nama'   => session()->get('bank_nama'),
            'petugas'     => session()->get('user_nama'),
            'waktu_cetak' => $waktu_cetak,
        ];

        $html = view('tarikdana/bukti_pdf', $data);
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("bukti_tarik_{$trxId}.pdf", ['Attachment' => false]);
        exit;
    }

    // Optional: method untuk testing
    public function testSaldo()
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT SUM(d.jumlah * d.harga) as total
            FROM tb_terima_detail d
            JOIN tb_terima_header h ON d.trx_id = h.trx_id
            WHERE h.nasabah_id = 1 AND h.bank_id = 1
        ");
        $total = $query->getRow()->total ?? 0;
        echo "Total setoran nasabah 1: " . $total;
    }
}