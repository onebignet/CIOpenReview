<?php

/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @author        CIOpenReview.com
 * @copyright           Copyright (c) 2015 CIOpenReview.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
 * @license        This file is part of CIOpenReview - free software licensed under the GNU General Public License version 2
 * @link        http://CIOpenReview.com
 */
// ------------------------------------------------------------------------

/**    This file is part of CIOpenReview.
 *
 *    CIOpenReview is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    CIOpenReview is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with CIOpenReview.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Setting model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Setting_model extends CI_Model
{

	/*
	 * Setting model class constructor
	 */

	function Setting_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addSetting function
	 */

	function addSetting($name, $value = '')
	{
		// add the setting
		$name = trim($name);
		// format the setting without spaces
		$setting_name = url_title($name, '-', TRUE);
		if (!$this->checkSettingExists($setting_name)) {
			$data = array(
				'name' => $name,
				'value' => $value
			);
			// add the setting
			$this->db->insert('setting', $data);
			$setting = $this->getSettingByName($setting_name);
			// return the setting id
			return $setting->id;
		} else {
			// setting already exists... return FALSE
			return FALSE;
		}
	}

	/*
	 * checkSettingExists function
	 */

	function checkSettingExists($name, $id = -1)
	{
		// check a setting exists and return TRUE or FALSE
		$this->db->select('name');
		$this->db->where('name', $name);
		if ($id != -1) {
			// ignore a particular setting id
			$this->db->where('id !=', $id);
		}
		$query = $this->db->get('setting');
		if ($query->num_rows() > 0) {
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * updateSetting function
	 */

	function updateSetting($name, $value)
	{
		// update the setting or add it if it does not already exist
		if ($this->checkSettingExists($name)) {
			$data = array(
				'value' => $value
			);
			$this->db->where('name', $name);
			$this->db->update('setting', $data);
		} else {
			// setting does not exist so add it
			$this->addSetting($name, $value);
		}
	}

	/*
	 * deleteSetting function
	 */

	function deleteSetting($id)
	{
		// delete the setting
		$this->db->where('id', $id);
		$this->db->delete('page');
	}

	/*
	 * getSettingByName function
	 */

	function getSettingByName($name)
	{
		// return the setting
		$this->db->where('name', $name);
		$this->db->limit(1);
		$query = $this->db->get('setting');
		if ($query->num_rows() > 0) {
			return $query->row()->value;
		}
		// no result
		return FALSE;
	}

	/*
	 * getSettingById function
	 */

	function getSettingById($id)
	{
		// return the setting
		$this->db - where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('setting');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		// no result
		return FALSE;
	}

	/*
	 * getEverySetting function
	 */

	function getEverySetting()
	{
		// return all settings as an array
		$this->db->select('name,value');
		$query = $this->db->get('setting');
		if ($query->num_rows() > 0) {
			$settings_array = array();
			foreach ($query->result() as $row) {
				$settings_array[$row->name] = $row->value;
			}
			// return the settings
			return $settings_array;
		}
		// no results
		return FALSE;
	}

}

/* End of file setting_model.php */
/* Location: ./application/models/setting_model.php */