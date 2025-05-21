<?php
//untuk menampilkan data user berdasarkan id session
class Fungsi
{
	protected $ci;

	function __construct()
	{
		$this->ci = &get_instance();
	}

	function user_login()
	{
		$this->ci->load->model('User_model');
		$username = $this->ci->session->userdata('username');
		$user_data = $this->ci->User_model->get_user_by_username($username);

		return $user_data;
	}
}
