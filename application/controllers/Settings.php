<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function resetpass() {

		$data['msg']   = "";
		if($this->input->post('submitted')){
			$data['msg'] = $this->auth->reset_pass($this->input->post('old_pass'), $this->input->post('new_pass'));
		}
		$this->load->view('view_reset_pass', $data);
	}
}
