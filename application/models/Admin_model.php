<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    // public function add_kandidat($data)
    // {
    //     $this->db->insert('mAcaraVoteDetail', $data);
    //     return $this->db->affected_rows();
    // }

    // public function get_kandidat_by_acara($id_acara)
    // {
    //     return $this->db->get_where('mAcaraVoteDetail', ['id_acara' => $id_acara])->result();
    // }

    public function get_acaravote($table)
    {
        return $this->db->get($table);
    }

    public function save_vote($data)
    {
        $this->db->insert('mAcaraVote', $data); // Menyimpan data acara
        return $this->db->affected_rows() > 0;
    }

    public function update_vote($no_acara, $data)
    {
        // Update data di tabel mAcaraVote berdasarkan no_acara
        $this->db->where('no_acara', $no_acara);  // Pastikan 'no_acara' adalah kondisi yang benar
        return $this->db->update('mAcaraVote', $data);  // Pastikan hanya kolom yang valid yang diperbarui
    }

    public function get_vote_by_no_acara($no_acara)
    {
        $this->db->where('no_acara', $no_acara);
        $query = $this->db->get('mAcaraVote');
        return $query->row();  // Mengembalikan satu baris data
    }

    public function save_vote_detail($data)
    {
        $this->db->insert('mAcaraVoteDetail', $data); // Menyimpan detail kandidat
        return $this->db->affected_rows() > 0;
    }

    public function get_last_insert_id()
    {
        return $this->db->insert_id(); // Mengambil ID terakhir yang disimpan
    }

    public function generate_reservation_number()
    {
        // Contoh format nomor reservasi: RES-20240926-001
        $today = date('Ymd'); // Mengambil tanggal hari ini (format: 20240926)

        // Ambil nomor urut terakhir dari tabel reservasi yang dibuat hari ini
        $this->db->like('no_acara', 'VOTE-' . $today, 'after');
        $this->db->order_by('no_acara', 'DESC');
        $last_reservation = $this->db->get('mAcaraVote')->row();

        // Jika ada data terakhir, ambil angka urutannya, jika tidak, mulai dari 001
        if ($last_reservation) {
            $last_number = intval(substr($last_reservation->no_acara, -3)) + 1;
            $no_acara = 'VOTE-' . $today . '-' . sprintf('%03d', $last_number);
        } else {
            $no_acara = 'VOTE-' . $today . '-001';
        }
        return $no_acara;
    }

    public function get_detail_kandidat($no_acara)
    {
        // Query untuk mengambil data kandidat yang terkait dengan no_acara
        $this->db->select('a.*');
        $this->db->from('macaravotedetail a');
        $this->db->join('macaravote b', 'b.no_acara = a.no_acara', 'inner');
        $this->db->where('b.no_acara', $no_acara);  // Kondisi no_acara
        $query = $this->db->get();

        // Mengembalikan hasil query
        return $query->result();
    }

    public function delete_kandidat($id_detail)
    {
        // Melakukan query untuk menghapus data kandidat berdasarkan id_detail
        $this->db->where('id_detail', $id_detail);
        return $this->db->delete('mAcaraVoteDetail'); // Pastikan nama tabel sesuai dengan yang Anda gunakan
    }









    public function get_all($table)
    {
        return $this->db->get($table);
    }

    public function get_prodi()
    {
        $this->db->select('id_prodi, deskripsi');  // Pilih kolom yang dibutuhkan
        $this->db->from('mProdi');  // Nama tabel prodi
        $this->db->order_by('id_prodi', 'ASC');  // Urutkan berdasarkan id_prodi
        $this->db->where('deskripsi !=', 'administrasi');  // Perbaiki kondisi WHERE dengan operator !=
        $query = $this->db->get();  // Eksekusi query

        // Jika ada data, kembalikan sebagai array
        if ($query->num_rows() > 0) {
            return $query->result();  // Mengembalikan array objek
        } else {
            return [];  // Jika tidak ada data, kembalikan array kosong
        }
    }
}
