<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vote_model extends CI_Model
{
    public function is_transaction_exists($id_pemilih)
    {
        $this->db->where('id_pemilih', $id_pemilih);
        $query = $this->db->get('trhasilVoting');
        return $query->num_rows() > 0;
    }

    public function insert_data($data, $table)
    {
        $this->db->insert($table, $data);
        return $this->db->affected_rows() > 0;
    }

    public function get_all_kandidat($id_prodi)
    {
        $this->db->select('a.*, b.*'); // Pilih kolom yang diperlukan
        $this->db->from('macaravotedetail a'); // Tabel utama
        $this->db->join('macaravote b', 'a.no_acara = b.no_acara', 'inner'); // Join tabel macaravote
        $this->db->where('b.status', 1);
        $this->db->where('b.id_prodi', $id_prodi);
        $query = $this->db->get(); // Eksekusi query

        return $query->result(); // Mengembalikan hasil sebagai array objek
    }

    // public function get_kandidat_with_votes($id_prodi = null)
    // {
    //     // Query untuk mengambil nama kandidat dan jumlah suara
    //     $this->db->select('a.*, b.id_prodi, COUNT(b.pilihan) as jumlah_suara');
    //     $this->db->from('macaravotedetail a'); // Alias 'a' untuk tabel macaravotedetail
    //     $this->db->join('trhasilVoting b', 'a.id_detail = b.pilihan', 'left'); // Alias 'b' untuk trhasilVoting

    //     // Tambahkan filter id_prodi jika ada
    //     if ($id_prodi) {
    //         $this->db->where('b.id_prodi', $id_prodi);
    //     }

    //     $this->db->group_by('a.id_detail'); // Kelompokkan berdasarkan id_detail dari tabel macaravotedetail
    //     $this->db->order_by('jumlah_suara', 'DESC'); // Urutkan berdasarkan jumlah suara tertinggi
    //     $query = $this->db->get();
    //     $kandidat_votes = $query->result_array();

    //     // Hitung total suara yang masuk untuk id_prodi
    //     $this->db->select('COUNT(*) as total_votes');
    //     $this->db->from('trhasilVoting');
    //     if ($id_prodi) {
    //         $this->db->where('id_prodi', $id_prodi);
    //     }
    //     $query_total = $this->db->get();
    //     $total_votes = $query_total->row()->total_votes;

    //     // Jika ada total suara, hitung persentase suara setiap kandidat
    //     if ($total_votes > 0) {
    //         foreach ($kandidat_votes as &$kandidat) {
    //             $kandidat['persentase'] = ($kandidat['jumlah_suara'] / $total_votes) * 100;
    //         }
    //     } else {
    //         // Jika tidak ada suara, set persentase ke 0
    //         foreach ($kandidat_votes as &$kandidat) {
    //             $kandidat['persentase'] = 0;
    //         }
    //     }

    //     return $kandidat_votes;
    // }

    // public function get_kandidat_with_votes($id_prodi = null)
    // {
    //     // Pastikan id_prodi diberikan sebelum menjalankan query
    //     if (!$id_prodi) {
    //         return [];
    //     }

    //     // Query untuk mengambil kandidat dan jumlah suara
    //     $this->db->select('a.*, COALESCE(COUNT(b.pilihan), 0) as jumlah_suara');
    //     $this->db->from('macaravotedetail a'); // Alias 'a' untuk tabel macaravotedetail
    //     $this->db->join('trhasilVoting b', 'a.id_detail = b.pilihan', 'left'); // LEFT JOIN tanpa filter id_prodi di ON
    //     $this->db->join('mAcaraVote c', 'b.id_prodi = c.id_prodi', 'left'); // LEFT JOIN tanpa filter id_prodi di ON
    //     $this->db->where('b.id_prodi', $id_prodi); // Filter id_prodi diterapkan di WHERE
    //     $this->db->group_by('a.id_detail'); // Kelompokkan berdasarkan id_detail dari tabel macaravotedetail
    //     $this->db->order_by('jumlah_suara', 'DESC'); // Urutkan berdasarkan jumlah suara tertinggi

    //     $query = $this->db->get();
    //     $kandidat_votes = $query->result_array();

    //     // Hitung total suara yang masuk untuk id_prodi
    //     $this->db->select('COUNT(*) as total_votes');
    //     $this->db->from('trhasilVoting');
    //     $this->db->where('id_prodi', $id_prodi);
    //     $query_total = $this->db->get();
    //     $total_votes = $query_total->row()->total_votes;

    //     // Jika ada total suara, hitung persentase suara setiap kandidat
    //     foreach ($kandidat_votes as &$kandidat) {
    //         $kandidat['persentase'] = ($total_votes > 0) ? round(($kandidat['jumlah_suara'] / $total_votes) * 100, 2) : 0;
    //     }

    //     return $kandidat_votes;
    // }

    public function get_kandidat_with_votes($id_prodi = null)
    {
        if (!$id_prodi) {
            return [];
        }

        // Query untuk mengambil semua kandidat
        $this->db->select('a.*, b.*');
        $this->db->from('macaravotedetail a'); // Alias 'a' untuk tabel macaravotedetail
        $this->db->join('mAcaraVote b', 'a.no_acara = b.no_acara', 'left');
        $this->db->where('b.id_prodi', $id_prodi);
        $kandidat_query = $this->db->get();
        $kandidat_data = $kandidat_query->result_array();

        // Query untuk menghitung jumlah suara berdasarkan id_prodi
        $this->db->select('b.pilihan, COUNT(b.pilihan) as jumlah_suara');
        $this->db->from('trhasilVoting b');
        $this->db->where('b.id_prodi', $id_prodi);
        $this->db->group_by('b.pilihan');
        $votes_query = $this->db->get();
        $votes_data = $votes_query->result_array();

        // Buat array jumlah suara dengan id_detail sebagai key
        $votes_count = [];
        foreach ($votes_data as $vote) {
            $votes_count[$vote['pilihan']] = $vote['jumlah_suara'];
        }

        // Gabungkan data kandidat dengan jumlah suara
        $total_votes = 0;
        foreach ($kandidat_data as &$kandidat) {
            $kandidat['jumlah_suara'] = isset($votes_count[$kandidat['id_detail']]) ? $votes_count[$kandidat['id_detail']] : 0; // Default 0 jika tidak ada suara
            $total_votes += $kandidat['jumlah_suara'];
        }

        // Hitung persentase suara untuk setiap kandidat
        foreach ($kandidat_data as &$kandidat) {
            $kandidat['persentase'] = ($total_votes > 0)
                ? round(($kandidat['jumlah_suara'] / $total_votes) * 100, 2)
                : 0;
        }

        // Urutkan berdasarkan jumlah suara (desc)
        usort($kandidat_data, function ($a, $b) {
            return $b['jumlah_suara'] - $a['jumlah_suara'];
        });

        return $kandidat_data;
    }
}
