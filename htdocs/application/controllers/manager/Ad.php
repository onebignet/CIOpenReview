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
 * @link        http://ciopenreview.com
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
 * Ad management controller class
 *
 * Allows manager to add, edit or delete an ad
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Ad extends CI_Controller
{

	/*
	 * Ad controller class constructor
	 */

	function ad()
	{
		parent::__construct();
		$this->load->model('Ad_model');
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('image_lib');
		$data['meta_keywords'] = '';
		$data['$meta_description'] = '';
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * add function
	 *
	 * display 'ad/add' view, validate form data and add new ad to the database
	 */

	function add()
	{
		debug('manager/ad page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// create '$ad' variable for use in the view
		$ad->name = '';
		$ad->text = '';
		$ad->link = '';
		$ad->image_url = '';
		$ad->image_width = '';
		$ad->image_height = '';
		$data['upload_error'] = '';
		$data['url_error'] = '';
		$data['image_width_error'] = '';
		$data['image_height_error'] = '';
		$data['ad'] = $ad;
		// check form data was submitted
		if ($this->input->post('ad_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_ad_form_validation_name'),
					'rules' => 'trim|required|max_length[256]'
				),
				array(
					'field' => 'text',
					'label' => lang('manager_ad_form_validation_text'),
					'rules' => 'trim|max_length[4096]'
				),
				array(
					'field' => 'link',
					'label' => lang('manager_ad_form_validation_link'),
					'rules' => 'trim|max_length[512]'
				),
				array(
					'field' => 'image_height',
					'label' => lang('manager_ad_form_validation_image_height'),
					'rules' => 'trim|numeric'
				),
				array(
					'field' => 'image_width',
					'label' => lang('manager_ad_form_validation_image_width'),
					'rules' => 'trim|numeric'
				),
				array(
					'field' => 'remote_image_url',
					'label' => lang('manager_ad_form_validation_remote_image_url'),
					'rules' => 'max_length[512]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['message'] = lang('manager_ad_form_fail');
				debug('loading "ad/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				//initialize some variables for uploading
				$file_error = 0;
				$orig_file_name = '';
				$remote_image_url = $this->input->post('remote_image_url');
				$height = $this->input->post('image_height');
				$width = $this->input->post('image_width');
				// when uploading ad images, uploaded file takes precedence over remote image url
				// so if a file is uploaded, the remote image url will be ignored
				if (($_FILES['userfile']['error'] > -1) && ($_FILES['userfile']['error'] != 4)) {
					debug('user has tried to upload a file');
					$remote_image_url = '';
					// set up config for logo upload
					$config['upload_path'] = './uploads/ads/images';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size'] = $this->setting['max_upload_filesize'];
					$config['max_width'] = $this->setting['max_upload_width'];
					$config['max_height'] = $this->setting['max_upload_height'];
					$config['remove_spaces'] = TRUE;
					// store the file name and extension
					$extension = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
					$file_name = str_replace(' ', '_', $_FILES['userfile']['name']);
					$file_name = substr($file_name, 0, strlen($file_name) - strlen($extension) - 1);
					$file_name = str_replace('.', '_', $_FILES['userfile']['name']);
					// create a random number to append to the file name
					$random_number = rand(0, 99999999);
					$config['file_name'] = $file_name . '_' . $random_number . '.' . $extension;
					// initialize the library with config data
					$this->upload->initialize($config);
					// attempt to upload the file
					debug('uploading the file');
					if (!$this->upload->do_upload()) {
						// there was an error uploading, set the error message
						debug('there was a file uploading error... ' . $this->upload->display_errors());
						$data['upload_error'] = $this->upload->display_errors();
						$file_error = 1;
					} else {
						// upload successful
						debug('upload was successful');
						$data['upload'] = array('upload_data' => $this->upload->data());
						$orig_file_name = $config['file_name'];
					}
				}
				if ($orig_file_name !== '') {
					debug('attempting to resize image to check it is a valid image file');
					// set up config for image library
					$config['image_library'] = 'gd2';
					$config['source_image'] = './uploads/ads/images/' . $orig_file_name;
					$random_number_resize = rand(0, 99999999);
					$config['new_image'] = $random_number_resize . '.' . $extension;
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = FALSE;
					// set required width and height
					$config['width'] = 128;
					$config['height'] = 128;
					$this->image_lib->initialize($config);
					if (!$this->image_lib->resize()) {
						// delete the file if resize fails
						debug('resize failed - deleting original file - upload failed');
						@unlink('./uploads/ads/images/' . $orig_file_name);
						$data['upload_error'] = $this->image_lib->display_errors() . 'snerf' . lang('manager_review_form_remote_image_fail');
						$file_error = 1;
					} else {
						// delete the temporary resized image file
						@unlink('./uploads/ads/images/' . $random_number_resize . '.' . $extension);
						// resize image if a width and height were provided
						if (($width > 0) && ($height > 0)) {
							debug('attempting to resize image');
							// set up config for image library
							$config['image_library'] = 'gd2';
							$config['source_image'] = './uploads/ads/images/' . $orig_file_name;
							$config['new_image'] = '';
							$config['create_thumb'] = FALSE;
							$config['maintain_ratio'] = TRUE;
							// set required width and height
							$config['width'] = $width;
							$config['height'] = $height;
							$this->image_lib->initialize($config);
							if (!$this->image_lib->resize()) {
								// delete the file if resize fails
								debug('resize failed - deleting original file - upload failed');
								@unlink('./uploads/ads/images/' . $orig_file_name);
								$data['upload_error'] = lang('manager_review_form_remote_image_fail');
								$file_error = 1;
							}
						}
					}
				}
				if ($file_error == 0) {
					// no uploading errors
					// prepare data adding to the database
					debug('file upload completed successfully');
					$name = $this->input->post('name');
					$text = $this->input->post('text');
					$link = $this->input->post('link');
					$visible_in_sidebar = isset($_POST['visible_in_sidebar']) ? 1 : 0;
					$visible_in_lists = isset($_POST['visible_in_lists']) ? 1 : 0;
					$visible_on_review_page = isset($_POST['visible_on_review_page']) ? 1 : 0;
					// add the ad
					debug('add the ad');
					$this->Ad_model->add_new_add($name, $orig_file_name, $remote_image_url, $height, $width, $text, $link, $visible_in_sidebar, $visible_in_lists, $visible_on_review_page);
					$data['message'] = lang('manager_ad_add_success');
					// clear form validation data
					$this->form_validation->clear_fields();
					// reload the form
					debug('loading "manager/ad/add" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					// error uploading so reload the form
					debug('there was an uploading error - loading "manager/ad/add" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			}
		} else {
			// form not submitted so just show the form
			debug('form not submitted - loading "manager/ad/add" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'ad/edit' view, validate form data and modify ad
	 */

	function edit($id)
	{
		debug('manager/ad page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		$data['upload_error'] = '';
		$data['url_error'] = '';
		$data['image_width_error'] = '';
		$data['image_height_error'] = '';
		// check form data was submitted
		if ($this->input->post('ad_submit')) {
			$data['ad'] = $this->Ad_model->get_ad_by_id($id);
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_ad_form_validation_name'),
					'rules' => 'trim|required|max_length[256]'
				),
				array(
					'field' => 'text',
					'label' => lang('manager_ad_form_validation_text'),
					'rules' => 'trim|max_length[4096]'
				),
				array(
					'field' => 'link',
					'label' => lang('manager_ad_form_validation_link'),
					'rules' => 'trim|max_length[512]'
				),
				array(
					'field' => 'image_height',
					'label' => lang('manager_ad_form_validation_image_height'),
					'rules' => 'trim|numeric'
				),
				array(
					'field' => 'image_width',
					'label' => lang('manager_ad_form_validation_image_width'),
					'rules' => 'trim|numeric'
				),
				array(
					'field' => 'remote_image_url',
					'label' => lang('manager_ad_form_validation_image_width'),
					'rules' => 'max_length[512]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['message'] = lang('manager_ad_form_fail');
				debug('loading "ad/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// initialize some variables for uploading
				$file_error = 0;
				$orig_file_name = '';
				$remote_image_url = $this->input->post('remote_image_url');
				$height = $this->input->post('image_height');
				$width = $this->input->post('image_width');
				// when uploading ad images, uploaded file takes precedence over remote image url
				// so if a file is uploaded, the remote image url will be ignored
				if (($_FILES['userfile']['error'] > -1) && ($_FILES['userfile']['error'] != 4)) {
					debug('user has tried to upload a file');
					$remote_image_url = '';
					// set up config for upload library
					$config['upload_path'] = './uploads/ads/images';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size'] = $this->setting['max_upload_filesize'];
					$config['max_width'] = $this->setting['max_upload_width'];
					$config['max_height'] = $this->setting['max_upload_height'];
					$config['remove_spaces'] = TRUE;
					// store the file name and extension
					$extension = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
					$file_name = str_replace(' ', '_', $_FILES['userfile']['name']);
					$file_name = substr($file_name, 0, strlen($file_name) - strlen($extension) - 1);
					$file_name = str_replace('.', '_', $_FILES['userfile']['name']);
					// create a random number to append to the file name
					$random_number = rand(0, 99999999);
					$config['file_name'] = $file_name . '_' . $random_number . '.' . $extension;
					// initialize the library with config data
					$this->upload->initialize($config);
					// attempt to upload the file
					debug('uploading the file');
					if (!$this->upload->do_upload()) {
						// if there was an error uploading, set the error message
						debug('there was a file uploading error... ' . $this->upload->display_errors());
						$data['upload_error'] = $this->upload->display_errors();
						$file_error = 1;
					} else {
						// upload successful
						debug('upload was successful');
						$data['upload'] = array('upload_data' => $this->upload->data());
						$orig_file_name = $config['file_name'];
					}
				}
				if ($orig_file_name !== '') {
					debug('attempting to resize image to check it is a valid image file');
					// set up config for image library
					$config['image_library'] = 'gd2';
					$config['source_image'] = './uploads/ads/images/' . $orig_file_name;
					$random_number_resize = rand(0, 99999999);
					$config['new_image'] = $random_number_resize . '.' . $extension;
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = FALSE;
					// set required width and height
					$config['width'] = 128;
					$config['height'] = 128;
					$this->image_lib->initialize($config);
					if (!$this->image_lib->resize()) {
						// delete the file if resize fails
						debug('resize failed - deleting original file - upload failed');
						@unlink('./uploads/ads/images/' . $orig_file_name);
						$data['upload_error'] = $this->image_lib->display_errors() . lang('manager_review_form_remote_image_fail');
						$file_error = 1;
					} else {
						// delete the temporary resized image file
						@unlink('./uploads/ads/images/' . $random_number_resize . '.' . $extension);
						// resize image if a width and height were provided
						if (($width > 0) && ($height > 0)) {
							debug('attempting to resize image');
							// set up config for image library
							$config['image_library'] = 'gd2';
							$config['source_image'] = './uploads/ads/images/' . $orig_file_name;
							$config['new_image'] = '';
							$config['create_thumb'] = FALSE;
							$config['maintain_ratio'] = TRUE;
							// set required width and height
							$config['width'] = $width;
							$config['height'] = $height;
							$this->image_lib->initialize($config);
							if (!$this->image_lib->resize()) {
								// delete the file if resize fails
								debug('resize failed - deleting original file - upload failed');
								@unlink('./uploads/ads/images/' . $orig_file_name);
								$data['upload_error'] = lang('manager_review_form_remote_image_fail');
								$file_error = 1;
							}
						}
					}
				}
				if ($file_error == 0) {
					// no uploading errors
					// prepare data for updating the database
					debug('file upload completed successfully');
					$ad = $this->Ad_model->get_ad_by_id($id);
					if (trim($orig_file_name) === '') {

						if (trim($remote_image_url) === '') {
							$orig_file_name = $ad->local_image_name;
						}
					} else {
						$remote_image_url = '';
					}
					// delete any previous uploaded image
					if (($data['ad']->local_image_name != $orig_file_name) && ($data['ad']->local_image_name !== '')) {
						@unlink('./uploads/ads/images/' . $data['ad']->local_image_name);
					}
					$name = $this->input->post('name');
					$text = $this->input->post('text');
					$link = $this->input->post('link');
					$visible_in_sidebar = isset($_POST['visible_in_sidebar']) ? 1 : 0;
					$visible_in_lists = isset($_POST['visible_in_lists']) ? 1 : 0;
					$visible_on_review_page = isset($_POST['visible_on_review_page']) ? 1 : 0;
					// update the ad
					debug('update the ad');
					$this->Ad_model->update_ad($id, $name, $orig_file_name, $remote_image_url, $height, $width, $text, $link, $visible_in_sidebar, $visible_in_lists, $visible_on_review_page);
					// reload the form
					debug('loading "manager/ad/edited" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					// error uploading so reload the form
					debug('there was an uploading error - loading "manager/ad/edit" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			}
		} else {
			// form not submitted so load data and show the form
			$data['ad'] = $this->Ad_model->get_ad_by_id($id);
			$data['visible_in_sidebar'] = $data['ad']->visible_in_sidebar > 0 ? 'CHECKED' : '';
			$data['visible_in_lists'] = $data['ad']->visible_in_lists > 0 ? 'CHECKED' : '';
			$data['visible_on_review_page'] = $data['ad']->visible_on_review_page > 0 ? 'CHECKED' : '';
			debug('form not submitted');
			if ($data['ad']) {
				debug('loading "manager/ad/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				// no ad data so redirect to ads list
				debug('ad not found - redirecting to "manager/ads"');
				redirect('/manager/ads', '301');
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
		debug('manager/ad page | delete function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the ad from the database
		$data['ad'] = $this->Ad_model->get_ad_by_id($id);
		if ($data['ad']) {
			debug('ad loaded');
			// ad exists... show confirmation page
			debug('loading "manager/ad/delete" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ad/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// redirect back to ads list
			debug('ad not found - redirecting to "manager/ads"');
			redirect('/manager/ads', '301');
		}
	}

	/*
	 * deleted function
	 *
	 * delete the ad after confirmation
	 */

	function deleted($id)
	{
		debug('manager/ad page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the ad from the database
		$data['ad'] = $this->Ad_model->get_ad_by_id($id);
		if ($data['ad']) {
			debug('ad loaded');
			// ad exists... delete the ad
			debug('delete the ad');
			$this->Ad_model->delete_ad($id);
			if ($data['ad']->local_image_name) {
				$ad_image_path = './uploads/ads/images/' . $data['ad']->local_image_name;
				debug('delete ad image');
				if (file_exists($ad_image_path)) {
					@unlink($ad_image_path);
				}
			}
		}
		// redirect back to ads list
		debug('redirecting to "manager/ads"');
		redirect('/manager/ads', '301');
	}

}

/* End of file ad.php */
/* Location: ./application/controllers/manager/ad.php */