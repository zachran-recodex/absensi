<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
		is_logged_in();
	}

	public function index()
	{
		is_logged_in();

		$data['jumlah_user'] = $this->Admin_model->count_users();
		$data['jumlah_absen'] = $this->Admin_model->count_absent_today();
		$data['jumlah_telat'] = $this->Admin_model->count_late_today();
		$this->load->view('templates/header');
		$this->load->view('home/index', $data);
		$this->load->view('templates/footer');

	}
}
