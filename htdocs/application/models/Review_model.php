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
 * Review model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Review_model extends CI_Model
{

	/*
	 * Review model class constructor
	 */

	function Review_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addReview function
	 */

	function addReview($title, $description, $category_id, $featured, $tags, $image_name, $image_extension, $vendor, $link, $meta_keywords, $meta_description, $approved)
	{
		// add the review
		// check the title is unique and append a number if it is not
		$title_add = 2;
		$title = trim($title);
		$seo_title = url_title($title, '-', TRUE);
		$safe_seo_title = $seo_title;
		while ($this->_checkSeoTitleExists($safe_seo_title)) {
			// keep trying numbers until the title is unique
			$safe_seo_title = $seo_title . '-' . $title_add;
			$title_add++;
		}
		// prepare tags array
		$tag_array = explode(',', $tags);
		$tags = $this->removeSpacesFromTags($tag_array);
		$data = array(
			'title' => $title,
			'seo_title' => $safe_seo_title,
			'description' => $description,
			'category_id' => $category_id,
			'featured' => $featured,
			'image_name' => $image_name,
			'image_extension' => $image_extension,
			'vendor' => $vendor,
			'link' => $link,
			'meta_keywords' => $meta_keywords,
			'meta_description' => $meta_description,
			'tags' => $tags,
			'approved' => $approved
		);
		// add the review
		$this->db->insert('review', $data);
		$review = $this->getReviewBySeoTitle($safe_seo_title);
		$id = $review->id;
		// update the tags for the review
		$tags = $this->updateTags($id, $tag_array);
		// return the review id
		return $id;
	}

	/*
	 * updateReview function
	 */

	function updateReview($id, $title, $description, $category_id, $featured, $tags, $image_name, $image_extension, $vendor, $link, $meta_keywords, $meta_description)
	{
		// update the review
		// check the title is unique and append a number if it is not
		$title_add = 2;
		$title = trim($title);
		$seo_title = url_title($title, '-', TRUE);
		$safe_seo_title = $seo_title;
		while ($this->_checkSeoTitleExists($safe_seo_title, $id)) {
			// keep trying numbers until the title is unique
			$safe_seo_title = $seo_title . '-' . $title_add;
			$title_add++;
		}
		// prepare tags array
		$tag_array = explode(',', $tags);
		$tags = $this->removeSpacesFromTags($tag_array);
		$data = array(
			'title' => $title,
			'seo_title' => $safe_seo_title,
			'description' => $description,
			'category_id' => $category_id,
			'featured' => $featured,
			'image_name' => $image_name,
			'image_extension' => $image_extension,
			'vendor' => $vendor,
			'link' => $link,
			'meta_keywords' => $meta_keywords,
			'meta_description' => $meta_description,
			'tags' => $tags
		);
		$this->db->where('id', $id);
		// update the review
		$this->db->update('review', $data);
		// update the tags for the review
		$tags = $this->updateTags($id, $tag_array);
	}

	/*
	 * addClick function
	 */

	function addClick($id)
	{
		// count a click for the review link
		$this->db->where('id', $id);
		$this->db->set('clicks', 'clicks+1', FALSE);
		$this->db->update('review');
	}

	/*
	 * addView function
	 */

	function addView($id)
	{
		// count a page view for the review
		$this->db->where('id', $id);
		$this->db->set('views', 'views+1', FALSE);
		$this->db->update('review');
	}

	/*
	 * mostViewed function
	 */

	function mostViewed($limit = 10)
	{
		// return a list of reviews in descending page view order
		// limit the results, default is 10
		$this->db->limit($limit);
		$this->db->order_by('views', 'DESC');
		$query = $this->db->get('review');
		// return the reviews
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no results
		return FALSE;
	}

	/*
	 * mostClicks function
	 */

	function mostClicks($limit = 10)
	{
		// return a list of reviews in descending link clicks order
		// limit the results, default is 10
		$this->db->limit($limit);
		$this->db->order_by('clicks', 'DESC');
		$query = $this->db->get('review');
		// return the reviews
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no results
		return FALSE;
	}

	/*
	 * changeCategory function
	 */

	function changeCategory($from_id, $to_id)
	{
		// change all reviews in one category to another category
		// 'moves' reviews to a category
		if (($from_id > 0) && ($to_id > 0)) {
			$this->db->where('category_id', $from_id);
			$this->db->set('category_id', $to_id);
			$this->db->update('review');
		}
	}

	/*
	 * deleteReview function
	 */

	function deleteReview($id)
	{
		// delete the review
		$this->db->where('id', $id);
		$this->db->delete('review');
		$this->deleteTags($id);
	}

	/*
	 * _checkSeoTitleExists function
	 */

	function _checkSeoTitleExists($seo_title, $id = -1)
	{
		// check the title is not already being used
		$this->db->select('seo_title');
		$this->db->where('seo_title', $seo_title);
		// optionally ignore a particular review
		// this is used when editing so the current title of an review can be ignored
		if ($id != -1) {
			$this->db->where('id !=', $id);
		}
		// return TRUE if the title exists, FALSE if not
		$query = $this->db->get('review');
		if ($query->num_rows() > 0) {
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * getTagCloudArray function
	 */

	function getTagCloudArray()
	{
		// return the tag cloud
		$this->db->select('tag,COUNT(*) AS tagcount');
		$this->db->group_by('tag');
		$query = $this->db->get('tags');
		if ($query->num_rows() > 0) {
			$tag_array = array();
			$results = $query->result();
			// build the tag array
			foreach ($results as $ind => $result) {
				$tag_item = array($result->tagcount, $result->tag);
				$tag_array[] = $tag_item;
			}
			// return tag cloud
			return $tag_array;
		}
		// no results
		return FALSE;
	}

	/*
	 * getFeaturedReviews function
	 */

	function getFeaturedReviews($limit, $offset = 0, $approval_required = 0)
	{
		// return all featured reviews
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->where('featured', 1);
		if ($approval_required > 0)
			$this->db->where('approved', '1');
		$this->db->order_by('id', 'asc');
		$query = $this->db->get('review');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			// set the image paths for each result
			foreach ($result as $key => $item) {
				if ($result[$key]->image_name === '') {
					$result[$key]->image_url = template_path() . 'design/images/default_image.jpg';
					$result[$key]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
					$result[$key]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				} else {
					$result[$key]->image_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '.' . $result[$key]->image_extension;
					$result[$key]->review_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_review_thumb.' . $result[$key]->image_extension;
					$result[$key]->list_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_list_thumb.' . $result[$key]->image_extension;
				}
			}
			// return the reviews
			return $result;
		}
		// no results
		return FALSE;
	}

	/*
	 * getLatestReviews function
	 */

	function getLatestReviews($limit = 0, $offset = 0, $approval_required = 0)
	{
		// return latest reviews
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		// newest first
		$this->db->order_by('id', 'desc');
		if ($approval_required > 0)
			$this->db->where('approved', '1');
		$query = $this->db->get('review');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			// set the image paths for each result
			foreach ($result as $key => $item) {
				$result[$key]->tags = array_filter(explode(',', $result[$key]->tags));
				if ($result[$key]->image_name === '') {
					$result[$key]->image_url = template_path() . 'design/images/default_image.jpg';
					$result[$key]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
					$result[$key]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				} else {
					$result[$key]->image_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '.' . $result[$key]->image_extension;
					$result[$key]->review_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_review_thumb.' . $result[$key]->image_extension;
					$result[$key]->list_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_list_thumb.' . $result[$key]->image_extension;
				}
			}
			// return the reviews
			return $result;
		}
		// no results
		return FALSE;
	}

	/*
	 * countReviews function
	 */

	function countReviews()
	{
		// return the total number of all reviews
		return $this->db->count_all_results('review');
	}

	/*
	 * countCategoryReviews function
	 */

	function countCategoryReviews($category_seo_name, $approval_required = 0)
	{
		// return the number of reviews in the category
		$this->db->where('seo_name', $category_seo_name);
		$query = $this->db->get('category');
		if ($query->num_rows() > 0) {
			$result = $query->row();
			$category_id = $result->id;
			if ($approval_required > 0)
				$this->db->where('approved', '1');
			$this->db->where('category_id', $category_id);
			$query = $this->db->get('review');
			// return the number of reviews
			return $query->num_rows();
		}
		// no results
		return FALSE;
	}

	/*
	 * getReviewsByCategorySeoName function
	 */

	function getReviewsByCategorySeoName($category_seo_name, $limit, $offset = 0, $approval_required = 0)
	{
		// return reviews for the category
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		$this->db->where('seo_name', $category_seo_name);
		$query = $this->db->get('category');
		if ($query->num_rows() > 0) {
			// category exists
			$result = $query->row();
			$category_id = $result->id;
			$this->db->where('category_id', $category_id);
			// if a limit more than zero is provided, limit the results
			if ($limit > 0) {
				$this->db->limit($limit, $offset);
			}
			if ($approval_required > 0)
				$this->db->where('approved', '1');
			// get all approved reviews in the category
			$query = $this->db->get('review');
			if ($query->num_rows() > 0) {
				$result = $query->result();
				// set the image paths for each result
				foreach ($result as $key => $item) {
					if ($result[$key]->image_name === '') {
						$result[$key]->image_url = template_path() . 'design/images/default_image.jpg';
						$result[$key]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
						$result[$key]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
					} else {
						$result[$key]->image_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '.' . $result[$key]->image_extension;
						$result[$key]->review_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_review_thumb.' . $result[$key]->image_extension;
						$result[$key]->list_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_list_thumb.' . $result[$key]->image_extension;
					}
					$result[$key]->tags = array_filter(explode(',', $result[$key]->tags));
				}
				// return the reviews
				return $result;
			}
		}
		// no results
		return FALSE;
	}

	/*
	 * getReviewsByKeyword function
	 */

	function getReviewsByKeyword($keyword, $limit, $offset = 0, $approval_required = 0)
	{
		// return reviews that match the keyword(s)
		// note that some keywords will be ignored as stop words
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->distinct();
		$this->db->where('MATCH (review.title, review.description, review.tags) AGAINST ("' . $keyword . '" IN BOOLEAN MODE)', NULL, FALSE);
		if ($approval_required > 0)
			$this->db->where('approved', '1');
		// get all matching approved reviews
		$this->db->group_by("review.id");
		$query = $this->db->get('review');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			// set the image paths for each result
			foreach ($result as $key => $item) {
				$result[$key]->tags = array_filter(explode(',', $result[$key]->tags));
				if ($result[$key]->image_name === '') {
					$result[$key]->image_url = template_path() . 'design/images/default_image.jpg';
					$result[$key]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
					$result[$key]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				} else {
					$result[$key]->image_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '.' . $result[$key]->image_extension;
					$result[$key]->review_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_review_thumb.' . $result[$key]->image_extension;
					$result[$key]->list_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_list_thumb.' . $result[$key]->image_extension;
				}
			}
			// return the reviews
			return $result;
		}
		// no results
		return FALSE;
	}

	/*
	 * countKeywordReviews function
	 */

	function countKeywordReviews($keyword, $approval_required = 0)
	{
		// return the number of reviews matching the keyword(s)
		// note that some keywords will be ignored as stop words
		$this->db->distinct();
		$this->db->where('MATCH (review.title, review.description, review.tags) AGAINST ("' . $keyword . '" IN BOOLEAN MODE)', NULL, FALSE);
		$this->db->group_by("review.id");
		if ($approval_required > 0)
			$this->db->where('approved', '1');
		$query = $this->db->get('review');
		// return the reviews
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $query->num_rows();
		}
		// no results
		return FALSE;
	}

	/*
	 * getReviewBySeoTitle function
	 */

	function getReviewBySeoTitle($param)
	{
		// return the review
		$this->db->where('seo_title', $param);
		$this->db->limit(1);
		$query = $this->db->get('review');
		// set the image paths for the review
		if ($query->num_rows() > 0) {
			if ($query->row()->image_name === '') {
				$query->row()->image_url = template_path() . 'design/images/default_image.jpg';
				$query->row()->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				$query->row()->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
			} else {
				$query->row()->image_url = base_url() . 'uploads/images/' . $query->row()->image_name . '.' . $query->row()->image_extension;
				$query->row()->review_thumb_url = base_url() . 'uploads/images/' . $query->row()->image_name . '_review_thumb.' . $query->row()->image_extension;
				$query->row()->list_thumb_url = base_url() . 'uploads/images/' . $query->row()->image_name . '_list_thumb.' . $query->row()->image_extension;
			}
			// return the review
			return $query->row();
		}
		// no result
		return FALSE;
	}

	/*
	 * getReviewForCommentId function
	 */

	function getReviewForCommentId($id)
	{
		// return the review based on the comment id
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('comment');
		if ($query->num_rows() > 0) {
			$result = $query->row();
			$this->db->where('id', $result->review_id);
			$this->db->limit(1);
			$query = $this->db->get('review');
			if ($query->num_rows() > 0) {
				$result = $query->result();
				// set the image paths for the review
				if ($result[0]->image_name === '') {
					$result[0]->image_url = template_path() . 'design/images/default_image.jpg';
					$result[0]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
					$result[0]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				} else {
					$result[0]->image_url = base_url() . 'uploads/images/' . $result[0]->image_name . '.' . $result[0]->image_extension;
					$result[0]->review_thumb_url = base_url() . 'uploads/images/' . $result[0]->image_name . '_review_thumb.' . $result[0]->image_extension;
					$result[0]->list_thumb_url = base_url() . 'uploads/images/' . $result[0]->image_name . '_list_thumb.' . $result[0]->image_extension;
				}
				// return the review
				return $result[0];
			}
		}
		// no result
		return FALSE;
	}

	/*
	 * getReviewForReviewRatingId function
	 */

	function getReviewForReviewRatingId($id)
	{
		// return the review based on the review rating id
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('review_rating');
		if ($query->num_rows() > 0) {
			$result = $query->row();
			$this->db->where('id', $result->review_id);
			$this->db->limit(1);
			$query = $this->db->get('review');
			if ($query->num_rows() > 0) {
				$result = $query->result();
				// set the image paths for the review
				if ($result[0]->image_name === '') {
					$result[0]->image_url = template_path() . 'design/images/default_image.jpg';
					$result[0]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
					$result[0]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				} else {
					$result[0]->image_url = base_url() . 'uploads/images/' . $result[0]->image_name . '.' . $result[0]->image_extension;
					$result[0]->review_thumb_url = base_url() . 'uploads/images/' . $result[0]->image_name . '_review_thumb.' . $result[0]->image_extension;
					$result[0]->list_thumb_url = base_url() . 'uploads/images/' . $result[0]->image_name . '_list_thumb.' . $result[0]->image_extension;
				}
				// return the review
				return $result[0];
			}
		}
		// no result
		return FALSE;
	}

	/*
	 * getReviewForReviewFeatureId function
	 */

	function getReviewForReviewFeatureId($id)
	{
		// return the review based on the review feature id
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('review_feature');
		if ($query->num_rows() > 0) {
			$result = $query->row();
			$this->db->where('id', $result->review_id);
			$this->db->limit(1);
			$query = $this->db->get('review');
			if ($query->num_rows() > 0) {
				$result = $query->result();
				// set the image paths for the review
				if ($result[0]->image_name === '') {
					$result[0]->image_url = template_path() . 'design/images/default_image.jpg';
					$result[0]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
					$result[0]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				} else {
					$result[0]->image_url = base_url() . 'uploads/images/' . $result[0]->image_name . '.' . $result[0]->image_extension;
					$result[0]->review_thumb_url = base_url() . 'uploads/images/' . $result[0]->image_name . '_review_thumb.' . $result[0]->image_extension;
					$result[0]->list_thumb_url = base_url() . 'uploads/images/' . $result[0]->image_name . '_list_thumb.' . $result[0]->image_extension;
				}
				// return the review
				return $result[0];
			}
		}
		// no result
		return FALSE;
	}

	/*
	 * getReviewById function
	 */

	function getReviewById($id)
	{
		// return the review
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('review');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			// set the image paths for the review
			if ($result[0]->image_name === '') {
				$result[0]->image_url = template_path() . 'design/images/default_image.jpg';
				$result[0]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				$result[0]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
			} else {
				$result[0]->image_url = base_url() . 'uploads/images/' . $result[0]->image_name . '.' . $result[0]->image_extension;
				$result[0]->review_thumb_url = base_url() . 'uploads/images/' . $result[0]->image_name . '_review_thumb.' . $result[0]->image_extension;
				$result[0]->list_thumb_url = base_url() . 'uploads/images/' . $result[0]->image_name . '_list_thumb.' . $result[0]->image_extension;
			}
			// return the review
			return $result[0];
		}
		// no result
		return FALSE;
	}

	/*
	 * getAllReviews function
	 */

	function getAllReviews($limit, $offset = 0)
	{
		// return all reviews
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('review');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			// set the image paths for each result
			foreach ($result as $key => $item) {
				if ($result[$key]->image_name === '') {
					$result[$key]->image_url = template_path() . 'design/images/default_image.jpg';
					$result[$key]->review_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
					$result[$key]->list_thumb_url = template_path() . 'design/images/default_image_thumb.jpg';
				} else {
					$result[$key]->image_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '.' . $result[$key]->image_extension;
					$result[$key]->review_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_review_thumb.' . $result[$key]->image_extension;
					$result[$key]->list_thumb_url = base_url() . 'uploads/images/' . $result[$key]->image_name . '_list_thumb.' . $result[$key]->image_extension;
				}
			}
			// return the reviews
			return $result;
		}
		// no result
		return FALSE;
	}

	/*
	 * updateTags function
	 */

	function updateTags($id, $tag_array)
	{
		// update the tags for the review
		$this->db->where('review_id', $id);
		$this->db->delete('tags');
		$tag_string = '';
		foreach ($tag_array as $tag) {
			$tag = trim($tag);
			// insert the tag in the tags table
			if ($tag !== '') {
				$this->db->set('review_id', $id);
				$this->db->set('tag', $tag);
				$this->db->insert('tags');
				$tag_string .= $tag . ',';
			}
		}
		$tag_string = substr($tag_string, 0, -1);
		// return the tags
		return $tag_string;
	}

	/*
	 * deleteTags function
	 */

	function deleteTags($id)
	{
		// delete all tags for the review
		$this->db->where('review_id', $id);
		$this->db->delete('tags');
	}

	/*
	 * removeSpacesFromTags function
	 */

	function removeSpacesFromTags($tag_array)
	{
		// remove extra spaces from all tags and return as a string
		$tag_string = '';
		foreach ($tag_array as $tag) {
			$tag = trim($tag);
			if ($tag !== '') {
				$tag_string .= $tag . ',';
			}
		}
		$tag_string = substr($tag_string, 0, -1);
		// return the tags
		return $tag_string;
	}

	/*
	 * getReviewsPending function
	 */

	function getReviewsPending($limit, $offset = 0)
	{
		// return all pending reviews
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		// ascending id order so we get the 'oldest' pending reviews at the top of the list
		$this->db->order_by('id', 'ASC');
		$this->db->where('approved', '0');
		$query = $this->db->get('review');
		// return the reviews
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no results
		return FALSE;
	}

	/*
	 * countReviewsPending function
	 */

	function countReviewsPending()
	{
		// return the number of pending reviews
		$this->db->where('approved', '0');
		return $this->db->count_all_results('review');
	}

	/*
	 * reviewApproval function
	 */

	function reviewApproval($review_id, $value)
	{
		// approve or unapprove the review
		$data = array('approved' => $value);
		$this->db->where('id', $review_id);
		$this->db->update('review', $data);
	}

}

/* End of file review_model.php */
/* Location: ./application/models/review_model.php */