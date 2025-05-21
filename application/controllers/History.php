<?php
defined('BASEPATH') or exit('No direct script access allowed');
class History extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('History_model');
		is_logged_in();
    }
    public function index()
    {
        is_logged_in();
        $data['judul'] = "History";
        

        $data['absen'] = $this->History_model->tampil_data();
       
        $this->load->view('templates/header', $data);
        $this->load->view('history/index', $data);
        $this->load->view('templates/footer');
    }

    

}
