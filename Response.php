<?php 

class Response {

	private $contentType 	= 'application/json';
	private $statusCode		= 200;
	private $data			= array();

	function __construct($params = array())
	{
		if(isset($params['type'])) {
			$this->contentType = 'application/'.strtolower($type);
		}
		$this->CI = &get_instance();
	}

	public function output($data = array(), $code = 0, $type = '')
	{
		$this->contentType 	= ($type)? 'application/'.strtolower($type): $this->contentType;
		$this->statusCode 	= ($code)? $code: $this->statusCode;
		return $this->CI->output->set_content_type($this->contentType)
            					->set_status_header($this->statusCode)
            					->set_output(json_encode($data));
	}



}