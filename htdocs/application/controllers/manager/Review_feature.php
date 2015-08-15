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
 * Review_feature management controller class
 *
 * Allows manager to add, edit or delete a review feature
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Review_feature extends CI_Controller
{

	/*
	 * Review_feature controller class constructor
	 */

	function Review_feature()
	{
		parent::__construct();
		$this->load->model('Review_feature_model');
		$this->load->model('Review_model');
		$this->load->model('Feature_model');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * add function
	 *
	 * display 'review_feature/add' view, validate form data and add new review_feature to the database
	 */

	function add($review_id)
	{
		debug('manager/review_feature page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load data for use in the view
		$data['features'] = $this->Feature_model->getFeaturesDropDown();
		if ($data['features']) {
			debug('features loaded');
			// load data for features drop down list in view
			$review_feature->value = '';
			$data['review_feature'] = $review_feature;
			$data['selected_feature'] = '';
			if ($review_id) {
				// load the review from the database
				$review = $this->Review_model->getReviewById($review_id);
				if ($review) {
					debug('review loaded');
					// review exists
					// check form data was submitted
					if ($this->input->post('review_feature_submit')) {
						debug('form was submitted');
						// set up form validation config
						$config = array(
							array(
								'field' => 'feature_id',
								'label' => lang('manager_review_features_form_validation_feature_id'),
								'rules' => 'callback__more_than_zero'
							),
							array(
								'field' => 'value',
								'label' => lang('manager_review_features_form_validation_value'),
								'rules' => 'trim|required|min_length[1]|max_length[512]'
							),
						);
						$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
						$this->form_validation->set_rules($config);
						$this->form_validation->set_message('_more_than_zero', lang('manager_review_form_validate_feature'));
						// validate the form data
						if ($this->form_validation->run() === FALSE) {
							debug('form validation failed');
							// validation failed - reload page with error message(s)
							$data['review'] = $review;
							$data['selected_feature'] = $this->input->post('feature_id');
							$data['message'] = lang('manager_review_feature_form_fail');
							debug('loading "review_feature/add" view');
							$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
							$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
						} else {
							debug('validation successful');
							// validation successful
							// prepare data for updating the database
							$feature_id = $this->input->post('feature_id');
							$value = $this->input->post('value');
							// add the review feature for the review
							debug('add the review feature');
							$addReviewFeature = $this->Review_feature_model->addReviewFeature($review_id, $feature_id, $value);
							$data['review'] = $review;
							$data['message'] = lang('manager_review_feature_add_success');
							// clear form validation data
							$this->form_validation->clear_fields();
							// reload the form
							debug('loading "manager/review_feature/add" view');
							$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
							$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
						}
					} else {
						// form not submitted so just show the form
						$data['review'] = $review;
						debug('form not submitted - loading "manager/review_feature/add" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					}
				}
			} else {
				// no review id provided so redirect to manager home page
				debug('no review id provided - redirecting to "manager/home"');
				redirect('/manager/home', '301');
			}
		} else {
			// no feature data to choose from so show 'review_feature/no_features' page
			$review = $this->Review_model->getReviewById($review_id);
			$data['review'] = $review;
			debug('features not found - loading "manager/review_feature/no_features" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/no_features', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'review_feature/edit' view, validate form data and modify review_feature
	 */

	function edit($id)
	{
		debug('manager/review_feature page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load data for features drop down list in view
		$data['features'] = $this->Feature_model->getFeaturesDropDown();
		$data['review'] = $this->Review_model->getReviewForReviewFeatureId($id);
		// check form data was submitted
		if ($this->input->post('review_feature_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'feature_id',
					'label' => lang('manager_review_features_form_validation_feature_id'),
					'rules' => 'callback__more_than_zero'
				),
				array(
					'field' => 'value',
					'label' => lang('manager_review_features_form_validation_value'),
					'rules' => 'trim|required|min_length[1]|max_length[512]'
				),
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			$this->form_validation->set_message('_more_than_zero', lang('manager_review_form_validate_feature'));
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['review_feature'] = $this->Review_feature_model->getReviewFeatureById($id);
				$data['selected_feature'] = $data['review_feature']->feature_id;
				$data['selected_value'] = $data['review_feature']->value;
				$data['message'] = lang('manager_review_feature_form_fail');
				debug('loading "review_feature/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// prepare data for updating the database
				$feature_id = $this->input->post('feature_id');
				$value = $this->input->post('value');
				// update the review feature for the review
				debug('update the review feature');
				$updateReviewFeature = $this->Review_feature_model->updateReviewFeature($id, $feature_id, $value);
				// reload the form
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so load the data and show the form
			debug('form not submitted');
			$data['review_feature'] = $this->Review_feature_model->getReviewFeatureById($id);
			if ($data['review_feature']) {
				$data['selected_feature'] = $data['review_feature']->feature_id;
				$data['selected_value'] = $data['review_feature']->value;
				debug('loading "manager/review_feature/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				// no review feature data so redirect back to reviews list
				debug('review feature not found - redirecting to "manager/reviews"');
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
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load the review feature from the database
		$data['review_feature'] = $this->Review_feature_model->getReviewFeatureById($id);
		if ($data['review_feature']) {
			// review feature exists... show confirmation page
			$data['review'] = $this->Review_model->getReviewForReviewFeatureId($id);
			debug('loading "review_feature/delete" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// review feature not found, redirect back to features list
			debug('review feature not found - review feature not found - redirect to manager/reviews');
			redirect('/manager/reviews');
		}
	}

	/*
	 * deleted function
	 *
	 * delete the review feature after confirmation
	 */

	function deleted($id)
	{
		debug('manager/review_feature page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load the review feature from the database
		$data['review_feature'] = $this->Review_feature_model->getReviewFeatureById($id);
		if ($data['review_feature']) {
			// review feature exists... delete all review features that use this feature
			$data['review'] = $this->Review_model->getReviewForReviewFeatureId($id);
			$this->Review_feature_model->deleteReviewFeatureById($id);
			// delete the review feature
			debug('delete the review feature');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review_feature/deleted', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			// redirect back to review features list for this review
			redirect('/manager/review_features/show/' . $data['review']->id);
		} else {
			// no review feature data so redirect back to reviews list
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

}

/* End of file review_feature.php */
/* Location: ./application/controllers/manager/review_feature.php */
