<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function submit_translate() {
		/*if($this->session->userdata('trans_sentence_id') != $this->input->post('sentence_id')){
			$result = array ('result'  => 'ERROR', 'message' => 'متاسفانه خطایی در ثبت اطلاعات رخ داد!');
			echo json_encode($result);
			return;
		}*/
		$user_level = $this->db->query("SELECT level FROM users WHERE id = ?", $this->session->userdata('user_id'))->row_array()['level'];
		$data = array (
						'trans_user'  		=> $this->input->post('trans_user'),
						'temporary'  		=> $this->input->post('temporary'),
						'prob_edit'  		=> $prob_edit = max(0,mt_rand(0,1000) / 1000 - (0.5 - $user_level / 10)),
						'translated_at'  	=> date("Y-m-d-H-i-s"),
						'translator' 		=> $this->session->userdata('user_id'));

		$query = $this->db->update('sentence_bank', $data, array('id' => $this->input->post('sentence_id')));
		$query = $this->db->update('users', array('last_activity' => date('Y-m-d-H-i-s')), array('id' => $this->session->userdata('user_id')));

		if($query) {
			$result = array ('result'  => 'OK', 'message' => 'اطلاعات با موفقیت ثبت شد. با سپاس از همکاری شما!');
			echo json_encode($result);
		}
		else {
			$result = array ('result'  => 'ERROR', 'message' => 'متاسفانه خطایی در ثبت اطلاعات رخ داد!');
			echo json_encode($result);
		}
	}
	
	public function submit_sentence_edit() {
		if($this->session->userdata('user_type') != 'admin'){
			$result = array ('result'  => 'ERROR', 'message' => 'خطای سطح دسترسی!');
			echo json_encode($result);
			return;
		}
		$data = array (
						'sentence_orig'  	=> $this->input->post('sentence_orig'),
						'reported_by'  		=> null);

		$query = $this->db->update('sentence_bank', $data, array('id' => $this->input->post('sentence_id')));
		$query = $this->db->update('users', array('last_activity' => date('Y-m-d-H-i-s')), array('id' => $this->session->userdata('user_id')));

		if($query) {
			$result = array ('result'  => 'OK', 'message' => 'اطلاعات با موفقیت ثبت شد!');
			echo json_encode($result);
		}
		else {
			$result = array ('result'  => 'ERROR', 'message' => 'متاسفانه خطایی در ثبت اطلاعات رخ داد!');
			echo json_encode($result);
		}
	}
	
	public function remove_sentence() {
		if($this->session->userdata('user_type') != 'admin'){
			$result = array ('result'  => 'ERROR', 'message' => 'خطای سطح دسترسی!');
			echo json_encode($result);
			return;
		}
		$data = array ('removed' => 1);

		$query = $this->db->update('sentence_bank', $data, array('id' => $this->input->post('sentence_id')));
		$query = $this->db->update('users', array('last_activity' => date('Y-m-d-H-i-s')), array('id' => $this->session->userdata('user_id')));

		if($query) {
			$result = array ('result'  => 'OK', 'message' => 'جمله با موفقیت حذف شد!');
			echo json_encode($result);
		}
		else {
			$result = array ('result'  => 'ERROR', 'message' => 'متاسفانه خطایی در ثبت اطلاعات رخ داد!');
			echo json_encode($result);
		}
	}

	public function add_sentences() {

		if($this->session->userdata('user_type') != 'admin'){
			$result = array ('result'  => 'ERROR', 'message' => 'خطای سطح دسترسی!');
			echo json_encode($result);
			return;
		}

		$query = $this->db->insert_batch('sentence_bank', $this->input->post('sentences'));
		if($query) {
			$result = array ('result'  => 'OK', 'message' => 'جملات با موفقیت آپلود شدند');
			echo json_encode($result);
		} else {
			$result = array ('result'  => 'ERROR', 'message' => 'خطایی در ثبت جملات رخ داد!');
			echo json_encode($result);
		}
	}

	public function new_sentence() {
		$statement = $this->db->query("SELECT * FROM sentence_bank WHERE removed = 0 AND sentence_group = ? AND trans_user IS NULL AND translator IS NULL AND reported_by IS NULL ORDER By last_load, RAND() ASC limit 1", $this->session->userdata('user_group'))->row_array();
		$this->session->set_userdata(array('trans_sentence_id' => $statement['id']));
		$this->db->update('sentence_bank', array('last_load' => time()), array('id' => $statement['id']));
		echo json_encode($statement);
	}

	public function new_sentence_edit() {
		$mylevel = $this->db->query("SELECT level FROM users WHERE id = ?", $this->session->userdata('user_id'))->row_array()['level'];
		$limit = $mylevel;
		if($mylevel == 1 || $mylevel == 2)
			$limit -= 1;

		$statement = $this->db->query("SELECT sentence_bank.*, users.level AS translator_level
											FROM sentence_bank, users 
											WHERE users.id = sentence_bank.translator 
											AND sentence_group = ?
											AND translator IS NOT NULL 
											AND editor IS NULL
											AND removed = 0
											AND temporary = 0
											AND translator != ?
											AND users.level > ? 
											AND prob_edit > 0.5 
											AND DATE(translated_at) < DATE_SUB(CURDATE(), INTERVAL 24 Hour)
											ORDER By last_load, RAND() ASC limit 1", array ($this->session->userdata('user_group'), $this->session->userdata('user_id'), $limit))->row_array();
		$this->session->set_userdata(array('edit_sentence_id' => $statement['id']));
		$this->db->update('sentence_bank', array('last_load' => time()), array('id' => $statement['id']));
		echo json_encode($statement);
	}

	public function submit_edition() {
		
		if($this->session->userdata('edit_sentence_id') != $this->input->post('sentence_id')){
			$result = array ('result'  => 'ERROR', 'message' => 'متاسفانه خطایی در ثبت اطلاعات رخ داد!');
			echo json_encode($result);
			return;
		}
		$score = $this->input->post('score');
		if($score > 5 || $score < 0){
			$result = array ('result'  => 'ERROR', 'message' => 'خطالی ورودی');
			echo json_encode($result);
			return;
		}

		$data = array (
						'trans_edited'  	=> $this->input->post('trans_edited'),
						'score'  			=> $score,
						'edited_at'  		=> date("Y-m-d-H-i-s"),
						'editor' 			=> $this->session->userdata('user_id'));

		$query = $this->db->update('sentence_bank', $data, array('id' => $this->input->post('sentence_id')));
		$query = $this->db->update('users', array('last_activity' => date('Y-m-d-H-i-s')), array('id' => $this->session->userdata('user_id')));

		if($query) {
			$result = array ('result'  => 'OK', 'message' => 'اطلاعات با موفقیت ثبت شد. با سپاس از همکاری شما!');
			echo json_encode($result);
		}
		else {
			$result = array ('result'  => 'ERROR', 'message' => 'متاسفانه خطایی در ثبت اطلاعات رخ داد!');
			echo json_encode($result);
		}
	}
	
	public function report_sentence() {

		$query = $this->db->update('sentence_bank', array('reported_by' => $this->session->userdata('user_id')), array('id' =>  $this->input->post('sentence_id')));
		if($query) {
			$result = array ('result'  => 'OK', 'message' => 'جمله با موفقیت گزارش شد!');
			echo json_encode($result);
		} else {
			$result = array ('result'  => 'ERROR', 'message' => 'خطایی در ثبت اطلاعات رخ داد!');
			echo json_encode($result);
		}
	}
	
	public function get_temporary_sentences() {
	
		$statements = $this->db->query("SELECT id, sentence_orig, trans_user FROM sentence_bank WHERE removed = 0 AND temporary = 1 AND  translator = ?", $this->session->userdata('user_id'))->result_array();
		echo json_encode($statements);
	}
	
	public function get_reported_sentences() {
		$statements = $this->db->query("SELECT sentence_bank.id, sentence_orig, users.name AS reporter FROM sentence_bank, users WHERE reported_by IS NOT NULL AND removed = 0 AND sentence_bank.reported_by = users.id AND sentence_group = ?", $this->session->userdata('user_group'))->result_array();
		echo json_encode($statements);
	}
	
	public function get_last24_translates() {
		$statements = $this->db->query("SELECT id,sentence_orig, trans_user FROM sentence_bank WHERE translator = ? and DATE(translated_at) >= DATE_SUB(CURDATE(), INTERVAL 24 Hour)", $this->session->userdata('user_id'))->result_array();
		echo json_encode($statements);
	}
	
	public function get_users() {
	
		if($this->session->userdata('user_type') != 'admin'){
			$result = array ('result'  => 'ERROR', 'message' => 'خطای سطح دسترسی!');
			echo json_encode($result);
			return;
		}
		$users = $this->db->query("SELECT id, name FROM users WHERE user_group = ?", $this->input->get('group'))->result_array();
		echo json_encode($users);
	}
}