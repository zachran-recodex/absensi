<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		is_logged_in();
		$this->load->model('Admin_model');
	}
	public function index()
	{
		$data['judul'] = "users";

		$data['data_admin'] = $this->Admin_model->tampil_data();

		$this->load->view('templates/header', $data);
		$this->load->view('user/index', $data);
		$this->load->view('templates/footer');
	}

	public function tambah()
	{
		// Load the view for adding data
		$this->load->view('templates/header');
		$this->load->view('user/tambah');
		$this->load->view('templates/footer');
	}

	public function simpan()
	{
		// Set validation rules
		$this->form_validation->set_rules('name', 'Nama', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('role', 'Role', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('message', validation_errors());
			redirect('admin/tambah');
		} else {
			// Configuration for photo upload
			$config['upload_path'] = './uploads/foto/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 2048; // 2MB
			$config['encrypt_name'] = TRUE; // Encrypt file name

			$this->upload->initialize($config);

			// Check if the photo is successfully uploaded
			if (!$this->upload->do_upload('photo')) {
				$error = $this->upload->display_errors();
				$this->session->set_flashdata('message', $error);
				redirect('admin/tambah');
			} else {
				$uploaded_data = $this->upload->data();
				$image_path = './uploads/foto/' . $uploaded_data['file_name'];

				// Call Python script to extract face encoding
				$python_script = 'D:\\absensi\\scripts\\extract_encoding.py';

				// Ensure the Python executable path is correct
				$python_executable = 'C:\\laragon\\bin\\python\\python-3.10\\python.exe';

				// Build the shell command to execute
				$command = escapeshellcmd($python_executable . ' ' . $python_script . ' ' . escapeshellarg($image_path));

				// Execute the command and capture the output
				$output = shell_exec($command);

				// Capture the output (face encoding as JSON)
				$encoding = json_decode($output, true);

				if ($encoding === NULL) {
					@unlink($image_path); // Remove the uploaded file if extraction failed
					$this->session->set_flashdata('message', 'Face encoding extraction failed.');
					redirect('admin/tambah');
				}

				// Prepare data to be saved
				$data = [
					'name' => $this->input->post('name'),
					'username' => $this->input->post('username'),
					'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'role' => $this->input->post('role'),
					'photo' => $uploaded_data['file_name'], // Save file name
					'face_encoding' => json_encode($encoding) // Save face encoding as JSON
				];

				// Save data to the database
				$result = $this->Admin_model->simpan($data);
				if ($result) {
					$this->session->set_flashdata('message', 'Data berhasil ditambahkan');
					redirect('admin');
				} else {
					$this->session->set_flashdata('message', 'Gagal menyimpan data');
					redirect('admin/tambah');
				}
			}
		}
	}

	public function saveFaceData()
	{
		if ($this->input->post('face_descriptor') && $this->input->post('image_data') && $this->input->post('id_user')) {
			$face_descriptor = json_decode($this->input->post('face_descriptor'), true);
			$image_data = $this->input->post('image_data');
			$id_user = $this->input->post('id_user'); // Ambil id_user dari POST

			$result = $this->Admin_model->updateFace($id_user, $face_descriptor, $image_data); // Ganti ke update

			if ($result) {
				echo json_encode(['success' => true]);
			} else {
				echo json_encode(['success' => false, 'message' => 'Database error']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Missing data']);
		}
	}

	private function extract_face_encoding($image_path)
	{
		// Pastikan script Python ada
		$python_script = './scripts/extract_encoding.py';
		if (!file_exists($python_script)) {
			log_message('error', 'Python script not found: ' . $python_script);
			return NULL;
		}

		// Pastikan gambar ada
		if (!file_exists($image_path)) {
			log_message('error', 'Image file not found: ' . $image_path);
			return NULL;
		}

		// Eksekusi script dengan error capturing
		// Make sure to check if python3 is the correct command for your system

		$command = escapeshellcmd("C:\\laragon\\bin\\python\\python-3.10\\python.exe {$python_script} ") . escapeshellarg($image_path) . ' 2>&1';

		$output = shell_exec($command);
		if ($output === NULL || empty($output)) {
			log_message('error', 'Python script execution failed or returned empty output');
			echo 'Python script output: ' . $output;
			return NULL;
		}

		log_message('debug', 'Python script output: ' . $output);
		// Log output mentah untuk debugging
		log_message('debug', 'Python script output: ' . $output);

		// Periksa status eksekusi
		if ($output === NULL) {
			log_message('error', 'Shell execution failed');
			return NULL;
		}

		// Dekode JSON dengan error handling
		$encoding = $output;

		if (json_last_error() !== JSON_ERROR_NONE) {
			log_message('error', 'JSON Decode Error: ' . json_last_error_msg());
			log_message('error', 'Raw output: ' . $output);
			return NULL;
		}

		// Validasi struktur encoding
		if (!is_array($encoding) || empty($encoding)) {
			log_message('error', 'Invalid or empty face encoding');
			return NULL;
		}

		return $encoding;
	}

	public function hapus($id)
	{
		is_logged_in();
		$this->Admin_model->hapus($id);
		redirect('admin');
	}

	public function ubah($id = '')
	{
		is_logged_in();
		$data['judul'] = "user";
		$data_admin = $this->Admin_model->get_id($id);
		$data['ubah_admin'] = $this->Admin_model->get_id($id);

		$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('hak_akses', 'Hak Akses', 'required|trim');
		$this->form_validation->set_rules(
			'password',
			'Password',
			'min_length[5]',
			[
				'min_length' => "Password minimal 5 digit"
			]
		);

		if ($this->form_validation->run() == FALSE) {
			if ($data_admin > 0) {
				$data['ubah_admin'] = $this->Admin_model->get_id($id);
				$this->load->view('templates/header', $data);
				$this->load->view('user/ubah', $data);
				$this->load->view('templates/footer');
			} else {
				$pesan = "Data tidak ditemukan";
				$url = base_url('admin');
				echo "<script>
        alert('$pesan');
        location='$url';
    </script>";
			}
		} else {
			$this->Admin_model->ubah();
			$pesan = "Data berhasil diupdate";
			$url = base_url('admin');
			echo "<script>
            alert('$pesan');
            location='$url';
        </script>";
		}
	}

	public function get($id = null)
	{
		is_logged_in();
		$this->db->select('*');
		$this->db->from('admin');
		if ($id != null) {
			$this->db->where('id', $id);
		}
		return $this->db->get();
	}
}
