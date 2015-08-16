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
 * Theme_settings management controller class
 *
 * Allows manager to edit theme settings
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Theme_settings extends CI_Controller
{

	/*
	 * Theme_settings controller class constructor
	 */

	function theme_settings()
	{
		parent::__construct();
		$this->load->model('Setting_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('image_lib');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * edit function
	 *
	 * display 'theme_settings/edit' view, validate form data and update the database
	 */

	function edit()
	{
		debug('manager/theme_settings page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// prepare data for the view
		$logo_path = './uploads/site_logo/';
		$data['max_upload_width'] = $this->setting['max_upload_width'];
		$data['max_upload_height'] = $this->setting['max_upload_height'];
		$data['max_upload_filesize'] = $this->setting['max_upload_filesize'];
		$data['review_thumb_max_width'] = $this->setting['review_thumb_max_width'];
		$data['review_thumb_max_height'] = $this->setting['review_thumb_max_height'];
		$data['search_thumb_max_width'] = $this->setting['search_thumb_max_width'];
		$data['search_thumb_max_height'] = $this->setting['search_thumb_max_height'];
		// load available site themes into an array
		$data['themes'] = glob('./themes/site/*', GLOB_ONLYDIR);
		// get name of current site theme
		$current_theme = ucwords(strtolower(str_replace("_", " ", $this->Setting_model->get_setting_by_name('current_theme'))));
		// update the data to show just the theme names and not the paths
		foreach ($data['themes'] as $theme => $key) {
			$data['themes'][$theme] = ucwords(strtolower(str_replace("_", " ", str_ireplace('./themes/site/', '', $data['themes'][$theme]))));
			if ($data['themes'][$theme] == $current_theme) {
				// when the current theme is found, store the name
				$data['selected_theme'] = $theme;
			}
		}
		// load available manager themes into an array
		$data['manager_themes'] = glob('./themes/manager/*', GLOB_ONLYDIR);
		// get name of current manager theme
		$current_manager_theme = ucwords(strtolower(str_replace("_", " ", $this->Setting_model->get_setting_by_name('current_manager_theme'))));
		// update the data to show just the theme names and not the paths
		foreach ($data['manager_themes'] as $manager_theme => $key) {
			$data['manager_themes'][$manager_theme] = ucwords(strtolower(str_replace("_", " ", str_ireplace('./themes/manager/', '', $data['manager_themes'][$manager_theme]))));
			if ($data['manager_themes'][$manager_theme] == $current_manager_theme) {
				// when the current theme is found, store the name
				$data['selected_manager_theme'] = $manager_theme;
			}
		}
		// if there is a site logo, get the path to the image file
		if ($this->setting['site_logo'] !== '') {
			$data['current_logo'] = base_url() . 'uploads/site_logo/' . $this->setting['site_logo_name'] . '_thumb.' . $this->setting['site_logo_extension'];
		} else {
			// no logo so leave it blank
			$data['current_logo'] = '';
		}
		debug('loaded all settings data');
		// check form was submitted
		if ($this->input->post('settings_submit')) {
			debug('form was submitted');
			// set up form validation config
			$config = array(
				array(
					'field' => 'max_upload_width',
					'label' => lang('manager_theme_settings_form_validation_max_upload_width'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[4]|numeric'
				),
				array(
					'field' => 'max_upload_height',
					'label' => lang('manager_theme_settings_form_validation_max_upload_height'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[4]|numeric'
				),
				array(
					'field' => 'max_upload_filesize',
					'label' => lang('manager_theme_settings_form_validation_max_upload_filesize'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[7]|numeric'
				),
				array(
					'field' => 'review_thumb_max_width',
					'label' => lang('manager_theme_settings_form_validation_review_thumb_max_width'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[4]|numeric'
				),
				array(
					'field' => 'review_thumb_max_height',
					'label' => lang('manager_theme_settings_form_validation_review_thumb_max_height'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[4]|numeric'
				),
				array(
					'field' => 'search_thumb_max_width',
					'label' => lang('manager_theme_settings_form_validation_search_thumb_max_width'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[4]|numeric'
				),
				array(
					'field' => 'search_thumb_max_height',
					'label' => lang('manager_theme_settings_form_validation_search_thumb_max_height'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[4]|numeric'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			$this->form_validation->set_message('_more_than_zero', lang('manager_more_than_zero'));
			// initialize some variables for use later in uploading
			$data['upload_error'] = '';
			$file_error = 0;
			$orig_file_name = '';
			// validate the form data
			debug('validate form data');
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['message'] = lang('manager_theme_settings_edited_message_fail');
				debug('loading "manager/theme_settings/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/theme_settings/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// check if a file (logo) uploaded without php errors
				if ($iles['userfile']['error'] == UPLOAD_ERR_OK) {
					debug('user tried to upload a file');
					// set up config for logo upload
					$config['upload_path'] = $logo_path;
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size'] = $this->setting['max_upload_filesize'];
					$config['max_width'] = $this->setting['max_upload_width'];
					$config['max_height'] = $this->setting['max_upload_height'];
					$config['remove_spaces'] = TRUE;
					// store the file name and extension
					$extension = pathinfo($iles['userfile']['name'], PATHINFO_EXTENSION);
					$file_name = str_replace(' ', '_', $iles['userfile']['name']);
					$file_name = substr($file_name, 0, strlen($file_name) - strlen($extension) - 1);
					$file_name = str_replace('.', '_', $iles['userfile']['name']);
					// create a random number to append to the file name
					$random_number = rand(0, 99999999);
					$config['file_name'] = $file_name . '_' . $random_number . '.' . $extension;
					// initialize the library with config data
					$this->upload->initialize($config);
					debug('uploading the file');
					// attempt to upload the file
					if (!$this->upload->do_upload()) {
						$file_data = array('upload_data' => $this->upload->data());
						$mimetype = $file_data['upload_data']['file_type'];
						// if there was an error uploading, set the error message
						debug('there was a file uploading error... ' . $this->upload->display_errors());
						$data['upload_error'] = lang('manager_theme_settings_form_upload_error') . $this->upload->display_errors() . ' Your file type is <font color="#0000FF">' . $mimetype . "</font>";
						$file_error = 1;
					} else {
						// upload successful
						debug('upload was successful');
						$data['upload_data'] = array('upload_data' => $this->upload->data());
						// create a thumbnail image for displaying on theme settings page
						// this also includes a check that a valid image file was uploaded
						// set up config for image resizing
						$orig_file_name = $config['file_name'];
						$config['image_library'] = 'gd2';
						$config['source_image'] = $logo_path . $orig_file_name;
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = TRUE;
						$config['width'] = 500;
						$config['height'] = 200;
						// initialize image library with config data
						$this->image_lib->initialize($config);
						// attempt to resize image (if a non-image file was uploaded this will fail and we will delete the original file)
						debug('attempting to resize image');
						if (!$this->image_lib->resize()) {
							// resize failed... delete the original file
							debug('resize failed - deleting original file - upload failed');
							@unlink($logo_path . $orig_file_name);
							// set the error message
							$data['upload_error'] = lang('manager_theme_settings_form_upload_error') . lang('manager_theme_settings_form_upload_not_image');
							$file_error = 1;
						}
					}
				} else {
					// file did not upload, set the error message
					debug('there was a file uploading error... ' . $this->upload->display_errors());
					$file_error = 1;
					$data['upload_error'] = lang('manager_theme_settings_form_upload_error') . $this->upload->display_errors();
				}
				debug('update settings');
				// update settings with form values
				$this->Setting_model->update_setting('max_upload_width', $this->input->post('max_upload_width'));
				$this->Setting_model->update_setting('max_upload_height', $this->input->post('max_upload_height'));
				$this->Setting_model->update_setting('max_upload_filesize', $this->input->post('max_upload_filesize'));
				$this->Setting_model->update_setting('review_thumb_max_width', $this->input->post('review_thumb_max_width'));
				$this->Setting_model->update_setting('review_thumb_max_height', $this->input->post('review_thumb_max_height'));
				$this->Setting_model->update_setting('search_thumb_max_width', $this->input->post('search_thumb_max_width'));
				$this->Setting_model->update_setting('search_thumb_max_height', $this->input->post('search_thumb_max_height'));
				$this->Setting_model->update_setting('current_theme', strtolower(str_replace(" ", "_", $data['themes'][$this->input->post('theme')])));
				$this->Setting_model->update_setting('current_manager_theme', strtolower(str_replace(" ", "_", $data['manager_themes'][$this->input->post('manager_theme')])));
				if ($file_error == 0) {
					debug('file upload completed successfully');
					// if logo file uploaded correctly, update the image file name setting
					$this->Setting_model->update_setting('site_logo_name', $file_name . '_' . $random_number);
					$this->Setting_model->update_setting('site_logo_extension', $extension);
				}
				debug('reload the settings');
				// update setting array with updated values
				$this->setting = $this->Setting_model->get_every_setting();
				// prepare data to display in the view
				$data['max_upload_width'] = $this->setting['max_upload_width'];
				$data['max_upload_height'] = $this->setting['max_upload_height'];
				$data['max_upload_filesize'] = $this->setting['max_upload_filesize'];
				$data['review_thumb_max_width'] = $this->setting['review_thumb_max_width'];
				$data['review_thumb_max_height'] = $this->setting['review_thumb_max_height'];
				$data['search_thumb_max_width'] = $this->setting['search_thumb_max_width'];
				$data['search_thumb_max_height'] = $this->setting['search_thumb_max_height'];
				// load available site themes into an array
				$data['themes'] = glob('./themes/site/*', GLOB_ONLYDIR);
				// get name of current site theme
				$current_theme = ucwords(strtolower(str_replace("_", " ", $this->Setting_model->get_setting_by_name('current_theme'))));
				// update the data to show just the theme names and not the paths
				foreach ($data['themes'] as $theme => $key) {
					$data['themes'][$theme] = ucwords(strtolower(str_replace("_", " ", str_ireplace('./themes/site/', '', $data['themes'][$theme]))));
					if ($data['themes'][$theme] == $current_theme) {
						// when the current theme is found, store the name
						$data['selected_theme'] = $theme;
					}
				}
				// load available manager themes into an array
				$data['manager_themes'] = glob('./themes/manager/*', GLOB_ONLYDIR);
				// get name of current manager theme
				$current_manager_theme = ucwords(strtolower(str_replace("_", " ", $this->Setting_model->get_setting_by_name('current_manager_theme'))));
				// update the data to show just the theme names and not the paths
				foreach ($data['manager_themes'] as $manager_theme => $key) {
					$data['manager_themes'][$manager_theme] = ucwords(strtolower(str_replace("_", " ", str_ireplace('./themes/manager/', '', $data['manager_themes'][$manager_theme]))));
					if ($data['manager_themes'][$manager_theme] == $current_manager_theme) {
						// when the current theme is found, store the name
						$data['selected_manager_theme'] = $manager_theme;
					}
				}
				// if there is a logo, set the path to the thumbnail image
				if ($this->setting['site_logo'] !== '') {
					$data['current_logo'] = base_url() . 'uploads/site_logo/' . $this->setting['site_logo_name'] . '_thumb.' . $this->setting['site_logo_extension'];
				} else {
					$data['current_logo'] = '';
				}
				// reload the form
				$data['message'] = lang('manager_theme_settings_edited_message');
				debug('loading "manager/theme_settings/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/theme_settings/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so just show the form
			$data['upload_error'] = '';
			debug('form not submitted - loading "manager/theme_settings/edit" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/theme_settings/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
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

/* End of file theme_settings.php */
/* Location: ./application/controllers/manager/theme_settings.php */
