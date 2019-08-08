<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Utils extends CI_Model {


	public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Tehran');
        
    }
	
    public function level_name($group_id) {
        switch($group_id){
			case 1:
				return '<span class="badge bg-green">A</span>';
				break;
			case 2:
				return '<span class="badge bg-orange">B</span>';
				break;
			case 3:
				return '<span class="badge bg-yellow">C</span>';
				break;
			case 4:
				return '<span class="badge bg-red">D</span>';
				break;
			case 5:
				return '<span class="badge bg-red">E</span>';
				break;
		}
    }
}


