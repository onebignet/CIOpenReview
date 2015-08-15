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
 * Feature model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Feature_model extends CI_Model
{

	/*
	 * Feature model class constructor
	 */

	function Feature_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addFeature function
	 */

	function addFeature($name)
	{
		// add the feature
		$data = array(
			'name' => $name
		);
		$this->db->insert('feature', $data);
	}

	/*
	 * updateFeature function
	 */

	function updateFeature($id, $name)
	{
		// update the feature
		$data = array(
			'name' => $name
		);
		$this->db->where('id', $id);
		$this->db->update('feature', $data);
	}

	/*
	 * deleteFeature function
	 */

	function deleteFeature($id)
	{
		// delete the feature
		$this->db->where('feature_id', $id);
		$this->db->delete('category_default_feature');
		$this->db->where('id', $id);
		$this->db->delete('feature');
	}

	/*
	 * getFeatureById function
	 */

	function getFeatureById($id)
	{
		// return the feature
		$this->db->where('id', $id);
		$query = $this->db->get('feature');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		// no result
		return FALSE;
	}

	/*
	 * getAllFeatures function
	 */

	function getAllFeatures($limit, $offset = 0)
	{
		// return all features
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		$this->db->order_by('name');
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get('feature');
		// return the features
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no results
		return FALSE;
	}

	/*
	 * countFeatures function
	 */

	function countFeatures()
	{
		// return total number of all features
		return $this->db->count_all_results('feature');
	}

	/*
	 * getFeaturesDropDown function
	 */

	function getFeaturesDropDown($no_blank = 0)
	{
		// get data for features drop down list
		$this->db->order_by('id');
		$query = $this->db->get('feature');
		if ($query->num_rows() > 0) {
			if ($no_blank < 1) {
				$features[0] = '--------';
			}
			foreach ($query->result() as $feature_row) {
				$features[$feature_row->id] = $feature_row->name;
			}
			// return the features list
			return $features;
		}
		// no results
		return FALSE;
	}

}

/* End of file feature_model.php */
/* Location: ./application/models/feature_model.php */