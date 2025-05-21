<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Location extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		is_logged_in();

		// Memastikan hanya admin yang dapat mengakses
		if ($this->session->userdata('role') !== 'Admin') {
			redirect('auth/blocked');
		}

		$this->load->model('Location_model');
		$this->load->helper('distance');
	}

	/**
	 * Halaman utama manajemen lokasi
	 */
	public function index()
	{
		$data['judul'] = 'Pengaturan Lokasi Absen';
		$data['employees'] = $this->Location_model->get_employees();

		$this->load->view('templates/header', $data);
		$this->load->view('location/index', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Menyimpan data lokasi absen karyawan
	 */
	public function save()
	{
		// Menerima data dari AJAX request
		$id_user = $this->input->post('id_user');
		$latitude = $this->input->post('latitude');
		$longitude = $this->input->post('longitude');
		$radius = $this->input->post('radius');

		// Validasi input
		if (empty($id_user) || empty($latitude) || empty($longitude) || empty($radius)) {
			echo json_encode(['success' => false, 'message' => 'Semua field harus diisi']);
			return;
		}

		// Simpan data lokasi
		$result = $this->Location_model->save_location($id_user, $latitude, $longitude, $radius);

		if ($result) {
			echo json_encode(['success' => true, 'message' => 'Lokasi absen berhasil disimpan']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Gagal menyimpan lokasi absen']);
		}
	}

	/**
	 * Mendapatkan data lokasi untuk karyawan tertentu
	 */
	public function get_location($id_user)
	{
		$location = $this->Location_model->get_location($id_user);
		echo json_encode($location);
	}
}
