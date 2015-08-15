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
 * Feature management controller class
 *
 * Allows manager to add, edit or delete a feature
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Feature extends CI_Controller
{

	/*
	 * Feature controller class constructor
	 */

	function Feature()
	{
		parent::__construct();
		$this->load->model('Feature_model');
		$this->load->model('Review_feature_model');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * add function
	 *
	 * display 'feature/add' view, validate form data and add new feature to the database
	 */

	function add()
	{
		debug('manager/feature page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// create '$feature' variable for use in the view
		$feature->name = '';
		$data['feature'] = $feature;
		// check form data was submitted
		if ($this->input->post('feature_submit')) {
			debug('form submitted');
			// set up form validation config
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_feature_form_validation_name'),
					'rules' => 'trim|required|min_length[2]|max_length[128]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				debug('loading "manager/feature/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/feature/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('form validation successful');
				// validation successful
				// prepare data for adding to database
				$name = $this->input->post('name');
				// add the feature
				debug('add the feature');
				$this->Feature_model->addFeature($name);
				$data['message'] = lang('manager_feature_add_success');
				// clear form validation data
				$this->form_validation->clear_fields();
				// reload the form
				debug('loading "manager/feature/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/feature/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so just show the form
			debug('form not submitted - loading "manager/feature/add" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/feature/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'feature/edit' view, validate form data and modify category
	 */

	function edit($id)
	{
		debug('manager/feature page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load the feature from the database
		$data['feature'] = $this->Feature_model->getFeatureById($id);
		if ($data['feature']) {
			debug('form submitted');
			// check form data was submitted
			if ($this->input->post('feature_submit')) {
				// set up form validation config
				$config = array(
					array(
						'field' => 'name',
						'label' => lang('manager_feature_form_validation_name'),
						'rules' => 'trim|required|min_length[2]|max_length[128]'
					)
				);
				$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
				$this->form_validation->set_rules($config);
				// validate the form data
				if ($this->form_validation->run() === FALSE) {
					debug('form validation failed');
					// validation failed - reload page with error message(s)
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/feature/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					debug('form validation successful');
					// validation successful
					// prepare data for updating the database
					$name = $this->input->post('name');
					// update the feature
					debug('update the feature');
					$this->Feature_model->updateFeature($id, $name);
					// reload the form
					debug('loading "manager/feature/edited" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/feature/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			} else {
				// form not submitted so just show the form
				debug('form not submitted - loading "manager/feature/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/feature/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// no feature data so redirect back to features list
			debug('feature not found - redirecting to "manager/features"');
			redirect('/manager/features', 301);
		}
	}

	/*
	 * delete function
	 *
	 * display delete confirmation page
	 */

	function delete($id)
	{
		debug('manager/feature page | delete function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load the feature from the database
		$data['feature'] = $this->Feature_model->getFeatureById($id);
		if ($data['feature']) {
			debug('loaded feature');
			// feature exists... show confirmation page
			debug('loading "manager/feature/delete" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/feature/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// redirect back to features list
			debug('feature not found - redirecting to "manager/features"');
			redirect('/manager/features', '301');
		}
	}

	/*
	 * deleted function
	 *
	 * delete the feature after confirmation
	 */

	function deleted($id)
	{
		debug('manager/feature page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load the feature from the database
		$data['feature'] = $this->Feature_model->getFeatureById($id);
		if ($data['feature']) {
			debug('loaded feature');
			// feature exists... delete all review features that use this feature
			debug('delete review features using this feature');
			$this->Review_feature_model->deleteReviewFeaturesByFeatureId($id);
			// delete the feature
			debug('delete the feature');
			$this->Feature_model->deleteFeature($id);
		}
		// redirect back to features list
		debug('redirect to "manager/features"');
		redirect('/manager/features', '301');
	}

}

/* End of file feature.php */
/* Location: ./application/controllers/manager/feature.php */