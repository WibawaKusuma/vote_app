<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->form_validation->set_rules('nim', 'NIM', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login page';

            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $nim = $this->input->post('nim');
        $password = $this->input->post('password');

        // Ambil data pengguna berdasarkan NIM
        $user = $this->db->get_where('mPemilih', ['nim' => $nim])->row_array();

        // Cek jika user ada
        if ($user) {
            // Cek jika user aktif
            if ($user['status'] == 1) {
                // Cek passwordnya
                if ($password === $user['password']) {
                    $data = [
                        'id_pemilih' => $user['id_pemilih'],
                        'nim' => $user['nim'],
                    ];
                    $this->session->set_userdata($data);

                    if ($user['nim'] == 123) {
                        redirect('admin');
                    } else {
                        // Periksa apakah id_pemilih sudah ada di tabel trHasilVoting
                        $pemilih = $this->db->get_where('trHasilVoting', ['id_pemilih' => $user['id_pemilih']])->row_array();
                        if ($pemilih) {
                            // Jika sudah memilih, arahkan ke halaman 'sudah_memilih'
                            $this->load->view('vote/sudah_memilih');
                        } else {
                            // Jika belum memilih, arahkan ke halaman 'vote'
                            redirect('vote');
                        }
                    }
                } else {
                    $this->session->set_flashdata(
                        'message',
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Password salah!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'
                    );
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Pemilih tidak aktif!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>'
                );
                redirect('auth');
            }
        } else {
            // Jika NIM tidak ditemukan di database
            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            NIM atau password tidak terdaftar!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>'
            );
            redirect('auth');
        }
    }


    public function logout()
    {
        // $this->session->unset_userdata('email');
        // $this->session->unset_userdata('role_id');
        $this->session->sess_destroy();

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> Berhasil logout!</div>');
        redirect('auth');
    }
}
