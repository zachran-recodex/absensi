<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absen extends CI_Controller
{
	
    public function __construct()
    {
		
        parent::__construct();
        $this->load->model('Absen_model');
		 // For ensuring user is logged in
    }

    public function index()
    {
        
        $data['judul'] = "Absen";
		$id_user = $this->session->userdata('id_user');
		$absen = $this->Absen_model->get_absen_today($id_user);

		$bulan = date('m'); 
        $tahun = date('Y'); 

        $jumlah_kehadiran = $this->Absen_model->get_kehadiran_bulan($bulan, $tahun, $id_user);
        $jumlah_terlambat = $this->Absen_model->get_kehadiran_terlambat($bulan, $tahun, $id_user);
        $jumlah_keluar_sebelum_16 = $this->Absen_model->get_kehadiran_keluar_sebelum_16($bulan, $tahun, $id_user);
        
        $data['jumlah_kehadiran'] = $jumlah_kehadiran;
		$data['jumlah_terlambat'] = $jumlah_terlambat;
		$data['jumlah_keluar_sebelum_16'] = $jumlah_keluar_sebelum_16;
		
		$data['absen'] = $absen;
		$data['history_absen'] = $this->Absen_model->get_history_absen_by_user($id_user);
        $this->load->view('templates/header', $data);
        $this->load->view('absen/index', $data);
        $this->load->view('templates/footer');
    }

	public function absen_masuk() 
{
    $data['judul'] = "Absen";


    // Load view setelah menjalankan Python
    $this->load->view('templates/header', $data);
    $this->load->view('absen/absen_masuk', $data);
    $this->load->view('templates/footer');
}

public function absen_keluar() 
{
    $data['judul'] = "Absen";


    // Load view setelah menjalankan Python
    $this->load->view('templates/header', $data);
    $this->load->view('absen/absen_keluar', $data);
    $this->load->view('templates/footer');
}



	public function simpan_absen() {
	
		$this->load->helper('distance');
		
		// Ambil data JSON yang dikirimkan
		$data = json_decode($this->input->raw_input_stream, true);
		
		$id_user_video = $data['id_user_video']; 
		$latitude = $data['latitude'];
		$longitude = $data['longitude'];
		$face_encoding = $data['face_encoding'];
		$tanggal = $data['tanggal'];
		$waktu = $data['waktu']; 
		
		$id_user = $this->session->userdata('id_user'); 
	
		// Validasi pengguna harus login
		if (!$id_user) {
			echo json_encode(['success' => false, 'message' => 'Anda harus login untuk melakukan absensi.']);
			return;
		}
	
		// Validasi kesesuaian ID pengguna
		if ($id_user != $id_user_video) {
			echo json_encode(['success' => false, 'message' => 'ID pengguna tidak sesuai dengan ID pada video.']);
			return;
		}
		
		// Load model untuk mendapatkan lokasi absen karyawan
		$this->load->model('Location_model');
		$location = $this->Location_model->get_location($id_user);
		
		// Jika lokasi belum diatur, gunakan lokasi default
		$preset_latitude = $location['location_latitude'] ?? -0.044107;
		$preset_longitude = $location['location_longitude'] ?? 109.344088;
		$max_distance = $location['location_radius'] ?? 100;
	
		// Validasi jarak lokasi
		$distance = calculate_distance($preset_latitude, $preset_longitude, $latitude, $longitude);
		if ($distance > $max_distance) {
			echo json_encode(['success' => false, 'message' => 'Anda berada di luar jangkauan lokasi absensi.']);
			return;
		}
	
		
		$absen_data = [
			'id_user' => $id_user,
			'latitude' => $latitude,
			'longitude' => $longitude,
			'absen_time' => $tanggal,
			'jam_masuk' => $waktu,
			'absen_type' => 'Masuk',
		];
	
		$this->load->model('Absen_model');
		if ($this->Absen_model->insert_absen($absen_data)) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data absensi.']);
		}
	}
	
	public function simpan_absen_keluar() {
		$this->load->helper('distance');
	
		// Ambil data JSON yang dikirimkan
		$data = json_decode($this->input->raw_input_stream, true);
	
		// Validasi input yang diperlukan
		if (empty($data['id_user_video']) || empty($data['latitude']) || empty($data['longitude']) || empty($data['tanggal']) || empty($data['waktu'])) {
			echo json_encode(['success' => false, 'message' => 'Data yang diperlukan tidak lengkap.']);
			return;
		}
	
		$id_user_video = $data['id_user_video'];
		$latitude = $data['latitude'];
		$longitude = $data['longitude'];
		$tanggal = $data['tanggal'];
		$waktu = $data['waktu'];
	
		$id_user = $this->session->userdata('id_user');

		// Load model untuk mendapatkan lokasi absen karyawan
		$this->load->model('Location_model');
		$location = $this->Location_model->get_location($id_user);

		// Jika lokasi belum diatur, gunakan lokasi default
		$preset_latitude = $location['location_latitude'] ?? -0.044107;
		$preset_longitude = $location['location_longitude'] ?? 109.344088;
		$max_distance = $location['location_radius'] ?? 100;
	
		// Validasi pengguna harus login
		if (!$id_user) {
			echo json_encode(['success' => false, 'message' => 'Anda harus login untuk melakukan absensi.']);
			return;
		}
	
		// Validasi kesesuaian ID pengguna
		if ($id_user != $id_user_video) {
			echo json_encode(['success' => false, 'message' => 'ID pengguna tidak sesuai dengan ID pada video.']);
			return;
		}
	
		// Validasi jarak lokasi
		$distance = calculate_distance($preset_latitude, $preset_longitude, $latitude, $longitude);
		if ($distance > $max_distance) {
			echo json_encode(['success' => false, 'message' => 'Anda berada di luar jangkauan lokasi absensi. Jarak Anda: ' . round($distance, 2) . ' meter. Batas maksimal adalah ' . $max_distance . ' meter.']);
			return;
		}
	
		// Data absensi yang akan diperbarui
		$absen_data = [
			'jam_keluar' => $waktu,
			'absen_type' => 'Keluar',
		];
	
		// Muat model dan lakukan pembaruan
		$this->load->model('Absen_model');
		if ($this->Absen_model->update_absen_by_user($id_user, $tanggal, $absen_data)) {
			echo json_encode(['success' => true, 'message' => 'Absensi keluar berhasil disimpan.']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data absensi.']);
		}
	}

	public function stream() 
    {
        // Set header untuk streaming video
        header('Content-Type: multipart/x-mixed-replace; boundary=frame');
    
        $python_path = "C:\\laragon\\bin\\python\\python-3.10\\python.exe";
        $script_path = "D:\\absensi\\scripts\\face_recognition_api.py";
    
        // Validasi keberadaan Python dan script
        if (!file_exists($python_path) || !file_exists($script_path)) {
            http_response_code(500);
            echo "Error: Python interpreter or script not found.";
            exit;
        }
    
        // Jalankan script Python
        try {
            // Tambahkan error handling
            $descriptorspec = [
                0 => ["pipe", "r"],  // stdin
                1 => ["pipe", "w"],  // stdout
                2 => ["pipe", "w"]   // stderr
            ];
            
            $process = proc_open("$python_path $script_path", $descriptorspec, $pipes);
            
            if (is_resource($process)) {
                // Streaming output
                while (!feof($pipes[1])) {
                    echo fgets($pipes[1]);
                    flush();
                }
                
                // Tutup pipe dan proses
                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
            } else {
                throw new Exception("Failed to start Python process");
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }

    public function get_registered_faces() 
    {
        // Set header JSON
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
        try {
            // Ambil semua pengguna
            $faces = $this->db->get('users')->result_array();

            // Jika tidak ada data
            if (empty($faces)) {
                echo json_encode([]);
                return;
            }

            // Proses face encoding
            $processed_faces = [];
            foreach ($faces as $face) {
                // Validasi dan konversi face_encoding
                $encoding = $face['face_encoding'];
                
                // Pastikan encoding valid
                if (!empty($encoding)) {
                    // Coba decode jika masih dalam bentuk string
                    if (is_string($encoding)) {
                        $decoded = json_decode($encoding, true);
                        $encoding = $decoded ? $decoded : $encoding;
                    }

                    // Hanya tambahkan jika valid
                    if (is_array($encoding)) {
                        $processed_face = [
                            'name' => $face['name'] ?? 'Unknown',
							'id_user' => $face['id_user'] ?? 'Unknown',
                            'face_encoding' => $encoding
                        ];
                        $processed_faces[] = $processed_face;
                    }
                }
            }

            // Kirim response JSON
            echo json_encode($processed_faces, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            // Tangani kesalahan
            http_response_code(500);
            echo json_encode([
                'error' => 'Failed to retrieve faces',
                'message' => $e->getMessage()
            ]);
        }
    }
	


}
?>
