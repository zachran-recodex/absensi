<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Location_model extends CI_Model
{
	/**
	 * Menyimpan atau memperbarui lokasi absen untuk pengguna
	 * 
	 * @param int $id_user ID pengguna
	 * @param float $latitude Latitude lokasi
	 * @param float $longitude Longitude lokasi
	 * @param int $radius Radius dalam meter
	 * @return bool True jika berhasil, false jika gagal
	 */
	public function save_location($id_user, $latitude, $longitude, $radius)
	{
		$data = [
			'location_latitude' => $latitude,
			'location_longitude' => $longitude,
			'location_radius' => $radius
		];

		$this->db->where('id_user', $id_user);
		$this->db->update('users', $data);

		return ($this->db->affected_rows() > 0);
	}

	/**
	 * Mendapatkan lokasi absen untuk pengguna tertentu
	 * 
	 * @param int $id_user ID pengguna
	 * @return array|null Data lokasi atau null jika tidak ditemukan
	 */
	public function get_location($id_user)
	{
		$this->db->select('location_latitude, location_longitude, location_radius');
		$this->db->from('users');
		$this->db->where('id_user', $id_user);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();
		}

		return null;
	}

	/**
	 * Mendapatkan semua pengguna dengan role karyawan
	 * 
	 * @return array Data pengguna
	 */
	public function get_employees()
	{
		$this->db->select('id_user, name, username, location_latitude, location_longitude, location_radius');
		$this->db->from('users');
		$this->db->where('role', 'Karyawan');
		$query = $this->db->get();

		return $query->result_array();
	}
}
