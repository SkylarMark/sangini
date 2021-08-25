<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analytics extends CI_Controller {

	public function index(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/analytics/sales');
        }
    }
    
    public function productsale(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/analytics/productsale');
        }
    }
    
    public function purchaces(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/analytics/customer');
        }
	}
}