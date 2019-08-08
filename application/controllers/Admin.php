<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	public function index() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_index');
	}
	
	public function extract(){
	     if($this->session->userdata('user_type') != 'admin'){
			$result = array ('result'  => 'ERROR', 'message' => 'خطای سطح دسترسی!');
			echo json_encode($result);
			return;
		}
		if($this->input->get('lang') == 'fa'){
			$sentences = $this->db->query("SELECT trans_user FROM sentence_bank, users WHERE users.id = sentence_bank.translator AND users.username = ? ORDER BY sentence_bank.id asc", array($this->input->get('username')))->result_array();
			foreach($sentences as $item){
				echo $item['trans_user'] . "<br/>";
			}
		}
		else if($this->input->get('lang') == 'en'){
			$sentences = $this->db->query("SELECT sentence_orig FROM sentence_bank, users WHERE users.id = sentence_bank.translator AND users.username = ? ORDER BY sentence_bank.id asc", array($this->input->get('username')))->result_array();
			foreach($sentences as $item){
				echo $item['sentence_orig'] . "<br/>";
			}
		}
	}
/*
	public function insert_raw(){
		phpinfo();
		return;
		$english = fopen("40001-42000-en.txt", "r") or die("Unable to open file!");
		// echo fread($myfile,filesize("fars.txt"));
		// fclose($myfile);
		$sentences = array();
		if ($english) {
			$i = 0;
	   		while (($line = fgets($english)) !== false) {
				// $this->db->insert('sentence_bank', array ('sentence_orig' => $line));
	   			$sentences[$i]['sentence_orig'] = $line;
	   			$i++;
			}
    		fclose($english);
		}

		$persian = fopen("40001-42000-fa.txt", "r") or die("Unable to open file!");
		// echo fread($myfile,filesize("fars.txt"));
		// fclose($myfile);
		if ($persian) {
			$i = 0;
	   		while (($line = fgets($persian)) !== false) {
				// $this->db->insert('sentence_bank', array ('sentence_orig' => $line));
	   			$sentences[$i]['trans_user'] = $line;
	   			$sentences[$i]['translator'] = $this->session->userdata('user_id');
	   			$sentences[$i]['translated_at'] = date('Y-m-d H:i:s');
	   			$sentences[$i]['sentence_group'] = $this->input->get('group_id');
	   			$sentences[$i]['upload_by'] = $this->session->userdata('user_id');
	   			$i++;
			}
    		fclose($persian);
		}
		// $this->db->insert_batch('sentence_bank', $sentences);
		// print_r($sentences);
	}
*/
	public function groups() {
	   
		$data['success'] = 0;
		$data['error']   = 0;
		if($this->input->post('submitted')){
			if($this->db->insert('groups', array('name' => $this->input->post('group_name')))){
				$data['success'] = 1;
			} else {
				$data['error'] = 1;
			}
		}
		$data['groups'] = $this->db->query("SELECT count(translator) AS num_translated, COUNT(*) AS num_sentences, groups.* FROM groups LEFT JOIN sentence_bank ON groups.id = sentence_group where removed = 0 GROUP BY groups.id")->result_array();
		$this->load->view('view_groups', $data);
	}
	
	public function new_user() {
		$data['success'] = 0;
		$data['error']   = 0;
		if($this->input->post('submitted')){
			if($this->auth->signup($this->input->post('formData'))){
				$data['success'] = 1;
			} else {
				$data['error'] = 1;
			}
		}
		$this->load->view('view_new_user', $data);
	}
	
	public function users() {

		if($this->input->get('submitted')){
			switch ($this->input->get('action')) {
				case 'lock':
					$this->db->update('users', array('isActive' => 0), array('id' => $this->input->get('user_id')));
					break;
				case 'unlock':
					$this->db->update('users', array('isActive' => 1), array('id' => $this->input->get('user_id')));
					break;
			}
		}

		if($this->input->post('submitted') && $this->input->post('action') == 'update_levels'){
			$users = $this->db->query("SELECT users.id, AVG(score) AS score_avg FROM users LEFT JOIN `sentence_bank` ON users.id = sentence_bank.translator GROUP BY translator")->result_array();
			foreach ($users as $key => $user) {
				if($user['score_avg'] > 4){
					$this->db->update('users', array ('level' => 1), array ('id' => $user['id']));
				}
				elseif ($user['score_avg'] <= 4 && $user['score_avg'] > 3) {
					$this->db->update('users', array ('level' => 2), array ('id' => $user['id']));
				}
				elseif ($user['score_avg'] <= 3 && $user['score_avg'] > 2) {
					$this->db->update('users', array ('level' => 3), array ('id' => $user['id']));
				}
				elseif ($user['score_avg'] <= 2 && $user['score_avg'] > 1) {
					$this->db->update('users', array ('level' => 4), array ('id' => $user['id']));
				}
				else {
					$this->db->update('users', array ('level' => 5), array ('id' => $user['id']));
				}
			}
			$this->db->update('options', array ('last_levels_update' => date('Y-m-d-H-i-s')));
		}

		$data['users'] = $this->db->query("SELECT tbl1.*, groups.name AS group_name, num_edit, avg_edit FROM (SELECT users.*,
												 COUNT(sentence_bank.id) AS num_translate,
												 COUNT(sentence_bank.id) / (DATEDIFF(NOW(),users.created_at)+1) AS avg_translate
											FROM users LEFT JOIN sentence_bank ON sentence_bank.translator = users.id AND temporary = 0
													   GROUP BY users.id) AS tbl1, (SELECT users.id,
												 COUNT(sentence_bank.id) AS num_edit,
												 COUNT(sentence_bank.id) / (DATEDIFF(NOW(),users.created_at)+1) AS avg_edit
											FROM users LEFT JOIN sentence_bank ON sentence_bank.editor = users.id
													   GROUP BY users.id) AS tbl2, groups WHERE tbl1.id = tbl2.id  AND groups.id = tbl1.user_group ORDER BY num_translate DESC")->result_array();
		
		$data['last_levels_update'] = $this->db->query("SELECT last_levels_update FROM options")->row_array()['last_levels_update'];
		$this->load->view('view_users', $data);
	}
	
	public function bolten() {

		$data['success'] = 0;
		$data['error'] = 0;

		if($this->input->post('submitted')){
			$query = $this->db->insert('bolten', $this->input->post('formData'));
			if($query)
				$data['success'] = 1;
			else
				$data['error'] = 1;
		}

		$this->load->view('view_new_notification', $data);
	}



	public function bolten_list() {

		if($this->input->get('submitted')){
			switch ($this->input->get('action')) {
				case 'remove':
					$this->db->update('bolten', array('isActive' => 0), array('id' => $this->input->get('itemId')));
					break;					
			}
		}

		$data['bolten'] = $this->db->query("SELECT * FROM bolten where isActive = 1")->result_array();
		$this->load->view('view_bolten_list', $data);
	}

	public function add() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_add');
	}
	
	public function add2db() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_add2db');
	}

	public function reports() {
		$this->load->view('head');
		$this->load->view('header');
		$this->load->view('view_sidemenu');
		$this->load->view('view_report_list');
	}
	
	public function user_activity() {
		$this->load->view('view_activity');
	}

}
