<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {

	public function request(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/support/request');
        }
	}

	public function developer(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/support/developer');
        }
	}
}