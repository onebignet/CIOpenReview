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
 * Rating model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Rating_model extends CI_Model
{

	/*
	 * Rating model class constructor
	 */

	function Rating_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addRating function
	 */

	function addRating($name)
	{
		// add the rating
		$data = array(
			'name' => $name
		);
		$this->db->insert('rating', $data);
	}

	/*
	 * updateRating function
	 */

	function updateRating($id, $name)
	{
		// update the rating
		$data = array(
			'name' => $name
		);
		$this->db->where('id', $id);
		$this->db->update('rating', $data);
	}

	/*
	 * deleteRating function
	 */

	function deleteRating($id)
	{
		// delete the rating
		$this->db->where('rating_id', $id);
		$this->db->delete('category_default_rating');
		$this->db->where('id', $id);
		$this->db->delete('rating');
	}

	/*
	 * getRatingById function
	 */

	function getRatingById($id)
	{
		// return the rating
		$this->db->where('id', $id);
		$query = $this->db->get('rating');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}

	/*
	 * getAllRatings function
	 */

	function getAllRatings($limit, $offset = 0)
	{
		// return all ratings
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		$this->db->order_by('name');
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get('rating');
		// return the ratings
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no results
		return FALSE;
	}

	/*
	 * countRatings function
	 */

	function countRatings()
	{
		return $this->db->count_all_results('rating');
	}

	/*
	 * getRatingsDropDown function
	 */

	function getRatingsDropDown($no_blank = 0)
	{
		// get data for ratings drop down list
		$this->db->order_by('id');
		$query = $this->db->get('rating');
		if ($query->num_rows() > 0) {
			if ($no_blank < 1) {
				$ratings[0] = '--------';
			}
			foreach ($query->result() as $rating_row) {
				$ratings[$rating_row->id] = $rating_row->name;
			}
			// return the ratings list
			return $ratings;
		}
		// no results
		return FALSE;
	}

}

/* End of file rating_model.php */
/* Location: ./application/models/rating_model.php */