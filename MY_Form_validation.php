<?php
class MY_Form_validation extends CI_Form_validation {

	protected $CI;

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->CI =& get_instance();
	}

	public function error_array()
	{
		if (count($this->_error_array) === 0) {
			return FALSE;
		} else {
			$this->createFormSession();
			return $this->_error_array;
		}
	}

    public function check_conditions($value, $params)
    {
        $this->CI->load->database();

		$this->CI->form_validation->set_message('check_conditions', "Sorry, that %s is already being added.");

		list($tableInfo, $columns, $values) = explode("|", $params);
		$tableInfo 	= explode('.', $tableInfo);
		$columns 	= explode('.', $columns);
		$values 	= explode('.', $values);
		$conditions = array($tableInfo[1] => $value);
		if(sizeof($columns)> 0) {
			foreach($columns as $key => $column) {
				if(isset($values[$key])) {
					$conditions[$column] = $values[$key];
				}
			}
		}
		$query = $this->CI->db->select()->get_where($tableInfo[0], $conditions);

		if($query->row()) {
			return FALSE;
		} else {
			return TRUE;
		}
    }

	public function edit_unique($value, $params)
	{
		$this->CI->load->database();

		$this->CI->form_validation->set_message('edit_unique', "Sorry, that %s is already being added.");

		list($table, $field, $current_id) = explode(".", $params);

		$query = $this->CI->db->select()->from($table)->where($field, $value)->limit(1)->get();

		if($query->row() && $query->row()->id != $current_id) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function createFormSession()
	{
		$formData = $this->CI->input->post();
		foreach($formData as $field => $value) {
			$this->CI->session->set_flashdata($field, $value);
		}
	}

}