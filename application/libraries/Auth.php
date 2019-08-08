<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

	protected $CI;
	protected $credentials;

	public function __construct($credentials = array()) {
        $this->CI =& get_instance();
        $this->credentials = $credentials;
    }

    public function login($username, $password) {
		$user = $this->CI->db->query("SELECT users.id, users.name,user_group, user_type, groups.name AS group_name FROM users, groups WHERE users.user_group = groups.id AND username = ? AND password = ? AND isActive = 1", array($username, hash_hmac('ripemd256', $password, $this->CI->config->item("my_key"))));
		if($user->num_rows() === 1) {
			$sessionData = array(
				'user_id'		=> $user->row_array()["id"],
		        'user_group'  	=> $user->row_array()["user_group"],
		        'group_name'  	=> $user->row_array()["group_name"],
		        'name'  		=> $user->row_array()["name"],
		        'user_type'  	=> $user->row_array()["user_type"],
		        'logged_in' 	=> TRUE
			);
			$this->CI->db->update('users', array('last_login' => date('Y-m-d-H-i-s')), array('id' => $user->row_array()["id"]));
			$this->CI->session->set_userdata($sessionData);
			return true;
		}
		return false;
    }

    public function signup($user_data) {
    	$user_data['password'] = hash_hmac('ripemd256', $user_data['password'], $this->CI->config->item("my_key"));

		$user = $this->CI->db->query("SELECT id FROM users WHERE username = ?", $user_data['username']);
		if($user->num_rows() === 0) {
			$query = $this->CI->db->insert('users', $user_data);
			if($query)
				return true;
			else
				return false;
		} else {
			return false;
		}
    }
    
    public function reset_pass($old_pass, $new_pass) {
    	if(strlen($new_pass) < 8)
    		return '<div class="pad margin no-print"><div class="callout callout-danger" style="margin-bottom: 0!important;">کلمه عبور حداقل باید 8 کاراکتر باشد!</div></div>';
    	$oldPass_hash = hash_hmac('ripemd256', $old_pass, $this->CI->config->item("my_key"));
		$user = $this->CI->db->query("SELECT * FROM users WHERE id = ? AND password = ?", array ($this->CI->session->userdata('user_id'), $oldPass_hash));
		if($user->num_rows() === 1) {
			$newPass_hash = hash_hmac('ripemd256', $new_pass, $this->CI->config->item("my_key"));
			$query = $this->CI->db->update('users', array ('password' => $newPass_hash), array('id' => $this->CI->session->userdata('user_id')));
			if($query)
				return '<div class="pad margin no-print"><div class="callout callout-success" style="margin-bottom: 0!important;">کلمه عبور با موفقیت تغییر یافت.</div></div>';
			else
				return '<div class="pad margin no-print"><div class="callout callout-danger" style="margin-bottom: 0!important;">خطا، لطفا دوباره امتحان کنید.</div></div>';
		} else {
			return '<div class="pad margin no-print"><div class="callout callout-danger" style="margin-bottom: 0!important;">کلمه عبور فعلی صحیح نمی باشد!</div></div>';
		}
    }

	public function verify() {
		if (!$this->is_logged_in() && $this->CI->router->fetch_class() != "login") {
			redirect(base_url()."login");
		} else {
			redirect(base_url());
		}
	}

	public function logout() {
		$this->CI->session->unset_userdata('logged_in');
		$this->CI->session->sess_destroy();
	}

	public function is_logged_in() {
		$logged_in = $this->CI->session->logged_in;
        if ($logged_in)
            return true;
        else
            return false;
	}
}
