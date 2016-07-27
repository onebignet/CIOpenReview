<?php

/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript
 *
 * @package        CIOpenReview
 * @subpackage          manager
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
 * Category management controller class
 *
 * Allows manager to add, edit or delete a category
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Category extends CI_Controller
{

	/*
	 * Category controller class constructor
	 */

	function category()
	{
		parent::__construct();
		$this->load->model('Category_model');
		$this->load->model('Review_model');
		$this->load->model('Feature_model');
		$this->load->model('Category_default_feature_model');
		$this->load->model('Rating_model');
		$this->load->model('Category_default_rating_model');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * add function
	 *
	 * display 'category/add' view, validate form data and add new category to the database
	 */

	function add()
	{
		debug('manager/category page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// create '$category' variable for use in the view
		$category->name = '';
		$data['category'] = $category;
		// check form data was submitted
		if ($this->input->post('category_submit')) {
			debug('form was submitted');
			// set up form validation config
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_category_form_validation_name'),
					'rules' => 'trim|required|min_length[2]|max_length[512]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				debug('loading "manager/category/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// prepare data for adding to database
				$name = $this->input->post('name');
				// add the category
				debug('add the category');
				$seo_name = $this->Category_model->add_category($name);
				$data['message'] = lang('manager_category_add_success');
				// clear form validation data
				$this->form_validation->clear_fields();
				// reload the form
				debug('loading "manager/category/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so just show the form
			debug('form not submitted - loading "manager/category/add" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'category/edit' view, validate form data and modify category
	 */

	function edit($id)
	{
		debug('manager/category page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// create '$category' variable for use in the view
		$data['category'] = $this->Category_model->get_category_by_id($id);
		if ($data['category']) {
			$data['features'] = $this->Feature_model->get_features_drop_down();
			$data['ratings'] = $this->Rating_model->get_ratings_drop_down();
			$data['categorydefaultfeatures'] = $this->Category_default_feature_model->get_default_features_by_category_id($id);
			$data['categorydefaultratings'] = $this->Category_default_rating_model->get_default_ratings_by_category_id($id);
			// check form data was submitted
			if ($this->input->post('category_submit')) {
				debug('category form was submitted');
				// set up form validation config
				$config = array(
					array(
						'field' => 'name',
						'label' => lang('manager_category_form_validation_name'),
						'rules' => 'trim|required|min_length[2]|max_length[512]'
					)
				);
				$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
				$this->form_validation->set_rules($config);
				// validate the form data
				if ($this->form_validation->run() === FALSE) {
					debug('form validation failed');
					// validation failed - reload page with error message(s)
					debug('loading "manager/category/add" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					debug('validation successful');
					// validation successful
					// prepare data for updating the database
					$name = $this->input->post('name');
					// update the category
					debug('update the category');
					$seo_name = $this->Category_model->update_category($id, $name);
					debug('loading "manager/category/edited" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					// display the page again
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			} else {
				if ($this->input->post('category_default_feature_submit')) {
					debug('category default feature form submitted');
					// category default feature form was submitted
					$feature_id = $this->input->post('feature_id');
					//add a default feature for this category
					debug('add default category feature');
					$this->Category_default_feature_model->add_default_feature($feature_id, $id);
					$data['categorydefaultfeatures'] = $this->Category_default_feature_model->get_default_features_by_category_id($id);
					//reload the page
					debug('loading "manager/category/edit" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					if ($this->input->post('category_default_rating_submit')) {
						debug('category default rating form submitted');
						// category default rating form was submitted
						$rating_id = $this->input->post('rating_id');
						//add a default rating for this category
						debug('add default category rating');
						$this->Category_default_rating_model->add_default_rating($rating_id, $id);
						$data['categorydefaultratings'] = $this->Category_default_rating_model->get_default_ratings_by_category_id($id);
						//reload the page
						debug('loading "manager/category/edit" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					} else {
						// form not submitted so just show the form
						debug('no form submitted - loading "manager/category/edit" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					}
				}
			}
		} else {
			// category does not exist so redirect to categories list
			debug('category does not exist - redirecting to "manager/categories"');
			redirect('/manager/categories', 301);
		}
	}

	/*
	 * delete_default_feature function
	 *
	 * delete a default feature and display 'category/edit' view
	 */

	function delete_default_feature($id)
	{
		debug('manager/category page | delete_default_feature function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the default feature from the database
		$default_feature = $this->Category_default_feature_model->get_default_feature_by_id($id);
		if ($default_feature) {
			// default feature exists... find the category for this default feature
			$data['category'] = $this->Category_model->get_category_by_id($default_feature->category_id);
			if ($data['category']) {
				debug('loaded category');
				// got category... delete the default feature
				debug('delete default category');
				$this->Category_default_feature_model->delete_default_feature($id);
				// get data for 'category/edit' view
				$data['features'] = $this->Feature_model->get_features_drop_down();
				$data['categorydefaultfeatures'] = $this->Category_default_feature_model->get_default_features_by_category_id($data['category']->id);
				$data['categorydefaultratings'] = $this->Category_default_rating_model->get_default_ratings_by_category_id($data['category']->id);
				// display the 'category/edit' page
				debug('loading "manager/category/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// default feature does not exist... redirect to categories list
			debug('default feature does not exist - redirecting to "manager/categories"');
			redirect('/manager/categories', 301);
		}
	}

	/*
	 * delete_default_rating function
	 *
	 * delete a default rating and display 'category/edit' view
	 */

	function delete_default_rating($id)
	{
		debug('manager/category page | delete_default_rating function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the default rating from the database
		$default_rating = $this->Category_default_rating_model->get_default_rating_by_id($id);
		if ($default_rating) {
			// default rating exists... find the category for this default rating
			$data['category'] = $this->Category_model->get_category_by_id($default_rating->category_id);
			if ($data['category']) {
				debug('loaded category');
				// got category... delete the default rating
				debug('delete default rating');
				$this->Category_default_rating_model->delete_default_rating($id);
				// get data for 'category/edit' view
				$data['ratings'] = $this->Rating_model->get_ratings_drop_down();
				$data['categorydefaultratings'] = $this->Category_default_rating_model->get_default_ratings_by_category_id($data['category']->id);
				$data['categorydefaultfeatures'] = $this->Category_default_feature_model->get_default_features_by_category_id($data['category']->id);
				// display the 'category/edit' page
				debug('loading "manager/category/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// default rating does not exist... redirect to categories list
			debug('default rating does not exist - redirecting to "manager/categories"');
			redirect('/manager/categories', 301);
		}
	}

	/*
	 * delete function
	 *
	 * delete a category, move reviews to another category and display 'category/deleted' view
	 */

	function delete($id)
	{
		debug('manager/category page | delete function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// check there are 2 or more categories
		if ($this->Category_model->count_categories() > 1) {
			$data['categories'] = $this->Category_model->get_categories_drop_down($id);
			$data['selected'] = (count($data['categories']) < 3) ? end(array_keys($data['categories'])) : 0;
			$data['category'] = $this->Category_model->get_category_by_id($id);
			if ($data['category']) {
				debug('loaded category');
				if ($this->input->post('category_delete_submit')) {
					debug('delete form was submitted');
					// delete form was submitted
					$config = array(
						array(
							'field' => 'category_id',
							'label' => lang('manager_review_form_validation_category'),
							'rules' => 'callback__more_than_zero'
						)
					);
					$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
					$this->form_validation->set_rules($config);
					$this->form_validation->set_message('_more_than_zero', lang('manager_category_form_validate_select_category'));
					// validate form
					if ($this->form_validation->run() === FALSE) {
						debug('form validation failed');
						// validation failed - reload page with error message(s)
						debug('loading "manager/category/delete" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					} else {
						debug('validation successful');
						// validation successful
						$to_id = $this->input->post('category_id');
						// 'move' all reviews with the original category id to the selected new category id
						debug('move reviews to another category');
						$this->Review_model->change_category($id, $to_id);
						// delete the category
						debug('delete the category');
						$this->Category_model->delete_category($id);
						// display the 'category/deleted' page
						debug('category deleted - redirecting to "manager/categories"');
						redirect('/manager/categories', 301);
					}
				} else {
					// form not submitted so just show the form
					debug('form not submitted - loading "manager/category/delete" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			} else {
				// category does not exist... redirect to categories list
				debug('category not found - redirecting to "manager/categories"');
				redirect('/manager/categories', '301');
			}
		} else {
			// there is only one remaining category so this category can not be deleted
			$data[] = '';
			// show the 'category/onlyone' page
			debug('this is the only category so it cannot be deleted');
			debug('loading "manager/category/onlyone" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/category/onlyone', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * _more_than_zero function
	 *
	 * test a value and return false if < 1
	 */

	function _more_than_zero($str)
	{
		if (!is_numeric($str)) {
			return FALSE;
		}
		return $str > 0;
	}

}

/* End of file category.php */
/* Location: ./application/controllers/manager/category.php */