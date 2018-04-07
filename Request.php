<?php 

class Request {

	function __construct($params = array())
	{
		$this->CI = &get_instance();
	}

	public function isGet()
	{
		return ($this->CI->input->server('REQUEST_METHOD') == 'GET');	
	}

	public function isPost()
	{
		return ($this->CI->input->server('REQUEST_METHOD') == 'POST');	
	}

	public function isAjax()
	{
		return $this->CI->input->is_ajax_request();
	}


}