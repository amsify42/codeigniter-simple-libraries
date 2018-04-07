<?php 

class Layout {

	 private $layout 	= 'frontend';

	 private $data 		= array(
		 						'header' => array(
		 							'title' => 'Smart Core Labs'
		 						),
		 						'menu' 	 => array(
		 							'title' => 'home'
		 						),	
		 						'footer' => array(

		 						)	
	 						);

	 function __construct($layout = array())
	 {
	 	if(isset($layout['name']))
	 	$this->layout 	= $layout['name'];
	 	$this->CI 		= &get_instance();
	 	$this->CI->load->model("basic");
	 	$this->CI->load->model("state");
	 	$this->CI->load->model("city");
	 }

	 public function view($file, $data = array(), $title = '', $menu = '')
	 {
	 	// If login session is created
	 	if(isset($this->CI->session) && sizeof($this->CI->session->userdata()) > 1) {
	 		$data['userdata']					= $this->CI->session->userdata();
	 		$this->data['userdata'] 			= $this->CI->session->userdata();
	 	 	$this->data['header']['userdata'] 	= $this->CI->session->userdata();
	 	 	$this->data['menu']['userdata'] 	= $this->CI->session->userdata();
	 	 	$this->data['footer']['userdata'] 	= $this->CI->session->userdata();
	 	}
	 	if($this->layout == 'frontend') {
	 		$this->data['selections'] 			= array();
	 		$this->data['selections']['states'] = $this->CI->state->where('country_id', 231)->result();
	 		$data['selections'] 				= $this->data['selections'];
	 	}
	 	$data['basicModel'] 			= $this->CI->basic;
	 	$this->data['_render_body'] 	= $this->CI->load->view($this->layout.'/'.$file, $data, TRUE);
	 	$this->data['render_section'] 	= $this->CI;

	 	// Set title if passed
	 	if($title != '') {
	 		$this->data['header']['title'] = $title.' | '.$this->data['header']['title'];
	 	}

	 	// Set Menu if passed
	 	if($menu != '') {
	 		$this->data['menu']['title']   = $menu;
	 	}

	 	$this->CI->load->view('layout/'.$this->layout, $this->data);
	 }	

}