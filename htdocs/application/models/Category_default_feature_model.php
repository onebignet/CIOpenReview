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
 * Category_default_feature model class
 * These are the features that get added to a review by default when it is created
 * The default features that are added depend on which category the review is in
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Category_default_feature_model extends CI_Model
{

	/*
	 * Category_default_feature model class constructor
	 */

	function category_default_feature_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addDefaultFeature function
	 */

	function add_default_feature($feature_id, $category_id)
	{
		// add a default feature for the category
		$data = array(
			'feature_id' => $feature_id,
			'category_id' => $category_id
		);
		$this->db->insert('category_default_feature', $data);
	}

	/*
	 * deleteDefaultFeature function
	 */

	function delete_default_feature($id)
	{
		// delete a default feature
		$this->db->where('id', $id);
		$this->db->delete('category_default_feature');
	}

	/*
	 * getDefaultFeatureById function
	 */

	function get_default_feature_by_id($id)
	{
		// return a default feature
		$this->db->where('id', $id);
		$query = $this->db->get('category_default_feature');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		// no results
		return FALSE;
	}

	/*
	 * getDefaultFeaturesByCategoryId function
	 */

	function get_default_features_by_category_id($category_id)
	{
		// return all the default features for a category
		$this->db->select('category_default_feature.id AS id');
		$this->db->select('feature.name');
		$this->db->select('feature.id AS att_id');
		$this->db->where('category_id', $category_id);
		$this->db->join('feature', 'category_default_feature.feature_id = feature.id');
		$query = $this->db->get('category_default_feature');
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no results
		return FALSE;
	}

}

/* End of file category_default_feature_model.php */
/* Location: ./application/models/category_default_feature_model.php */