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
 * Rating management controller class
 *
 * Allows manager to add, edit or delete a rating
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Rating extends CI_Controller
{

	/*
	 * Rating controller class constructor
	 */

	function rating()
	{
		parent::__construct();
		$this->load->model('Rating_model');
		$this->load->model('Review_rating_model');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * add function
	 *
	 * display 'rating/add' view, validate form data and add new rating to the database
	 */

	function add()
	{
		debug('manager/rating page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// create '$rating' variable for use in the view
		$rating->name = '';
		$data['rating'] = $rating;
		// check form data was submitted
		if ($this->input->post('rating_submit')) {
			debug('form submitted');
			// set up form validation config
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_rating_form_validation_name'),
					'rules' => 'trim|required|min_length[2]|max_length[128]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['message'] = lang('manager_rating_form_fail');
				debug('loading "manager/rating/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/rating/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('form validation successful');
				// validation successful
				// prepare data for adding to database
				$name = $this->input->post('name');
				// add the rating
				debug('add the rating');
				$this->Rating_model->add_rating($name);
				$data['message'] = lang('manager_rating_add_success');
				// clear form validation data
				$this->form_validation->clear_fields();
				// reload the form
				debug('loading "manager/rating/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/rating/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so just show the form
			debug('form not submitted - loading "manager/rating/add" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/rating/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'rating/edit' view, validate form data and modify rating
	 */

	function edit($id)
	{
		debug('manager/rating page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the rating from the database
		$data['rating'] = $this->Rating_model->get_rating_by_id($id);
		if ($data['rating']) {
			debug('form submitted');
			// check form data was submitted
			if ($this->input->post('rating_submit')) {
				// set up form validation config
				$config = array(
					array(
						'field' => 'name',
						'label' => lang('manager_rating_form_validation_name'),
						'rules' => 'trim|required|min_length[2]|max_length[128]'
					)
				);
				$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
				$this->form_validation->set_rules($config);
				// validate the form data
				if ($this->form_validation->run() === FALSE) {
					debug('form validation failed');
					// validation failed - reload page with error message(s)
					$data['message'] = lang('manager_rating_form_fail');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/rating/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					debug('form validation successful');
					// validation successful
					// prepare data for updating the database
					$name = $this->input->post('name');
					// update the rating
					debug('update the rating');
					$this->Rating_model->update_rating($id, $name);
					$data['message'] = lang('manager_rating_edit_success');
					// reload the form
					debug('loading "manager/rating/edited" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/rating/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			} else {
				// form not submitted so load the data and show the form
				debug('form not submitted - loading "manager/rating/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/rating/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// no rating data so redirect back to ratings list
			debug('rating not found - redirecting to "manager/ratings"');
			redirect('/manager/ratings', 301);
		}
	}

	/*
	 * delete function
	 *
	 * display delete confirmation page
	 */

	function delete($id)
	{
		debug('manager/rating page | delete function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the rating from the database
		$data['rating'] = $this->Rating_model->get_rating_by_id($id);
		if ($data['rating']) {
			debug('loaded rating');
			// rating exists... show confirmation page
			debug('loading "manager/rating/delete" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/rating/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// redirect back to ratings list
			debug('rating not found - redirecting to "manager/ratings"');
			redirect('/manager/ratings', '301');
		}
	}

	/*
	 * deleted function
	 *
	 * delete the rating after confirmation
	 */

	function deleted($id)
	{
		debug('manager/rating page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the rating from the database
		$data['rating'] = $this->Rating_model->get_rating_by_id($id);
		if ($data['rating']) {
			debug('loaded rating');
			// rating exists... delete all review ratings that use this rating
			debug('delete review ratings using this rating');
			$this->Review_rating_model->delete_review_ratings_by_rating_id($id);
			// delete the rating
			debug('delete the rating');
			$this->Rating_model->delete_rating($id);
		}
		// redirect back to ratings list
		debug('redirect to "manager/ratings"');
		redirect('/manager/ratings', '301');
	}

}

/* End of file rating.php */
/* Location: ./application/controllers/manager/rating.php */