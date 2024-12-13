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

    public function get_all_kandidat()
    {
        $this->db->select('a.*, b.*'); // Pilih kolom yang diperlukan
        $this->db->from('macaravotedetail a'); // Tabel utama
        $this->db->join('macaravote b', 'a.no_acara = b.no_acara', 'inner'); // Join tabel macaravote
        $this->db->where('b.status', 1);
        $query = $this->db->get(); // Eksekusi query

        return $query->result(); // Mengembalikan hasil sebagai array objek
    }

    public function get_kandidat_with_votes()
    {
        // Query untuk mengambil nama kandidat dan jumlah suara
        $this->db->select('a.*, COUNT(b.pilihan) as jumlah_suara');
        $this->db->from('macaravotedetail a'); // Alias 'a' untuk tabel macaravotedetail
        $this->db->join('trhasilVoting b', 'a.id_detail = b.pilihan', 'left'); // Alias 'b' untuk trhasilVoting
        $this->db->group_by('a.id_detail'); // Kelompokkan berdasarkan id_detail dari tabel macaravotedetail
        $this->db->order_by('jumlah_suara', 'DESC'); // Urutkan berdasarkan jumlah suara tertinggi
        $query = $this->db->get();
        $kandidat_votes = $query->result_array();

        // Hitung total suara yang masuk
        $this->db->select('COUNT(*) as total_votes');
        $this->db->from('trhasilVoting');
        $query_total = $this->db->get();
        $total_votes = $query_total->row()->total_votes;

        // Jika ada total suara, hitung persentase suara setiap kandidat
        if ($total_votes > 0) {
            foreach ($kandidat_votes as &$kandidat) {
                $kandidat['persentase'] = ($kandidat['jumlah_suara'] / $total_votes) * 100;
            }
        } else {
            // Jika tidak ada suara, set persentase ke 0
            foreach ($kandidat_votes as &$kandidat) {
                $kandidat['persentase'] = 0;
            }
        }

        return $kandidat_votes;
    }
}
