<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	
	public function index() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_index');
	}

	public function func() {
		// $str_src ="امروز باران می بارد .";
		// $str_dest = "امروز باران می‌بارد .";
		// echo strpos($str_src, "باران") . '<br/>'; 
		// $src_arr = explode(" ",$str_src);
		// $dest_arr = explode(" ", $str_dest);
		// $corrections = array();
		// for($i = 0; $i < count($dest_arr); $i++){
		// 	echo $i . ' => ' . $dest_arr[$i] . '<br/>';
		// 	if(strpos($str_src, $dest_arr[$i]) !== false){
		// 		$str_src = str_replace($dest_arr[$i], "", $str_src);

		// 	} else {
		// 		array_push($corrections ,  $dest_arr[$i]);
		// 	}
		// }
		// echo $str_src;
		// $sum = 0;
		// for($i = 0; $i < 1000; $i++){
		// 	$prob_edit = max(0,mt_rand(0,100) / 100 - (0.5 - 0.1));
		// 	if($prob_edit > 0.5)
		// 		$sum += 1;
		// 	// echo $rand . "\n";
		// }
		// echo "avg = " . $sum / 1000;
	}
	
	public function temporary() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_temporary');
	}

	public function stats() {
		$data['stats'] = $this->db->query("SELECT sentence_bank.*, CONCAT(date_format(created_at, '%Y'), '_', week(created_at)) AS week from sentence_bank GROUP BY week ORDER BY week DESC LIMIT 12")->result_array();
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_morris');
	}

	public function help() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_help');
		
	}

	public function edit() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_edit');
	}

	public function translate() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_translate');
	}

	public function last24() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_last24');
	}

	public function logout() {
		$this->auth->logout();
	}
}
