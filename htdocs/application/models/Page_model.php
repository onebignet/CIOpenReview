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
 * Page model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Page_model extends CI_Model
{

	/*
	 * Page model class constructor
	 */

	function Page_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addPage function
	 */

	function addPage($name, $content, $meta_keywords, $meta_description)
	{
		// check the page name is unique and append a number if it is not
		$name_add = 2;
		$name = trim($name);
		$seo_name = url_title($name, '_', TRUE);
		$safe_seo_name = $seo_name;
		while ($this->_checkSeoNameExists($safe_seo_name)) {
			// keep trying numbers until the title is unique
			$safe_seo_name = $seo_name . '-' . $name_add;
			$name_add++;
		}
		$data = array(
			'name' => $name,
			'seo_name' => $safe_seo_name,
			'content' => $content,
			'meta_keywords' => $meta_keywords,
			'meta_description' => $meta_description
		);
		// add the page
		$this->db->insert('page', $data);
		$page = $this->getPageBySeoName($safe_seo_name);
		$id = $page->id;
		// return the page id
		return $id;
	}

	/*
	 * updatePage function
	 */

	function updatePage($id, $name, $content, $meta_keywords, $meta_description)
	{
		// check the page name is unique and append a number if it is not
		$name_add = 2;
		$name = trim($name);
		$seo_name = url_title($name, '_', TRUE);
		$safe_seo_name = $seo_name;
		while ($this->_checkSeoNameExists($safe_seo_name, $id)) {
			// keep trying numbers until the title is unique
			$safe_seo_name = $seo_name . '-' . $name_add;
			$name_add++;
		}
		$data = array(
			'name' => $name,
			'seo_name' => $safe_seo_name,
			'content' => $content,
			'meta_keywords' => $meta_keywords,
			'meta_description' => $meta_description
		);
		// update the page
		$this->db->where('id', $id);
		$this->db->update('page', $data);
	}

	/*
	 * deletePage function
	 */

	function deletePage($id)
	{
		// delete the page
		$this->db->where('id', $id);
		$this->db->delete('page');
	}

	/*
	 * _checkSeoNameExists function
	 */

	function _checkSeoNameExists($seo_name, $id = -1)
	{
		// check the page name is not already being used
		$this->db->select('seo_name');
		$this->db->where('seo_name', $seo_name);
		// optionally ignore a particular page
		// this is used when editing so the current name of a page can be ignored
		if ($id != -1) {
			$this->db->where('id !=', $id);
		}
		// return TRUE if the title exists, FALSE if not
		$query = $this->db->get('page');
		if ($query->num_rows() > 0) {
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * countPages function
	 */

	function countPages()
	{
		// return the total number of pages
		return $this->db->count_all_results('page');
	}

	/*
	 * getPageBySeoName function
	 */

	function getPageBySeoName($param)
	{
		// return the page
		$this->db->where('seo_name', $param);
		$this->db->limit(1);
		$query = $this->db->get('page');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		// no result
		return FALSE;
	}

	/*
	 * getPageById function
	 */

	function getPageById($id)
	{
		// return the page
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('page');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result[0];
		}
		// no result
		return FALSE;
	}

	/*
	 * getAllPages function
	 */

	function getAllPages($limit, $offset = 0)
	{
		// return all pages
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('page');
		// return the pages
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no results
		return FALSE;
	}

}

/* End of file page_model.php */
/* Location: ./application/models/page_model.php */