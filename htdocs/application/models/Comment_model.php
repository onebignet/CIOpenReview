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
 * @license        CIOpenReview is free software licensed under the GNU General Public License version 2 - This file is part of CIOpenReview - free software licensed under the GNU General Public License version 2
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
 * Comment model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Comment_model extends CI_Model
{

	/*
	 * Comment model class constructor
	 */

	function comment_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addComment function
	 */

	function add_comment($review_id, $quotation, $source, $site_link, $approved, $visitor_rating)
	{
		$visitor_ip_address = $_SERVER['REMOTE_ADDR'];
		// the next section checks if the visitor has already posted a rating with a comment for this review.
		// after their first rating all other comments will be posted without a rating.
		// remove/comment this section to disable the IP address check and let visitors submit multiple ratings.
		if ($visitor_rating > 0) {
			$this->db->where('visitor_ip_address', $visitor_ip_address);
			$this->db->where('review_id', $review_id);
			$this->db->select('visitor_ip_address');
			$query = $this->db->get('comment');
			if ($query->num_rows() > 0) {
				$visitor_rating = 0;
			}
		}
		// end of IP check
		$data = array(
			'review_id' => $review_id,
			'quotation' => $quotation,
			'source' => $source,
			'site_link' => $site_link,
			'approved' => $approved,
			'visitor_rating' => $visitor_rating,
			'visitor_ip_address' => $visitor_ip_address
		);
		// add the comment
		$this->db->insert('comment', $data);
	}

	/*
	 * GetVisitorRatingForReviewById function
	 */

	function get_visitor_rating_for_review_by_id($id)
	{
		// calculates the average rating given by visitors for a review
		$this->db->select('visitor_rating');
		$this->db->where('review_id', $id);
		$this->db->where('approved', '1');
		$this->db->where('visitor_rating >', '0');
		$query = $this->db->get('comment');
		// count how many approved comments there are for this review
		$result_count = $query->num_rows();
		if ($result_count > 0) {
			$total_rating = 0;
			// total all the rating values
			foreach ($query->result() as $rating) {
				$total_rating += $rating->visitor_rating;
			}
			// calculate average
			$average_rating = round($total_rating / $result_count);
			// get the rating image for the rating value
			switch ($average_rating) {
				case 1:
					$rating_image = "rating_1.jpg";
					break;
				case 2:
					$rating_image = "rating_2.jpg";
					break;
				case 3:
					$rating_image = "rating_3.jpg";
					break;
				case 4:
					$rating_image = "rating_4.jpg";
					break;
				case 5:
					$rating_image = "rating_5.jpg";
					break;
			}
		} else {
			// no ratings
			$rating_image = 'not_rated.jpg';
		}
		return $rating_image;
	}

	/*
	 * UpdateComment function
	 */

	function update_comment($comment_id, $quotation, $source, $site_link, $approved)
	{
		// update the comment
		$data = array(
			'quotation' => $quotation,
			'source' => $source,
			'site_link' => $site_link,
			'approved' => $approved
		);
		$this->db->where('id', $comment_id);
		$this->db->update('comment', $data);
	}

	/*
	 * deleteCommentById function
	 */

	function delete_comment_by_id($id)
	{
		// delete the comment
		$this->db->where('id', $id);
		$this->db->delete('comment');
	}

	/*
	 * deleteCommentsByReviewId function
	 */

	function delete_comments_by_review_id($review_id)
	{
		// delete all comments for the review
		$this->db->where('review_id', $review_id);
		$this->db->delete('comment');
	}

	/*
	 * commentApproval function
	 */

	function comment_approval($comment_id, $value)
	{
		// approve or unapprove the comment based on the provided value
		$data = array('approved' => $value);
		$this->db->where('id', $comment_id);
		$this->db->update('comment', $data);
	}

	/*
	 * getCommentsForReviewBySeoTitle function
	 */

	function get_comments_for_review_by_seo_title($seo_title)
	{
		// return all comments for the review with this title
		$this->db->where('seo_title', $seo_title);
		$this->db->where('approved', '1');
		$query = $this->db->get('review');
		if ($query->num_rows() > 0) {
			$result = $query->row();
			$review_id = $result->id;
			$this->db->where('review_id', $review_id);
			$query = $this->db->get('comment');
			if ($query->num_rows() > 0) {
				// get the rating image for each result
				foreach ($query->result() as $result) {
					switch ($result->visitor_rating) {
						case 1:
							$result->rating_image = "rating_1.jpg";
							break;
						case 2:
							$result->rating_image = "rating_2.jpg";
							break;
						case 3:
							$result->rating_image = "rating_3.jpg";
							break;
						case 4:
							$result->rating_image = "rating_4.jpg";
							break;
						case 5:
							$result->rating_image = "rating_5.jpg";
							break;
						default:
							$result->rating_image = "";
							break;
					}
				}
				return $query->result();
			}
		}
		// no comments
		return FALSE;
	}

	/*
	 * getApprovedCommentsForReviewById function
	 */

	function get_approved_comments_for_review_by_id($id, $limit = 0, $offset = 0)
	{
		// get only approved comments for the review
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->where('review_id', $id);
		$this->db->where('approved', '1');
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('comment');
		if ($query->num_rows() > 0) {
			// get the rating image for each result
			foreach ($query->result() as $result) {
				switch ($result->visitor_rating) {
					case 1:
						$result->rating_image = "rating_1.jpg";
						break;
					case 2:
						$result->rating_image = "rating_2.jpg";
						break;
					case 3:
						$result->rating_image = "rating_3.jpg";
						break;
					case 4:
						$result->rating_image = "rating_4.jpg";
						break;
					case 5:
						$result->rating_image = "rating_5.jpg";
						break;
					default:
						$result->rating_image = "";
						break;
				}
			}
			return $query->result();
		}
		// no comments
		return FALSE;
	}

	/*
	 * getCommentsForReviewById function
	 */

	function get_comments_for_review_by_id($id, $limit = 0, $offset = 0)
	{
		// get only approved comments for the review
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->where('review_id', $id);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('comment');
		if ($query->num_rows() > 0) {
			// get the rating image for each result
			foreach ($query->result() as $result) {
				switch ($result->visitor_rating) {
					case 1:
						$result->rating_image = "rating_1.jpg";
						break;
					case 2:
						$result->rating_image = "rating_2.jpg";
						break;
					case 3:
						$result->rating_image = "rating_3.jpg";
						break;
					case 4:
						$result->rating_image = "rating_4.jpg";
						break;
					case 5:
						$result->rating_image = "rating_5.jpg";
						break;
					default:
						$result->rating_image = "";
						break;
				}
			}
			return $query->result();
		}
		// no comments
		return FALSE;
	}

	/*
	 * doesCommentExist function
	 */

	function does_comment_exist($quotation, $source)
	{
		// check if a comment already exists with the same name and text
		$this->db->where('quotation', $quotation);
		$this->db->where('source', $source);
		$query = $this->db->get('comment');
		return ($query->num_rows() > 0);
	}

	/*
	 * countCommentsForReviewById function
	 */

	function count_comments_for_review_by_id($id)
	{
		// return number of all comments for the review
		$this->db->where('review_id', $id);
		return $this->db->count_all_results('comment');
	}

	/*
	 * getCommentById function
	 */

	function get_comment_by_id($id)
	{
		// return the comment
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('comment');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result[0];
		}
		// no comment
		return FALSE;
	}

	/*
	 * getCommentsPending function
	 */

	function get_comments_pending($limit = 0, $offset = 0)
	{
		// get all pending comments
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->select('review.id AS review_id');
		$this->db->select('review.seo_title,review.title,comment.id,comment.review_id,comment.quotation,comment.source,comment.approved');
		$this->db->where('comment.approved', '0');
		$this->db->join('review', 'comment.review_id = review.id');
		$query = $this->db->get('comment');
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no comments
		return FALSE;
	}

	/*
	 * countCommentsPending function
	 */

	function count_comments_pending()
	{
		// return number of all comments pending
		$this->db->where('approved', '0');
		return $this->db->count_all_results('comment');
	}

}

/* End of file comment_model.php */
/* Location: ./application/models/comment_model.php */