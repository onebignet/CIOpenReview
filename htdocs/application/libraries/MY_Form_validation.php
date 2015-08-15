<?php

class MY_Form_validation extends CI_Form_validation
{

	function __construct()
	{
		parent::__construct();
	}

	// Convert PHP tags to entities

	public function clear_fields()
	{
		$this->_field_data = array();
	}

}