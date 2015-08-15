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
 * Review_feature model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Review_feature_model extends CI_Model
{

	/*
	 * Review_feature model class constructor
	 */

	function Review_feature_model()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Feature_model');
	}

	/*
	 * addReviewFeature function
	 */

	function addReviewFeature($review_id, $feature_id, $value)
	{
		// add the review feature
		$data = array(
			'review_id' => $review_id,
			'feature_id' => $feature_id,
			'value' => $value
		);
		$this->db->insert('review_feature', $data);
	}

	/*
	 * updateReviewFeature function
	 */

	function updateReviewFeature($id, $feature_id, $value)
	{
		// update the review feature
		$data = array(
			'feature_id' => $feature_id,
			'value' => $value
		);
		$this->db->where('id', $id);
		$this->db->update('review_feature', $data);
	}

	/*
	 * deleteReviewFeaturesByFeatureId function
	 */

	function deleteReviewFeaturesByFeatureId($feature_id)
	{
		// delete all review features for the feature
		$this->db->where('feature_id', $feature_id);
		$this->db->delete('review_feature');
	}

	/*
	 * deleteReviewFeaturesByReviewId function
	 */

	function deleteReviewFeaturesByReviewId($review_id)
	{
		// delete all review features for the review
		$this->db->where('review_id', $review_id);
		$this->db->delete('review_feature');
	}

	/*
	 * deleteReviewFeatureById function
	 */

	function deleteReviewFeatureById($id)
	{
		// delete the review feature
		$this->db->where('id', $id);
		$this->db->delete('review_feature');
	}

	/*
	 * getReviewFeatureById function
	 */

	function getReviewFeatureById($id)
	{
		// return the review feature
		$this->db->where('id', $id);
		$query = $this->db->get('review_feature');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		// no result
		return FALSE;
	}

	/*
	 * getReviewFeaturesForReviewById function
	 */

	function getReviewFeaturesForReviewById($review_id, $limit = 0, $offset = 0)
	{
		// return review features for the review
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->select('a.id AS "review_feature_id",a.feature_id,a.review_id,a.value,b.id,b.name');
		$this->db->from('review_feature a');
		// join with 'feature' table to get the feature name
		$this->db->join('feature b', 'a.feature_id = b.id');
		$this->db->where('a.review_id', $review_id);
		$this->db->order_by('a.id');
		// load all the review features for this review
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			// return review features
			return $query->result();
		}
		// no results
		return FALSE;
	}

	/*
	 * countReviewFeaturesForReviewById function
	 */

	function countReviewFeaturesForReviewById($review_id)
	{
		// return the number of review features for the review
		$this->db->where('review_id', $review_id);
		$this->db->order_by('id');
		return $this->db->count_all_results('review_feature');
	}

}

/* End of file review_feature_model.php */
/* Location: ./application/models/review_feature_model.php */