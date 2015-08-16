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
 * Review_rating management controller class
 *
 * Allows manager to add, edit or delete a review rating
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Review_rating extends CI_Controller
{

	/*
	 * Review_rating controller class constructor
	 */

	function review_rating()
	{
		parent::__construct();
		$this->load->model('Review_rating_model');
		$this->load->model('Review_model');
		$this->load->model('Rating_model');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * add function
	 *
	 * display 'review_rating/add' view, validate form data and add new review_rating to the database
	 */

	function add($review_id)
	{
		debug('manager/review_rating page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load data for use in the view
		$data['ratings'] = $this->Rating_model->get_ratings_drop_down();
		$data['selected_rating'] = '';
		$data['selected_value'] = '';
		if ($data['ratings']) {
			debug('ratings loaded');
			// load data for ratings drop down list in view
			$data['values'] = array('--------', '0.5', '1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5');
			$data['selected_rating'] = '';
			$data[] = '';
			if ($review_id) {
				// load the review from the database
				$review = $this->Review_model->get_review_by_id($review_id);
				if ($review) {
					debug('review loaded');
					// review exists
					// check form data was submitted
					if ($this->input->post('review_rating_submit')) {
						// set up form validation config
						debug('form was submitted');
						$config = array(
							array(
								'field' => 'rating_id',
								'label' => lang('manager_review_ratings_form_validation_rating_id'),
								'rules' => 'callback__more_than_zero'
							),
							array(
								'field' => 'value_id',
								'label' => lang('manager_review_ratings_form_validation_value'),
								'rules' => 'callback__more_than_zero2'
							),
						);
						$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
						$this->form_validation->set_rules($config);
						$this->form_validation->set_message('_more_than_zero', lang('manager_review_form_validate_rating'));
						$this->form_validation->set_message('more_than_zero2', lang('manager_review_form_validate_value'));
						// validate the form data
						if ($this->form_validation->run() === FALSE) {
							debug('form validation failed');
							// validation failed - reload page with error message(s)
							$data['review'] = $review;
							$data['message'] = lang('manager_review_rating_form_fail');
							debug('loading "review_rating/add" view');
							$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_rating/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
							$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
						} else {
							debug('validation successful');
							// validation successful
							// prepare data for updating the database
							$rating_id = $this->input->post('rating_id');
							$value_id = $this->input->post('value_id');
							// add the review rating for the review
							debug('add the review rating');
							$add_review_rating = $this->Review_rating_model->add_review_rating($review_id, $rating_id, $value_id);
							$data['review'] = $review;
							$data['message'] = lang('manager_review_rating_add_success');
							// clear form validation data
							$this->form_validation->clear_fields();
							// reload the form
							debug('loading "manager/review_rating/add" view');
							$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_rating/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
							$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
						}
					} else {
						// form not submitted so just show the form
						$data['review'] = $review;
						debug('form not submitted - loading "manager/review_rating/add" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_rating/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					}
				}
			} else {
				// no review id provided so redirect to manager home page
				debug('no review id provided - redirecting to "manager/home"');
				redirect('/manager/home', '301');
			}
		} else {
			// no rating data to choose from so show 'review_rating/no_ratings' page
			$review = $this->Review_model->get_review_by_id($review_id);
			$data['review'] = $review;
			debug('ratings not found - loading "manager/review_rating/no_ratings" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_rating/no_ratings', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'review_rating/edit' view, validate form data and modify review_rating
	 */

	function edit($id)
	{
		debug('manager/review_rating page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load data for ratings drop down list in view
		$data['ratings'] = $this->Rating_model->get_ratings_drop_down();
		$data['values'] = array('--------', '1', '2', '3', '4', '5');
		$data['review'] = $this->Review_model->get_review_for_review_rating_id($id);
		// check form data was submitted
		if ($this->input->post('review_rating_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'rating_id',
					'label' => lang('manager_review_ratings_form_validation_rating_id'),
					'rules' => 'callback__more_than_zero'
				),
				array(
					'field' => 'value_id',
					'label' => lang('manager_review_ratings_form_validation_value'),
					'rules' => 'callback__more_than_zero2'
				),
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			$this->form_validation->set_message('_more_than_zero', lang('manager_review_form_validate_rating'));
			$this->form_validation->set_message('_more_than_zero2', lang('manager_review_form_validate_value'));
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['review_rating'] = $this->Review_rating_model->get_review_rating_by_id($id);
				$data['selected_rating'] = $data['review_rating']->rating_id;
				$data['selected_value'] = $data['review_rating']->value;
				$data['message'] = lang('manager_review_rating_form_fail');
				debug('loading "review_rating/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_rating/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// prepare data for updating the database
				$value = $this->input->post('value_id');
				$rating_id = $this->input->post('rating_id');
				// update the review rating for the review
				debug('update the review rating');
				$update_review_rating = $this->Review_rating_model->update_review_rating($id, $rating_id, $value);
				// reload the form
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_rating/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so load the data and show the form
			debug('form not submitted');
			$data['review_rating'] = $this->Review_rating_model->get_review_rating_by_id($id);
			if ($data['review_rating']) {
				$data['selected_rating'] = $data['review_rating']->rating_id;
				$data['selected_value'] = $data['review_rating']->value;
				debug('loading "manager/review_rating/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_rating/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				// no review rating data so redirect back to ratings list
				debug('review rating not found - redirecting to "manager/reviews"');
				redirect('/manager/reviews', '301');
			}
		}
	}

	/*
	 * delete function
	 *
	 * display delete confirmation page
	 */

	function delete($id)
	{
		debug('manager/review_rating page | delete function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the review rating from the database
		$data['review_rating'] = $this->Review_rating_model->get_review_rating_by_id($id);
		if ($data['review_rating']) {
			// review rating exists... show confirmation page
			$data['review'] = $this->Review_model->get_review_for_review_rating_id($id);
			debug('loading "review_rating/delete" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_rating/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// review rating not found, redirect back to reviews list
			debug('review rating not found - review rating not found - redirect to manager/reviews');
			redirect('/manager/reviews');
		}
	}

	/*
	 * deleted function
	 *
	 * delete the review rating after confirmation
	 */

	function deleted($id)
	{
		debug('manager/review_rating page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the review rating from the database
		$data['review_rating'] = $this->Review_rating_model->get_review_rating_by_id($id);
		if ($data['review_rating']) {
			// review rating exists... delete all review ratings that use this rating
			$data['review'] = $this->Review_model->get_review_for_review_rating_id($id);
			// delete the review rating
			debug('delete the review rating');
			$this->Review_rating_model->delete_review_rating_by_id($id);
			// redirect back to review ratings list for this review
			redirect('/manager/review_ratings/show/' . $data['review']->id);
		} else {
			// no review rating data so redirect back to reviews list
			debug('redirect to manager/reviews');
			redirect('/manager/reviews');
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

	/*
	 * _more_than_zero_2 function
	 *
	 * test a value and return false if < 1
	 *
	 * (duplicated as used in 2 separate validation rules each with their own error message)
	 */

	function _more_than_zero2($str)
	{
		if (!is_numeric($str)) {
			return FALSE;
		}
		return $str > 0;
	}

}

/* End of file review_rating.php */
/* Location: ./application/controllers/manager/review_rating.php */