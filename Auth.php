<?php 

class Auth {

	 private $table 		= 'users';
	 private $redirect		= 'login';
	 private $redirectInfo	= array(
	 				'super' => 'login',
	 				'admin' => 'login',
	 				'user' 	=> 'login',
	 			);

	 private $dashboardRole = array(
							'super' 	=> 'super-admin/dashboard',
							'admin' 	=> 'admin/dashboard',
							'user' 		=> 'user/dashboard',
						);		

	 function __construct($params = array()) {
	 	if(isset($params['table'])) {
	 		$this->table = $params['table'];
	 	}
	 	if(isset($params['redirect'])) {
	 		$this->redirect = $params['redirect'];
	 	}
	 	$this->CI = & get_instance();
	 }


	 public function filter($role) {

	 	 $redirect = true;

	 	 if(sizeof($this->CI->session->userdata()) > 1) {
	 	 	if($role == $this->CI->session->userdata('role')) {
	 	 		$redirect = false;
	 	 	}
	 	 }
	 	 
	 	 if($redirect) {
	 	 	$this->CI->session->set_flashdata('error', 'Please login to view section');
	 	 	if(isset($this->redirectInfo) && isset($this->redirectInfo[$role])) {
	 	 		redirect($this->redirectInfo[$role]);
	 	 	} else {
	 	 		redirect($this->redirect);
	 	 	}
	 	 }
	 }


	 public function redirect($redirect) {
	 	if(sizeof($this->CI->session->userdata()) > 1) {
	 	 	redirect($redirect);
	 	 }
	 }

	 public function attempt($credentials = array(), $table = '') {

 		if(sizeof($credentials) > 0) {
 			// If table name is passed from parameter
 			if($table != '') {
	 			$this->table = $table;
	 		}

	 		// If credentials are authenticated
	 		if($this->checkTable($credentials)) {
	 			return true;
	 		}
 		}
 		return false;
	 }


	 // Compare Credentials value with  
	 public function checkTable($credentials) {

	 	if(isset($credentials['password'])) {
	 		$credentials['password'] = md5($credentials['password']);
	 	}

	 	$result = $this->CI->db->get_where($this->table, $credentials)->row();

	 	if(sizeof($result) > 0) {
	 		$data 			= array();
	 		$data['type'] 	= $this->table;
	 		foreach($result as $key => $value) {
	 			if($key != 'password') {
	 				$data[$key] = $value;
	 			}
	 		}
	 		$this->CI->session->set_userdata($data);
	 		return true;
	 	}

	 	return false;
	 }

	public function getLoginRedirect() {
		
		if(sizeof($this->CI->session->userdata()) > 1) {
			$role = $this->CI->session->userdata('role');
			if(isset($this->dashboardRole[$role])) {
				return $this->dashboardRole[$role];
			}
		}
		return $this->dashboardRole['user'];
	}


	 public function logOut($role = 'user') {
	 	foreach($this->CI->session->userdata() as $key => $value) {
	 		$this->CI->session->unset_userdata($key);
	 	}
	 	$this->CI->session->set_flashdata('message', 'You have been logged out');
	 	redirect($this->redirectInfo[$role]);
	 }	

}