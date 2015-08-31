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
 * Review_rating model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Review_rating_model extends CI_Model
{

	/*
	 * Review_rating model class constructor
	 */

	function review_rating_model()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Rating_model');
	}

	/*
	 * addReviewRating function
	 */

	function add_review_rating($review_id, $rating_id, $value)
	{
		// add the review rating
		$data = array(
			'review_id' => $review_id,
			'rating_id' => $rating_id,
			'value' => $value
		);
		$this->db->insert('review_rating', $data);
	}

	/*
	 * updateReviewRating function
	 */

	function update_review_rating($id, $rating_id, $value)
	{
		// update the review rating
		$data = array(
			'rating_id' => $rating_id,
			'value' => $value
		);
		$this->db->where('id', $id);
		$this->db->update('review_rating', $data);
	}

	/*
	 * deleteReviewRatingsByRatingId function
	 */

	function delete_review_ratings_by_rating_id($rating_id)
	{
		// delete all review ratings for the rating
		$this->db->where('rating_id', $rating_id);
		$this->db->delete('review_rating');
	}

	/*
	 * deleteReviewRatingsByReviewId function
	 */

	function delete_review_ratings_by_review_id($review_id)
	{
		// delete all review ratings for the review
		$this->db->where('review_id', $review_id);
		$this->db->delete('review_rating');
	}

	/*
	 * deleteReviewRatingById function
	 */

	function delete_review_rating_by_id($id)
	{
		// delete the review rating
		$this->db->where('id', $id);
		$this->db->delete('review_rating');
	}

	/*
	 * getReviewRatingById function
	 */

	function get_review_rating_by_id($id)
	{
		// return the review rating
		$this->db->where('id', $id);
		$query = $this->db->get('review_rating');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		// no result
		return FALSE;
	}

	/*
	 * getReviewRatingsForReviewById function
	 */

	function get_review_ratings_for_review_by_id($review_id, $limit = 0, $offset = 0)
	{
		// return review ratings for the review
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->where('review_id', $review_id);
		$this->db->order_by('id');
		// load all the review ratings for this review
		$query = $this->db->get('review_rating');
		if ($query->num_rows() > 0) {
			// set the rating image for each review rating
			foreach ($query->result() as $result) {
				$result->rating_name = $this->Rating_model->get_rating_by_id($result->rating_id)->name;
				$result->rating_value = $result->value;
			}
			// return review ratings
			return $query->result();
		}
		// no results
		return FALSE;
	}

	/*
	 * countReviewRatingsForReviewById function
	 */

	function count_review_ratings_for_review_by_id($review_id)
	{
		// return the number of review ratings for the review
		$this->db->where('review_id', $review_id);
		$this->db->order_by('id');
		return $this->db->count_all_results('review_rating');
	}

}

/* End of file review_rating_model.php */
/* Location: ./application/models/review_rating_model.php */