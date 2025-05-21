<?php
class Absen_model extends CI_Model
{
	public function save_attendance($data)
	{
		// Pastikan data yang diterima valid
		if (empty($data['id_user']) || empty($data['latitude']) || empty($data['longitude'])) {
			return false;
		}

		// Masukkan data absensi ke dalam tabel absensi
		$this->db->insert('absensi', $data);

		// Cek jika insert berhasil
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Fungsi untuk mengambil data wajah yang sudah di-encoded berdasarkan ID user
	public function get_face_encoding($id_user)
	{
		$this->db->select('face_encoding');
		$this->db->from('users');
		$this->db->where('id_user', $id_user);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->face_encoding;
		} else {
			return null;
		}
	}

	public function saveAbsence($isAbsenMasuk, $isAbsenKeluar)
	{
		// Get the logged-in user ID from session
		$id_user = $this->session->userdata('id_user');
		$current_time = date('Y-m-d H:i:s'); // Get current date and time

		// Validate user ID
		if (empty($id_user)) {
			return false; // If no user is logged in
		}

		// Prepare the data to insert
		$data = [
			'id_user' => $id_user,
			'absen_time' => $current_time,
			'absen_type' => $isAbsenMasuk ? 'masuk' : ($isAbsenKeluar ? 'keluar' : null),
		];

		// Ensure there is a valid absence type
		if ($data['absen_type'] === null) {
			return false; // Invalid absen type (should not happen with proper checks)
		}

		// Insert the absence record into the database
		$this->db->insert('absensi', $data);

		// Check if the insertion was successful
		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}

	public function matchFace($faceDescriptor)
	{
		// Get the registered face descriptors from the database
		$this->db->select('id_user, face_descriptor');
		$query = $this->db->get('users');
		$users = $query->result();

		// Compare the face descriptor with registered faces in the database
		foreach ($users as $user) {
			$storedDescriptor = json_decode($user->face_descriptor); // Stored face descriptor
			if ($this->compareFaceDescriptors($faceDescriptor, $storedDescriptor)) {
				return $user->id_user; // Return the user ID if a match is found
			}
		}

		return false; // No match found
	}

	public function insert_absen($data)
	{
		return $this->db->insert('absensi', $data);
	}

	public function update_absen_by_user($id_user, $tanggal, $data)
	{
		$this->db->where('id_user', $id_user);
		$this->db->where('absen_time', $tanggal);
		return $this->db->update('absensi', $data);
	}

	public function reset_absen_harian($id_user, $tanggal_sekarang)
	{
		// Cek apakah absen terakhir bukan dari hari ini
		$this->db->select('id');
		$this->db->where('id_user', $id_user);
		$this->db->where('DATE(absen_time) <', $tanggal_sekarang);
		$this->db->get('absensi');
	}

	public function get_history_absen_by_user($id_user)
	{
		// Query untuk mengambil data absen berdasarkan id_user dan urutkan berdasarkan tanggal terbaru
		$this->db->select('*');
		$this->db->from('absensi');  // Sesuaikan dengan nama tabel absen
		$this->db->where('id_user', $id_user);
		$this->db->order_by('absen_time', 'DESC');  // Mengurutkan berdasarkan tanggal (DESC untuk terbaru)

		// Menjalankan query dan mengembalikan hasilnya
		return $this->db->get()->result_array();
	}

	public function get_absen_today($id_user)
	{

		date_default_timezone_set('Asia/Jakarta');

		$today = date('Y-m-d');

		$this->db->where('id_user', $id_user);
		$this->db->where('DATE(absen_time)', $today);
		return $this->db->get('absensi')->row_array();
	}

	public function get_kehadiran_bulan($bulan, $tahun, $id_user)
	{
		$this->db->select('absen_time');
		$this->db->where('MONTH(absen_time)', $bulan);
		$this->db->where('YEAR(absen_time)', $tahun);
		$this->db->where('id_user', $id_user);
		$this->db->group_by('absen_time');
		$query = $this->db->get('absensi');

		return $query->num_rows();
	}

	public function get_kehadiran_terlambat($bulan, $tahun, $id_user)
	{
		$this->db->select('absen_time');
		$this->db->where('MONTH(absen_time)', $bulan);
		$this->db->where('YEAR(absen_time)', $tahun);
		$this->db->where('TIME(jam_masuk) > "08:00:00"');
		$this->db->where('id_user', $id_user);
		$this->db->group_by('absen_time');
		$query = $this->db->get('absensi');

		return $query->num_rows();
	}

	public function get_kehadiran_keluar_sebelum_16($bulan, $tahun, $id_user)
	{
		$this->db->select('absen_time');
		$this->db->where('MONTH(absen_time)', $bulan);
		$this->db->where('YEAR(absen_time)', $tahun);
		$this->db->where('TIME(jam_keluar) < "16:00:00"');
		$this->db->where('id_user', $id_user);
		$this->db->group_by('absen_time');
		$query = $this->db->get('absensi');

		return $query->num_rows();
	}

	private function compareFaceDescriptors($descriptor1, $descriptor2)
	{
		if (!is_array($descriptor1) || !is_array($descriptor2)) {
			log_message('error', 'Face descriptors are not valid arrays. Descriptor1: ' . json_encode($descriptor1) . ' Descriptor2: ' . json_encode($descriptor2));
			return false;
		}

		// Ensure both descriptors have the same length
		if (count($descriptor1) !== count($descriptor2)) {
			log_message('error', 'Face descriptors do not have the same length. Descriptor1 length: ' . count($descriptor1) . ' Descriptor2 length: ' . count($descriptor2));
			return false;
		}

		// Calculate the Euclidean distance between the two face descriptors
		$distance = 0;
		for ($i = 0; $i < count($descriptor1); $i++) {
			$distance += pow($descriptor1[$i] - $descriptor2[$i], 2);
		}

		// Take the square root to get the actual Euclidean distance
		$distance = sqrt($distance);
		log_message('debug', 'Calculated distance: ' . $distance);

		// You can adjust the threshold based on testing
		return $distance < 0.8;  // Adjust the threshold as needed
	}

	public function markAttendance($userId, $absenType)
	{
		// Mark attendance based on the type (Masuk or Keluar)
		$data = [
			'id_user' => $userId,
			'absen_type' => $absenType,
			'absen_time' => date('Y-m-d H:i:s'),
		];

		// Insert attendance record
		return $this->db->insert('absensi', $data);
	}
	// Fungsi untuk mendapatkan data absensi berdasarkan ID user
	public function get_attendance_by_user($id_user)
	{
		$this->db->select('*');
		$this->db->from('absensi');
		$this->db->where('id_user', $id_user);
		$query = $this->db->get();

		return $query->result();
	}
}
?>
