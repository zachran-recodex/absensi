<?php
class History_model extends CI_Model
{
	public function tampil_data()
	{
		$this->db->select('absensi.*, users.name, users.role');
		$this->db->from('absensi');
		$this->db->join('users', 'users.id_user = absensi.id_user', 'inner');
		$this->db->order_by('absensi.absen_time', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}
}
