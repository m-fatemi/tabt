<?php  if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
 
class Auth_hook {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }


    public function index() {
        $controller = $this->CI->router->fetch_class();
		if ($this->CI->auth->is_logged_in() && $controller == "login") {
            redirect(base_url());
            
        } else if(!$this->CI->auth->is_logged_in() && $controller != "login"){
            redirect(base_url()."login");
        }
		
		if($this->CI->session->userdata('user_type') != 'admin' && $controller == 'admin')
			show_404();
    }
}