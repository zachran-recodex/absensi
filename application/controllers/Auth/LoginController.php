<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Load Dependencies

	}

	// List all your items
	public function index()
	{
		$data['title'] = 'Login';

		$this->form_validation->set_rules('username','username','trim|required|alpha_numeric_spaces');
		$this->form_validation->set_rules('password','Password','trim|required|alpha_numeric_spaces');

		if ($this->form_validation->run() == FALSE) :
			
			$this->load->view('login');
		else :
			$username = htmlspecialchars($this->input->post('username',TRUE));
			$password = htmlspecialchars($this->input->post('password',TRUE));

			$this->cek_login($username, $password);
		endif;
	}

	private function cek_login($username, $password)
{
    // Cek akun di table user dan admin berdasarkan nip
    
    $user = $this->db->get_where('users', ['username' => $username])->row_array();
	$hak_akses = $this->db->get_where('users', ['username' => $username])->row_array();
	$id_user = $this->db->get_where('users', ['username' => $username])->row_array();
    if ($user) {
        // Jika akun user ditemukan
        // Cek password
        if (password_verify($password, $user['password'])) {
            // Jika password benar
            // Buat session userdata
			
            $session = [
				
                'username' => $user['username'],
				'role' => $hak_akses['role'],
				'id_user' => $id_user['id_user'],
				'name' => $user['name'],
            ];

            $this->session->set_userdata($session);
            $this->session->set_flashdata('msg', '<div class="alert alert-primary" role="alert">
                Login berhasil!
                </div>');

            return redirect('home');
        } else {
            // Password salah
            $this->session->set_flashdata('msg', '<div class="alert alert-danger" role="alert">
                Username atau Password salah!
                </div>');

            return redirect('Auth/LoginController');
        }
    } else {
        // nip tidak ditemukan di tabel user maupun admin
        $this->session->set_flashdata('msg', '<div class="alert alert-danger" role="alert">
            Username atau Password salah!
            </div>');

        return redirect('Auth/LoginController');
    }
}

}

/* End of file LoginController.php */
/* Location: ./application/controllers/Auth/LoginController.php */
