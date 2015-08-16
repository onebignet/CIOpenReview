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
 * Category model class
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            model
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Category_model extends CI_Model
{

	/*
	 * Category model class constructor
	 */

	function category_model()
	{
		parent::__construct();
		$this->load->database();
	}

	/*
	 * addCategory function
	 */

	function add_category($name)
	{
		// add the category
		$seo_name = url_title(trim($name), '-', TRUE);
		$data = array(
			'name' => $name,
			'seo_name' => $seo_name
		);
		$this->db->insert('category', $data);
	}

	/*
	 * updateCategory function
	 */

	function update_category($id, $name)
	{
		// update the category
		$seo_name = url_title(trim($name), '-', TRUE);
		$data = array(
			'name' => $name,
			'seo_name' => $seo_name
		);
		$this->db->where('id', $id);
		$this->db->update('category', $data);
	}

	/*
	 * deleteCategory function
	 */

	function delete_category($id)
	{
		// delete the category
		$this->db->where('id', $id);
		$this->db->delete('category');
	}

	/*
	 * getAllCategories function
	 */

	function get_all_categories($limit = 0, $offset = 0, $return_array = 0)
	{
		// return all categories
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		$this->db->order_by('name');
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get('category');
		// return the categories
		if ($query->num_rows() > 0) {
			if ($return_array == 1) {
				return $query->result_array();
			} else {
				return $query->result();
			}
		}
		// no result
		return FALSE;
	}

	/*
	 * getCategoryById function
	 */

	function get_category_by_id($id)
	{
		// return the category
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('category');
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		// no result
		return FALSE;
	}

	/*
	 * getCategoriesDropDown function
	 */

	function get_categories_drop_down($id = 0)
	{
		// get data for categories drop down list
		$this->db->select('id,name');
		$this->db->order_by('name');
		if ($id > 0) {
			$this->db->where('id !=', $id);
		}
		$query = $this->db->get('category');
		if ($query->num_rows() > 0) {
			$categories[0] = '--------';
			foreach ($query->result() as $category_row) {
				$categories[$category_row->id] = $category_row->name;
			}
			// return the categories list
			return $categories;
		}
		// no results
		return FALSE;
	}

	/*
	 * countCategories function
	 */

	function count_categories()
	{
		// return total number of all categories
		return $this->db->count_all_results('category');
	}

	/*
	 * getNameFromSeoName function
	 */

	function get_name_from_seo_name($category_seo_name)
	{
		// get the category name based on the seo name
		$this->db->where('seo_name', $category_seo_name);
		$query = $this->db->get('category');
		// return the category
		if ($query->num_rows() > 0) {
			$result = $query->row();
			$category_name = $result->name;
			return $category_name;
		}
		// no result
		return FALSE;
	}

}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */