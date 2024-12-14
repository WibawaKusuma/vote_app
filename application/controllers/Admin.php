<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->library('session');
        // $this->load->library('form_validation');

        $this->load->library(['form_validation', 'upload', 'session']);
        $this->load->helper(['form', 'url']);


        $this->load->model('Admin_model');
        $this->load->model('Vote_model');
    }

    public function index()
    {
        // $data['user'] = $this->db->get_where('mPemilih', ['NIM' => $this->session->userdata('nim')])->row_array();
        $data['acaravote'] = $this->Admin_model->get_acaravote('mAcaraVote')->result();
        $data['title'] = 'admin';
        // print_r($data);
        // exit;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/dashboard');
        $this->load->view('templates/footer');


        // $this->load->view('admin/dashboard', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Voting',
            // 'user' =>  $this->db->get_where('muser', ['email' => $this->session->userdata('email')])->row_array(),
            'no_acara' => $this->Admin_model->generate_reservation_number(),
            'dropdown_prodi' => $this->Admin_model->get_prodi(),

            // 'dropdown_prodi' => $this->Admin_model->get_all('mProdi')->result(),
            // 'package' => $package
        ];
        // $data['dropdown_prodi'] = $this->Admin_model->get_prodi();

        // print_r($data['dropdown_prodi']);
        // exit;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/form', $data);
        $this->load->view('templates/footer');
    }


    public function create_vote()
    {
        $this->load->model('Admin_model');
        $vote_data = $this->input->post('p');

        // Validasi data input
        if (empty($vote_data['nama_acara']) || empty($vote_data['tanggal'])) {
            $this->session->set_flashdata('error', 'Nama acara dan tanggal wajib diisi!');
            redirect('admin/create_vote');
        }

        // Menambahkan waktu pembuatan
        $vote_data['created_at'] = date('Y-m-d H:i:s');

        // Menyimpan data acara
        if ($this->Admin_model->save_vote($vote_data)) {
            // Mendapatkan no_acara yang baru saja disimpan
            $no_acara = $this->Admin_model->get_last_insert_id(); // Ambil ID acara yang baru saja disimpan

            // Set flash message
            $this->session->set_flashdata('success', 'Data acara berhasil disimpan!');

            // Redirect ke halaman create_vote_detail dengan membawa no_acara
            redirect('admin/create_vote_detail/' . $no_acara);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data acara!');
            redirect('admin/create_vote');
        }
    }

    public function update_vote($no_acara)
    {
        $this->load->model('Admin_model');
        $detail_kandidat = $this->Admin_model->get_detail_kandidat($no_acara);

        // print_r($detail_kandidat);
        // exit;

        // Jika form disubmit
        if ($this->input->post()) {
            // Ambil data yang dikirim dari form
            $vote_data = $this->input->post('p');

            // print_r($vote_data);
            // exit;

            // Validasi input
            if (empty($vote_data['nama_acara']) || empty($vote_data['tanggal'])) {
                $this->session->set_flashdata('error', 'Nama acara dan tanggal wajib diisi!');
                redirect('admin/update_vote/' . $no_acara);  // Redirect ke halaman update
            }

            // Pastikan kolom 'no_acara' tidak dimasukkan dalam array $vote_data
            $vote_data['updated_at'] = date('Y-m-d H:i:s');  // Memasukkan waktu update

            // Update data
            if ($this->Admin_model->update_vote($no_acara, $vote_data)) {
                $this->session->set_flashdata('success', 'Data acara berhasil disimpan!');
                redirect('admin');  // Redirect ke halaman admin setelah update
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan data acara!');
                redirect('admin/update_vote/' . $no_acara);  // Jika gagal, redirect ke halaman update
            }
        } else {
            // Jika form belum disubmit, ambil data acara berdasarkan NoAcara
            $vote = $this->Admin_model->get_vote_by_no_acara($no_acara);
            if (!$vote) {
                show_404();  // Jika data tidak ditemukan, tampilkan halaman 404
            }

            // Siapkan data untuk view
            $data['title'] = 'Update Vote';
            $data['vote'] = $vote;
            $data['no_acara'] = $no_acara;
            $data['detail_kandidat'] = $detail_kandidat;
            $data['dropdown_prodi'] = $this->Admin_model->get_prodi(); // Contoh nama model dan metode


            // print_r($vote);
            // exit;

            // Tampilkan form dengan data
            $this->load->view('templates/header', $data);
            $this->load->view('templates/topbar');
            $this->load->view('templates/sidebar_admin');
            $this->load->view('admin/form', $data);  // Pastikan form menerima data 'vote'
            $this->load->view('templates/footer');
        }
    }

    public function create_vote_detail($no_acara)
    {
        // Periksa apakah no_acara ada di tabel mAcaraVote
        $acara = $this->Admin_model->get_vote_by_no_acara($no_acara);
        if (!$acara) {
            $this->session->set_flashdata('error', 'Nomor acara tidak ditemukan!');
            redirect('admin'); // Redirect ke halaman admin jika no_acara tidak ada
        }

        // Jika ada data POST, jalankan logika penyimpanan
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $detail_data = $this->input->post('d');

            // Validasi input
            $this->form_validation->set_rules('d[nama_kandidat]', 'Nama Kandidat', 'required');
            $this->form_validation->set_rules('d[visi]', 'Visi', 'required');
            $this->form_validation->set_rules('d[misi]', 'Misi', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/create_vote_detail/' . $no_acara);
            }

            // Konfigurasi upload
            $config['upload_path'] = './assets/template/img/kandidat';
            $config['allowed_types'] = 'jpeg|jpg|png|gif';
            $config['file_name'] = time() . '_' . $_FILES['image']['name'];
            $config['overwrite'] = FALSE;
            $config['max_size'] = 2048; // 2MB

            $this->upload->initialize($config);

            // Cek apakah ada file yang di-upload
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $this->session->set_flashdata('error', 'Tidak ada file yang dipilih atau file terlalu besar.');
                redirect('admin/create_vote_detail/' . $no_acara);
            }

            // Lakukan upload file
            if (!$this->upload->do_upload('image')) {
                $error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error', "Upload image gagal! Error: $error");
                redirect('admin/create_vote_detail/' . $no_acara);
            } else {
                $upload_data = $this->upload->data();
                $detail_data['image'] = $upload_data['file_name'];
            }

            $detail_data['created_at'] = date('Y-m-d H:i:s');
            $detail_data['no_acara'] = $no_acara;

            // Simpan ke database melalui model
            if ($this->Admin_model->save_vote_detail($detail_data)) {
                $this->session->set_flashdata('success', 'Detail kandidat berhasil disimpan!');
                redirect('admin/update_vote/' . $no_acara);
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan detail kandidat!');
                redirect('admin/create_vote_detail/' . $no_acara);
            }
        }

        // Menampilkan form dengan data dari no_acara
        $data = [
            'title' => 'Tambah Detail Kandidat',
            'vote' => $this->Admin_model->get_vote_by_no_acara($no_acara),
            'no_acara' => $no_acara
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar_admin');
        $this->load->view('admin/form_detail', $data);
        $this->load->view('templates/footer');
    }

    public function hapus_kandidat($id_detail, $no_acara)
    {
        $this->load->model('Admin_model');

        // Panggil model untuk menghapus data berdasarkan id_detail
        if ($this->Admin_model->delete_kandidat($id_detail)) {
            $this->session->set_flashdata('success', 'Kandidat berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kandidat!');
        }

        // Redirect kembali ke halaman create_vote_detail dengan membawa no_acara
        redirect('admin/update_vote/' . $no_acara);
    }
}
