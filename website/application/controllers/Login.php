<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index(){

		if (!$this->session->userdata('username'))
        {
			$this->load->view('login');
        }
        else
        {
			echo 'Redirecting to Dashboard';
			redirect(base_url()."dashboard");
        }

	}

	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}

	public function checkLogin(){
		if ( trim($_POST['userlogin']) != null && trim($_POST['password']) != null )
		{
			$url = api_url.'login';

			$fields = array(
				'userlogin' => $_POST['userlogin'],
				'password' => $_POST['password'],
			);
	
			$data = http_build_query($fields);
	
			//open connection
			$ch = curl_init();
	
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
			//execute post
			$result = curl_exec($ch);
			$result = json_decode($result, true);

			if ($result['error'])
			{
				echo $result['data'];
			}
			else
			{
				$this->session->set_userdata('username', $result['data']['username']);
				$this->session->set_userdata('api_key', $result['data']['api_key']);
				$this->session->set_userdata('role', $result['data']['role']);
				echo 'Logged In Successfully';
			}

			//close connection
			curl_close($ch);
		}
		else
		{
			echo 'Username and Password are Required';
		}
	}
}
