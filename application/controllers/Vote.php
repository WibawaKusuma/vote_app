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
        $this->load->model('Vote_model'); // Muat model

        $data['pemilih'] = $this->db->get_where('mPemilih', ['NIM' => $this->session->userdata('nim')])->row_array();

        // Pastikan id_prodi diambil dari data pemilih
        $id_prodi = $data['pemilih']['id_prodi'];

        $data['kandidat'] = $this->Vote_model->get_all_kandidat($id_prodi); // Panggil fungsi dari model

        // print_r($data);
        // exit;
        $this->load->view('vote/form', $data);
        // print_r($data['user']);
        // exit;
    }

    public function create()
    {
        $data = $this->input->post('p');

        // Validasi keberadaan data
        if (!isset($data['id_pemilih']) || !isset($data['nim'])) {
            $this->session->set_flashdata('error', 'Data pemilih tidak lengkap.');
            redirect('vote');
            return;
        }

        $data['Created_at'] = date('Y-m-d H:i:s');
        $data['sudah_memilih'] = 1;

        // Cek apakah id_pemilih sudah ada dalam database
        if ($this->Vote_model->is_transaction_exists($data['id_pemilih'])) {
            $this->session->set_flashdata('error', 'Anda sudah memilih!<br>Voting ganda tidak diperbolehkan!');
            // $this->load->view('vote/intermediate_redirect');
            redirect('vote/hasil_vote');
            return;
        }

        // Hash nim sebelum menyimpan
        $data['nim'] = password_hash($data['nim'], PASSWORD_BCRYPT);
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        // Insert data ke database
        if ($this->Vote_model->insert_data($data, 'trhasilVoting')) {

            // print_r($data);
            // exit;
            $this->session->set_flashdata('success', 'Vote berhasil disubmit!');
            // $this->load->view('vote/success_redirect');
            redirect('vote/hasil_vote');

            return;
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan vote. Silakan coba lagi.');
            redirect('vote');
        }
    }

    // public function hasil_vote()
    // {
    //     $kandidat = $this->Vote_model->get_kandidat_with_votes(); // Fungsi untuk mengambil data kandidat dan jumlah suara
    //     $this->load->view('vote/success_redirect', ['kandidat' => $kandidat]);
    // }

    // public function hasil_vote()
    // {
    //     $this->load->model('Vote_model');
    //     $kandidat_votes = $this->db->get_where('mPemilih', ['NIM' => $this->session->userdata('nim')])->row_array();
    //     $id_prodi = $kandidat_votes['id_prodi'];
    //     // print_r($id_prodi);
    //     // exit;
    //     $kandidat_votes = $this->Vote_model->get_kandidat_with_votes($id_prodi);
    //     // print_r($kandidat_votes);
    //     // exit;
    //     // $kandidat_votes = 1;

    //     $this->load->view('vote/hasil_vote', ['kandidat_votes' => $kandidat_votes]);
    // }

    public function hasil_vote()
    {
        $this->load->model('Vote_model');

        // Ambil data id_prodi pengguna
        $kandidat_votes = $this->db->get_where('mPemilih', ['NIM' => $this->session->userdata('nim')])->row_array();
        // print_r($kandidat_votes);
        // exit;
        $id_prodi = $kandidat_votes['id_prodi'];

        // Ambil hasil vote berdasarkan id_prodi
        $kandidat_votes = $this->Vote_model->get_kandidat_with_votes($id_prodi);

        // Kirim data ke view
        $this->load->view('vote/hasil_vote', ['kandidat_votes' => $kandidat_votes]);
    }
}
