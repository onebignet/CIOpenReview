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
 * Ad model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Ad_model extends CI_Model
{

	/*
	 * Ad model class constructor
	 */

	function ad_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addAd function
	 */

	function add_ad($name, $local_image_name, $remote_image_url, $image_height, $image_width, $text, $link, $visible_in_sidebar, $visible_in_lists, $visible_on_review_page)
	{
		// add the ad
		$seo_name = url_title(trim($name), '-', TRUE);
		$data = array(
			'name' => $name,
			'local_image_name' => $local_image_name,
			'remote_image_url' => $remote_image_url,
			'image_height' => $image_height,
			'image_width' => $image_width,
			'text' => $text,
			'link' => $link,
			'visible_in_sidebar' => $visible_in_sidebar,
			'visible_in_lists' => $visible_in_lists,
			'visible_on_review_page' => $visible_on_review_page
		);
		$this->db->insert('ad', $data);
	}

	/*
	 * updateAd function
	 */

	function update_ad($id, $name, $local_image_name, $remote_image_url, $image_height, $image_width, $text, $link, $visible_in_sidebar, $visible_in_lists, $visible_on_review_page)
	{
		// update the ad
		$seo_name = url_title(trim($name), '-', TRUE);
		$data = array(
			'name' => $name,
			'local_image_name' => $local_image_name,
			'remote_image_url' => $remote_image_url,
			'image_height' => $image_height,
			'image_width' => $image_width,
			'text' => $text,
			'link' => $link,
			'visible_in_sidebar' => $visible_in_sidebar,
			'visible_in_lists' => $visible_in_lists,
			'visible_on_review_page' => $visible_on_review_page
		);
		$this->db->where('id', $id);
		$this->db->update('ad', $data);
	}

	/*
	 * deleteAd function
	 */

	function delete_ad($id)
	{
		// delete the ad
		$this->db->where('id', $id);
		$this->db->delete('ad');
	}

	/*
	 * getManagerAds function
	 */

	function get_manager_ads($limit = 0, $offset = 0)
	{
		// get all ads
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		$this->db->order_by('id', 'desc');
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get('ad');
		// return the ads
		if ($query->num_rows() > 0) {
			$images = $this->initialize_images($query->result());
			return $images;
		}
		// no results
		return FALSE;
	}

	/*
	 * getAds function
	 */

	function get_ads($limit = 0, $type = 0)
	{//$type can be 0 - all, 1 - list, 2 - review page, 3 - sidebar
		// return all ads of specified type
		switch ($type) {
			case 1:
				$this->db->where('visible_in_lists', '1');
				break;
			case 2:
				$this->db->where('visible_on_review_page', '1');
				break;
			case 3:
				$this->db->where('visible_in_sidebar', '1');
				break;
		}
		// randomize the order
		$this->db->order_by('id', 'random');
		if ($limit > 0) {
			$this->db->limit($limit, 0);
		}
		$query = $this->db->get('ad');
		// return the ads
		if ($query->num_rows() > 0) {
			$images = $this->initialize_images($query->result());
			return $images;
		}
		// no results
		return FALSE;
	}

	/*
	 * initialize_images function
	 */

	function initialize_images($images)
	{
		// set image paths for array of ads
		foreach ($images as $ad) {
			if (trim($ad->local_image_name !== '')) {
				$ad->image = '<img src="' . base_url() . 'uploads/ads/images/' . $ad->local_image_name . '" width="' . (($ad->image_width > 0) ? $ad->image_width : 100) . '" height="' . (($ad->image_height > 0) ? $ad->image_height : 80) . '"/>';
			} else {
				if (trim($ad->remote_image_url !== '')) {
					$ad->image = '<img src="' . $ad->remote_image_url . '" width="' . (($ad->image_width > 0) ? $ad->image_width : 100) . '" height="' . (($ad->image_height > 0) ? $ad->image_height : 80) . '"/>';
				} else {
					$ad->image = '';
				}
			}
		}
		// return the array
		return $images;
	}

	/*
	 * getAdById function
	 */

	function get_ad_by_id($id)
	{
		// return the ad
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('ad');
		// return the ad
		if ($query->num_rows() > 0) {
			$images = $this->initialize_images($query->result());
			return $images[0];
		}
		// no result
		return FALSE;
	}

	/*
	 * countAds function
	 */

	function count_ads()
	{
		// return total number of all ads
		return $this->db->count_all_results('ad');
	}

}

/* End of file ad_model.php */
/* Location: ./application/models/ad_model.php */