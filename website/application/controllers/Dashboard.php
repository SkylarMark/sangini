<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
        if ($this->session->userdata('username'))
        {
            if ($this->session->userdata('role') == '0')
                $this->load->view('admin/dashboard');
            else
                $this->load->view('users/dashboard');
        }
        else
        {
            echo 'Redirecting to Login';
            $this->session->set_flashdata('error_msg', 'Please Login First Before Accessing Dashboard !');
			redirect(base_url());
        }
		
    }
    
    public function test()
    {
        echo "It Works !";
    }
}
