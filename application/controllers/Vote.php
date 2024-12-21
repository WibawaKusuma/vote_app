<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vote extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Vote_model');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->model('Vote_model');

        $data['pemilih'] = $this->db->get_where('mPemilih', ['NIM' => $this->session->userdata('nim')])->row_array();
        $id_prodi = $data['pemilih']['id_prodi'];
        $data['kandidat'] = $this->Vote_model->get_all_kandidat($id_prodi);

        // print_r($data);
        // exit;
        $this->load->view('vote/form', $data);
    }

    public function create()
    {
        // Ambil data pemilih berdasarkan sesi
        $data['pemilih'] = $this->db->get_where('mPemilih', ['NIM' => $this->session->userdata('nim')])->row_array();

        if (!$data['pemilih']) {
            $this->session->set_flashdata('error', 'Data pemilih tidak ditemukan.');
            redirect('vote');
            return;
        }

        // Ambil input dari form
        $dataInput = $this->input->post('p');

        // Validasi keberadaan data
        if (!isset($dataInput['nim']) || !isset($dataInput['password'])) {
            $this->session->set_flashdata('error', 'Data pemilih tidak lengkap.');
            redirect('vote');
            return;
        }

        // Tambahkan data lainnya
        $dataInput['Created_at'] = date('Y-m-d H:i:s');
        $dataInput['sudah_memilih'] = 1;

        // Data untuk update tabel mPemilih
        $data2 = ['sudah_memilih' => 1];

        // Cek apakah id_pemilih sudah ada dalam database
        if ($this->Vote_model->is_transaction_exists($data['pemilih']['id_pemilih'])) {
            $this->session->set_flashdata('error', 'Anda sudah memilih!<br>Voting ganda tidak diperbolehkan!');
            redirect('vote/hasil_vote');
            return;
        }

        // Hash nim dan password untuk keamanan
        $dataInput['nim'] = password_hash($dataInput['nim'], PASSWORD_BCRYPT);
        $dataInput['password'] = password_hash($dataInput['password'], PASSWORD_BCRYPT);

        // Transaksi untuk memastikan data tersimpan dan terupdate dengan benar
        $this->db->trans_start();

        // Insert data ke tabel trhasilVoting
        $this->Vote_model->insert_data($dataInput, 'trhasilVoting');

        // Update data ke tabel mPemilih
        $this->db->where('id_pemilih', $data['pemilih']['id_pemilih']);
        $this->db->update('mPemilih', $data2);

        $this->db->trans_complete();

        // Cek status transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            redirect('vote');
        } else {
            $this->session->set_flashdata('success', 'Vote berhasil disubmit dan status diperbarui!');
            redirect('vote/hasil_vote');
        }
    }

    public function hasil_vote()
    {
        $this->load->model('Vote_model');

        $kandidat_votes = $this->db->get_where('mPemilih', ['NIM' => $this->session->userdata('nim')])->row_array();
        // print_r($kandidat_votes);
        // exit;
        $id_prodi = $kandidat_votes['id_prodi'];

        $kandidat_votes = $this->Vote_model->get_kandidat_with_votes($id_prodi);
        $this->load->view('vote/hasil_vote', ['kandidat_votes' => $kandidat_votes]);
    }
}
