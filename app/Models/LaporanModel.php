<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Mendapatkan data saldo nasabah (setoran + penarikan)
     * @param string|null $search   Nama nasabah (LIKE)
     * @param int|null    $bankId   Filter bank_id
     * @return array
     */
    public function getSaldoNasabah($search = null, $bankId = null)
    {
        $sql = "
            SELECT
                all_trans.bank_id,
                all_trans.nasabah_id,
                n.nasabah_nama,
                all_trans.trx_id,
                all_trans.tanggal_setor,
                all_trans.total_nilai,
                all_trans.tanggal_tarik,
                all_trans.jumlah_dana,
                all_trans.tgl_order,
                SUM(
                    CASE
                        WHEN all_trans.tanggal_setor IS NOT NULL THEN all_trans.total_nilai
                        WHEN all_trans.tanggal_tarik IS NOT NULL THEN all_trans.jumlah_dana
                        ELSE 0
                    END
                ) OVER (PARTITION BY all_trans.nasabah_id ORDER BY all_trans.tgl_order, all_trans.trx_id) AS saldo
            FROM (
                -- Setoran
                SELECT
                    h.bank_id,
                    h.nasabah_id,
                    h.trx_id,
                    h.tanggal AS tanggal_setor,
                    SUM(d.jumlah * d.harga) AS total_nilai,
                    NULL AS tanggal_tarik,
                    NULL AS jumlah_dana,
                    h.tanggal AS tgl_order
                FROM tb_terima_header h
                JOIN tb_terima_detail d ON h.trx_id = d.trx_id
                GROUP BY h.trx_id

                UNION ALL

                -- Penarikan (hanya yang punya setoran sebelumnya)
                SELECT
                    t.bank_id,
                    t.nasabah_id,
                    t.trx_id,
                    NULL AS tanggal_setor,
                    NULL AS total_nilai,
                    t.tanggal AS tanggal_tarik,
                    -1 * t.jumlah_dana AS jumlah_dana,
                    t.tanggal AS tgl_order
                FROM tb_tarik_dana t
                WHERE EXISTS (
                    SELECT 1 FROM tb_terima_header h
                    WHERE h.nasabah_id = t.nasabah_id AND h.tanggal <= t.tanggal
                    LIMIT 1
                )
            ) all_trans
            LEFT JOIN tb_nasabah n ON all_trans.nasabah_id = n.nasabah_id
        ";

        $where = [];
        $params = [];

        if ($bankId !== null) {
            $where[] = "all_trans.bank_id = ?";
            $params[] = $bankId;
        }

        if (!empty($search)) {
            $where[] = "n.nasabah_nama LIKE ?";
            $params[] = "%{$search}%";
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY n.nasabah_nama, all_trans.tgl_order, all_trans.trx_id";

        $query = $this->db->query($sql, $params);
        return $query->getResultArray();
    }
}