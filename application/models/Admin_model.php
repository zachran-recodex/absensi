<?php
class Admin_model extends CI_Model
{

    public function tampil_data()
    {
        return $this->db->get('users')->result_array();
    }

	public function count_users() {
       
        $this->db->from('users');  
        return $this->db->count_all_results();  
    }

	public function count_absent_today() {
        $today = date('Y-m-d');
        $this->db->from('absensi'); 
        $this->db->where('absen_time', $today); 
        return $this->db->count_all_results(); 
    }

	public function count_late_today() {
        $today = date('Y-m-d'); 
        $late_time = '08:00:00'; 

        $this->db->from('absensi'); 
        $this->db->where('absen_time', $today); 
        $this->db->where('jam_masuk >', $late_time); 
        return $this->db->count_all_results();
    }

    public function simpan($data)
{
    return $this->db->insert('users', $data);
    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data Berhasil Ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
}

    public function hapus($id)
    {
        $this->db->where('id_user', $id);
        $this->db->delete('users');
        $this->session->set_flashData('message', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Data Berhasil Dihapus</strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    }
    public function get_id($id)
    {
        return $this->db->get_where('user', ['id_user' => $id])->row_array();
        
    }

	public function get_user_hakakses($hakakses){
		if (is_array($hakakses)) {
			$this->db->where_in('hak_akses', $hakakses);
		} else {
			$this->db->where('hak_akses', $hakakses);
		}
	
		$query = $this->db->get('user');
		return $query->result_array();
	}

    
    public function usernamecek($username, $id)
    {
        $this->db->where('username =', $username);
        $this->db->where('id_user !=', $id);
        $cek = $this->db->get('user')->num_rows();
        return $cek;
    }

	public function get_user($id_user) {
        $this->db->where('id_user', $id_user);
        $query = $this->db->get('users'); // Asumsikan tabel 'users' menyimpan data pengguna
        return $query->row(); // Mengembalikan baris pertama (data pengguna)
    }

	public function updateFace($id_user, $face_descriptor, $image_data) {
    $data = [
        'face_descriptor' => json_encode($face_descriptor),  // Simpan face descriptor sebagai JSON string
        'photo' => $image_data  // Simpan data foto
    ];

    // Update berdasarkan id_user
    $this->db->where('id_user', $id_user);
    return $this->db->update('users', $data);
}

public function get_users_with_face_descriptor() {
	// Mengambil data nama dan face_descriptor dari tabel users
	$query = $this->db->select('name, face_descriptor')->get('users');
	return $query->result_array();
}


    public function ubah()
    {
        $pass = $this->input->post('password');

        $data = [
            
            "nama" => $this->input->post('nama'),
            "username" => $this->input->post('username'),
			"hak_akses" => $this->input->post('hak_akses'),
            
        ];

        if ($pass != null) { //jika input password tidak kosong maka yang disimpan password baru
            $data = [
                "password" => password_hash(htmlspecialchars($this->input->post('password',TRUE)), PASSWORD_DEFAULT),
               "nama" => $this->input->post('nama'),
            "username" => $this->input->post('username'),
			"hak_akses" => $this->input->post('hak_akses'),
                
            ];
        }
        $this->session->set_flashData('message', '<div class="alert alert-primary alert-dismissible fade show" role="alert">
            <strong>Data Berhasil Diupdate</strong> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>');
        $this->db->where('id_user', $this->input->post('id'));
        $this->db->update('user', $data);
    }

    public function login($post)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('username', $post['user_name']);
        $this->db->where('password', sha1($post['pass']));
        return $this->db->get();
    }

    public function get($id = null)
    //membuat 1 fungsi untuk menampilkan semua data
    //dan menampilkan data per id/satu data
    {
        $this->db->select('*');
        $this->db->from('admin');
        if ($id != null) {
            $this->db->where('id_admin', $id);
        }
        return $this->db->get();
    }
}
