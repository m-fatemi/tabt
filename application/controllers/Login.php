<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function index() {
		$data['login_error'] = 0;
		if($this->input->post('submitted')){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if ($this->auth->login($username, $password)) {
				redirect(base_url());
			} else {
				$data['login_error'] = 1;
			}
			
		}
		
		$this->load->view('view_login', $data);
	}
}