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
 * Review management controller class
 *
 * Allows manager to add, edit, approve, un-approve or delete a review
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Review extends CI_Controller
{

	/*
	 * Review controller class constructor
	 */

	function Review()
	{
		parent::__construct();
		$this->load->model('Review_model');
		$this->load->model('Category_model');
		$this->load->model('Comment_model');
		$this->load->model('Rating_model');
		$this->load->model('Feature_model');
		$this->load->model('Review_feature_model');
		$this->load->model('Review_rating_model');
		$this->load->model('Category_default_rating_model');
		$this->load->model('Category_default_feature_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('image_lib');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * add function
	 *
	 * display 'review/add' view, validate form data and add new review to the database
	 */

	function add()
	{
		debug('manager/review page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// create '$review' variable for use in the view
		$data['categories'] = $this->Category_model->getCategoriesDropDown();
		$data['selected_category'] = 0;
		$data['featured'] = '';
		$image_name = '';
		$image_extension = '';
		$review->title = '';
		$review->description = '';
		$review->featured = '';
		$review->tags = '';
		$review->image_url = '';
		$review->vendor = '';
		$review->link = '';
		$review->meta_keywords = '';
		$review->meta_description = '';
		$data['review'] = $review;
		$data['upload_error'] = '';
		$data['grab_error'] = '';
		// check form data was submitted
		if ($this->input->post('review_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'title',
					'label' => lang('manager_review_form_validation_title'),
					'rules' => 'trim|required|min_length[5]|max_length[512]'
				),
				array(
					'field' => 'description',
					'label' => lang('manager_review_form_validation_description'),
					'rules' => 'required'
				),
				array(
					'field' => 'category_id',
					'label' => lang('manager_review_form_validation_category'),
					'rules' => 'callback__more_than_zero'
				),
				array(
					'field' => 'tags',
					'label' => lang('manager_review_form_validation_tags'),
					'rules' => 'callback__validate_tags'
				),
				array(
					'field' => 'image_url',
					'label' => lang('manager_review_form_validation_image_url'),

				),
				array(
					'field' => 'vendor',
					'label' => lang('manager_review_form_validation_vendor'),

				),
				array(
					'field' => 'link',
					'label' => lang('manager_review_form_validation_link'),

				),
				array(
					'field' => 'meta_keywords',
					'label' => lang('manager_review_form_validation_meta_keywords'),
					'rules' => 'max_length[255]'
				),
				array(
					'field' => 'meta_description',
					'label' => lang('manager_review_form_validation_meta_description'),
					'rules' => 'max_length[255]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			$this->form_validation->set_message('_validate_tags', lang('manager_review_form_validate_tags'));
			$this->form_validation->set_message('_more_than_zero', lang('manager_review_form_validate_category'));
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['message'] = lang('manager_review_form_fail');
				$data['selected_category'] = $this->input->post('category_id');
				$data['featured'] = isset($_POST['featured']) ? 1 : 0;
				debug('loading "review/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				//initialize some variables for uploading
				$file_error = 0;
				$uploaded_file_name = '';
				$uploaded_file_extension = '';
				$url = trim($this->input->post('image_url'));
				// when uploading review images, uploaded file takes precedence over remote image url
				// so if a file is uploaded, the remote image url will be ignored
				if (($_FILES['userfile']['error'] > -1) && ($_FILES['userfile']['error'] != 4)) {
					debug('user has tried to upload a file');
					$remote_image_url = '';
					// set up config for upload library
					$config['upload_path'] = './uploads/images';
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
						$data['upload_data'] = array('upload_data' => $this->upload->data());
						$uploaded_file_name = $file_name . '_' . $random_number;
						$uploaded_file_extension = $extension;
					}
				} else {
					// no file upload so check if the user entered a url for a remote image
					if ($url !== '') {
						// grab an image from a remote server and store locally
						// create a unique filename
						$md5 = md5($url . rand());
						// use Curl to grab an image from the remote url
						debug('download image from remote server... ' . $url);
						$ch = curl_init($url);
						// open a file to store the image locally
						$fp = fopen('./uploads/images/' . $md5 . '.jpg', 'w');
						curl_setopt($ch, CURLOPT_FILE, $fp);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_exec($ch);
						curl_close($ch);
						fclose($fp);
						$uploaded_file_name = $md5;
						$uploaded_file_extension = 'jpg';
					}
				}
				if ($uploaded_file_name !== '') {
					debug('file upload completed successfully');
					// create a thumb nail image for use on review page
					// set up config for image library
					$config['image_library'] = 'gd2';
					$config['source_image'] = './uploads/images/' . $uploaded_file_name . '.' . $uploaded_file_extension;
					$config['create_thumb'] = TRUE;
					$config['thumb_marker'] = '_review_thumb';
					$config['maintain_ratio'] = TRUE;
					// width and height come from theme settings
					$config['width'] = $this->setting['review_thumb_max_width'];
					$config['height'] = $this->setting['review_thumb_max_height'];
					// initialize image library with config data
					$this->image_lib->initialize($config);
					// attempt to resize image
					// if this fails, it is not a valid image file
					debug('attempting to resize image and create review thumb nail image');
					if (!$this->image_lib->resize()) {
						// resize failed... delete the file and set error message
						debug('resize failed - deleting original file - upload failed');
						@unlink('./uploads/images/' . $uploaded_file_name . '.' . $uploaded_file_extension);
						$data['grab_error'] = lang('manager_review_form_remote_image_fail');
						$file_error = 1;
					} else {
						// create a thumb nail image for use on home page, search page and other lists
						// set up config for image library
						$config['image_library'] = 'gd2';
						$config['source_image'] = './uploads/images/' . $uploaded_file_name . '.' . $uploaded_file_extension;
						$config['create_thumb'] = TRUE;
						$config['thumb_marker'] = '_list_thumb';
						$config['maintain_ratio'] = TRUE;
						$config['width'] = $this->setting['search_thumb_max_width'];
						$config['height'] = $this->setting['search_thumb_max_height'];
						// initialize image library with config data
						$this->image_lib->initialize($config);
						// attempt to resize image
						// if this fails, it is not a valid image file
						debug('attempting to resize image and create search thumb nail image');
						if (!$this->image_lib->resize()) {
							// resize failed... delete the file and set error message
							debug('resize failed - deleting original file - upload failed');
							@unlink('./uploads/images/' . $uploaded_file_name . '.' . $uploaded_file_extension);
							echo $this->image_lib->display_errors();
							$data['grab_error'] = lang('manager_review_form_remote_image_fail');
							$file_error = 1;
						}
					}
				}
				if ($file_error == 0) {
					// no uploading errors
					// prepare data for adding to the database
					$title = $this->input->post('title');
					$seo_title = $this->input->post('seo_title');
					$description = $this->input->post('description');
					$category_id = $this->input->post('category_id');
					$featured = isset($_POST['featured']) ? 1 : 0;
					$tags = $this->input->post('tags');
					$vendor = $this->input->post('vendor');
					$link = $this->input->post('link');
					$meta_keywords = str_replace('"', '', $this->input->post('meta_keywords'));
					$meta_description = str_replace('"', '', $this->input->post('meta_description'));
					$auto_approve = $this->setting['review_auto'];
					$review_approval = $this->setting['review_approval'];
					$approved = ($review_approval == 0) OR ($auto_approve == 1) ? 1 : 0;
					// add the review
					debug('add the review');
					$new_review_id = $this->Review_model->addReview($title, $description, $category_id, $featured, $tags, $uploaded_file_name, $uploaded_file_extension, $vendor, $link, $meta_keywords, $meta_description, $approved);
					// get some data and reload the form
					$default_ratings = $this->Category_default_rating_model->getDefaultRatingsByCategoryId($category_id);
					if ($default_ratings) {
						foreach ($default_ratings as $result) {
							$this->Review_rating_model->addReviewRating($new_review_id, $result->rating_id, 5);
						}
					}
					$default_features = $this->Category_default_feature_model->getDefaultFeaturesByCategoryId($category_id);
					if ($default_features) {
						foreach ($default_features as $result) {
							$this->Review_feature_model->addReviewFeature($new_review_id, $result->att_id, '');
						}
					}
					$data['message'] = lang('manager_review_add_success');
					$this->form_validation->clear_fields();
					debug('loading "manager/review/add" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					// error uploading so reload the form
					$data['message'] = lang('manager_review_form_fail') . ' - ' . $data['upload_error'];
					$data['selected_category'] = $this->input->post('category_id');
					$data['featured'] = isset($_POST['featured']) ? 1 : 0;
					debug('there was a file uploading error... ' . $this->upload->display_errors());
					debug('loading "manager/review/add" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			}
		} else {
			// form not submitted so just show the form
			debug('form not submitted - loading "manager/review/add" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'review/edit' view, validate form data and modify review
	 */

	function edit($id)
	{
		debug('manager/review page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		$data['categories'] = $this->Category_model->getCategoriesDropDown();
		$data['upload_error'] = '';
		$data['grab_error'] = '';
		// check form data was submitted
		$data['review'] = $this->Review_model->getReviewById($id);
		if ($this->input->post('review_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'title',
					'label' => lang('manager_review_form_validation_title'),
					'rules' => 'trim|required|min_length[5]|max_length[512]'
				),
				array(
					'field' => 'description',
					'label' => lang('manager_review_form_validation_description'),
					'rules' => 'required'
				),
				array(
					'field' => 'category_id',
					'label' => lang('manager_review_form_validation_category'),
					'rules' => 'callback__more_than_zero'
				),
				array(
					'field' => 'tags',
					'label' => lang('manager_review_form_validation_tags'),
					'rules' => 'callback__validate_tags'
				),
				array(
					'field' => 'image_url',
					'label' => lang('manager_review_form_validation_image_url'),

				),
				array(
					'field' => 'vendor',
					'label' => lang('manager_review_form_validation_vendor'),

				),
				array(
					'field' => 'link',
					'label' => lang('manager_review_form_validation_link'),

				),
				array(
					'field' => 'meta_keywords',
					'label' => lang('manager_review_form_validation_meta_keywords'),
					'rules' => 'max_length[255]'
				),
				array(
					'field' => 'meta_description',
					'label' => lang('manager_review_form_validation_meta_description'),
					'rules' => 'max_length[255]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			$this->form_validation->set_message('_validate_tags', lang('manager_review_form_validate_tags'));
			$this->form_validation->set_message('_more_than_zero', lang('manager_review_form_validate_category'));
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['selected_category'] = $data['review']->category_id;
				$data['featured'] = isset($_POST['featured']) ? 1 : 0;
				debug('loading "review/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// initialize some variables for uploading
				$file_error = 0;
				$url = trim($this->input->post('image_url'));
				$uploaded_file_name = '';
				$uploaded_file_extension = '';
				// when uploading review images, uploaded file takes precedence over remote image url
				// so if a file is uploaded, the remote image url will be ignored
				if (($_FILES['userfile']['error'] > -1) && ($_FILES['userfile']['error'] != 4)) {
					debug('user has tried to upload a file');
					$remote_image_url = '';
					// set up config for upload library
					$config['upload_path'] = './uploads/images';
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
						$data['upload_data'] = array('upload_data' => $this->upload->data());
						$uploaded_file_name = $file_name . '_' . $random_number;
						$uploaded_file_extension = $extension;
					}
				} else {
					// no file upload so check if the user entered a url for a remote image
					if ($url !== '') {
						// grab an image from a remote server and store locally
						// create a unique filename
						$md5 = md5($url . rand());
						// use Curl to grab an image from the remote url
						debug('download image from remote server... ' . $url);
						$ch = curl_init($url);
						// open a file to store the image locally
						$fp = fopen('./uploads/images/' . $md5 . '.jpg', 'w');
						curl_setopt($ch, CURLOPT_FILE, $fp);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_exec($ch);
						curl_close($ch);
						fclose($fp);
						$uploaded_file_name = $md5;
						$uploaded_file_extension = 'jpg';
					}
				}
				if ($uploaded_file_name !== '') {
					debug('file upload completed successfully');
					// create a thumb nail image for use on review page
					// set up config for image library
					$config['image_library'] = 'gd2';
					$config['source_image'] = './uploads/images/' . $uploaded_file_name . '.' . $uploaded_file_extension;
					$config['create_thumb'] = TRUE;
					$config['thumb_marker'] = '_review_thumb';
					$config['maintain_ratio'] = TRUE;
					// width and height come from theme settings
					$config['width'] = $this->setting['review_thumb_max_width'];
					$config['height'] = $this->setting['review_thumb_max_height'];
					// initialize image library with config data
					$this->image_lib->initialize($config);
					// attempt to resize image
					// if this fails, it is not a valid image file
					debug('attempting to resize image and create review thumb nail image');
					if (!$this->image_lib->resize()) {
						// resize failed... delete the file and set error message
						debug('resize failed - deleting original file - upload failed');
						@unlink('./uploads/images/' . $uploaded_file_name . '.' . $uploaded_file_extension);
						echo $this->image_lib->display_errors();
						$data['grab_error'] = lang('manager_review_form_remote_image_fail');
						$file_error = 1;
					} else {
						// create a thumb nail image for use on home page, search page and other lists
						// set up config for image library
						$config['image_library'] = 'gd2';
						$config['source_image'] = './uploads/images/' . $uploaded_file_name . '.' . $uploaded_file_extension;
						$config['create_thumb'] = TRUE;
						$config['thumb_marker'] = '_list_thumb';
						$config['maintain_ratio'] = TRUE;
						$config['width'] = $this->setting['search_thumb_max_width'];
						$config['height'] = $this->setting['search_thumb_max_height'];
						// initialize image library with config data
						$this->image_lib->initialize($config);
						// attempt to resize image
						// if this fails, it is not a valid image file
						debug('attempting to resize image and create search thumb nail image');
						if (!$this->image_lib->resize()) {
							// resize failed... delete the file and set error message
							debug('resize failed - deleting original file - upload failed');
							@unlink('./uploads/images/' . $uploaded_file_name . '.' . $uploaded_file_extension);
							$data['grab_error'] = lang('manager_review_form_remote_image_fail');
							$file_error = 1;
						}
					}
				}
				if ($file_error == 0) {
					// no uploading errors
					// prepare data for adding to the database
					$review = $this->Review_model->getReviewById($id);
					if (trim($uploaded_file_name) === '') {
						$image_name = $review->image_name;
						$image_extension = $review->image_extension;
					} else {
						$image_name = $uploaded_file_name;
						$image_extension = $uploaded_file_extension;
					}
					// delete any previous uploaded image
					$data['review'] = $this->Review_model->getReviewById($id);
					$current_file = $data['review']->image_name . '.' . $data['review']->image_extension;
					$new_file = $image_name . '.' . $image_extension;
					if (($current_file != $new_file) && ($current_file !== '')) {
						$review_thumb_path = './uploads/images/' . $data['review']->image_name . '_review_thumb.' . $data['review']->image_extension;
						$search_thumb_path = './uploads/images/' . $data['review']->image_name . '_list_thumb.' . $data['review']->image_extension;
						$image_path = './uploads/images/' . $data['review']->image_name . '.' . $data['review']->image_extension;
						debug('delete review image and thumb nail images');
						if (file_exists($review_thumb_path)) {
							@unlink($review_thumb_path);
						}
						if (file_exists($search_thumb_path)) {
							@unlink($search_thumb_path);
						}
						if (file_exists($image_path)) {
							@unlink($image_path);
						}
					}

					$title = $this->input->post('title');
					$seo_title = $this->input->post('seo_title');
					$description = $this->input->post('description');
					$category_id = $this->input->post('category_id');
					$featured = isset($_POST['featured']) ? 1 : 0;
					$vendor = $this->input->post('vendor');
					$link = $this->input->post('link');
					$meta_keywords = str_replace('"', '', $this->input->post('meta_keywords'));
					$meta_description = str_replace('"', '', $this->input->post('meta_description'));
					$tags = $this->input->post('tags');
					$tag_array = explode(',', $tags);
					// update the tags for this review
					$tags = $this->Review_model->updateTags($id, $tag_array);
					// update the review
					debug('update the review');
					$this->Review_model->updateReview($id, $title, $description, $category_id, $featured, $tags, $image_name, $image_extension, $vendor, $link, $meta_keywords, $meta_description);
					// get some data and reload the form
					$data['review'] = $this->Review_model->getReviewById($id);
					$data['categories'] = $this->Category_model->getCategoriesDropDown();
					$data['upload_error'] = '';
					$data['grab_error'] = '';
					$data['current_image'] = base_url() . 'uploads/images/' . $data['review']->image_name . '_list_thumb.' . $data['review']->image_extension;
					$data['selected_category'] = $data['review']->category_id;
					$data['review']->image_url = '';
					$data['featured'] = $data['review']->featured > 0 ? 'CHECKED' : '';
					$data['message'] = lang('manager_review_edited_message');
					debug('loading "manager/review/edit" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					// error uploading so reload the form
					$data['current_image'] = base_url() . 'uploads/images/' . $data['review']->image_name . '_list_thumb.' . $data['review']->image_extension;
					$data['selected_category'] = $data['review']->category_id;
					$data['review']->image_url = '';
					$data['featured'] = $data['review']->featured > 0 ? 'CHECKED' : '';
					debug('there was a file uploading error... ' . $this->upload->display_errors());
					debug('loading "manager/review/edit" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			}
		} else {
			// form not submitted so load data and reload the form
			debug('form not submitted');
			$data['review'] = $this->Review_model->getReviewById($id);
			if ($data['review']) {
				$data['selected_category'] = $data['review']->category_id;
				$data['review']->image_url = '';
				$data['current_image'] = base_url() . 'uploads/images/' . $data['review']->image_name . '_list_thumb.' . $data['review']->image_extension;
				$data['featured'] = $data['review']->featured > 0 ? 'CHECKED' : '';
				debug('loading "manager/review/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				// no review data so redirect to reviews list
				debug('review not found - loading "manager/review/edit" view');
				redirect('/manager/reviews', 301);
			}
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
	 * _validate_tags function
	 *
	 * check tags only contain valid characters
	 */

	function _validate_tags($str)
	{
		$result = (!preg_match("/^([a-z0-9,-_\s])+$/i", $str)) ? FALSE : TRUE;
		if ($result === TRUE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function delete($id)
	{
		debug('manager/review page | delete function');
		$this->secure->allowManagers($this->session);
		$data['review'] = $this->Review_model->getReviewById($id);
		if ($data['review']) {
			debug('loading "manager/review/delete" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			debug('review not found - redirecting to "manager/reviews"');
			redirect('/manager/reviews', '301');
		}
	}

	/*
	 * delete function
	 *
	 * display delete confirmation page
	 */

	function deleted($id)
	{
		debug('manager/review page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load the review from the database
		$data['review'] = $this->Review_model->getReviewById($id);
		if ($data['review']) {
			debug('loaded the review');
			// review exists
			// delete all review_features for this review
			debug('delete all review features for the review');
			$this->Review_feature_model->deleteReviewFeaturesByReviewId($id);
			// delete all review_ratings for this review
			debug('delete all review ratings for the review');
			$this->Review_rating_model->deleteReviewRatingsByReviewId($id);
			// delete all comments for this review
			debug('delete all comments for the review');
			$this->Comment_model->deleteCommentsByReviewId($id);
			// delete the review
			debug('delete the review');
			$this->Review_model->deleteReview($id);
			// delete image and thumbnails for this review
			if ($data['review']->image_name) {
				$review_thumb_path = './uploads/images/' . $data['review']->image_name . '_review_thumb.' . $data['review']->image_extension;
				$search_thumb_path = './uploads/images/' . $data['review']->image_name . '_list_thumb.' . $data['review']->image_extension;
				$image_path = './uploads/images/' . $data['review']->image_name . '.' . $data['review']->image_extension;
				debug('delete review image and thumb nail images');
				if (file_exists($review_thumb_path)) {
					@unlink($review_thumb_path);
				}
				if (file_exists($search_thumb_path)) {
					@unlink($search_thumb_path);
				}
				if (file_exists($image_path)) {
					@unlink($image_path);
				}
			}
		}
		// redirect to reviews list
		debug('redirecting to "manager/reviews"');
		redirect('/manager/reviews', '301');
	}

	/*
	 * approve function
	 *
	 * approve a review and redirect to reviews list
	 */

	function approve($review_id)
	{
		debug('manager/review page | approve function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// approve the review
		debug('approve the review');
		$this->Review_model->reviewApproval($review_id, 1);
		// redirect to reviews list
		debug('redirecting to "manager/reviews"');
		redirect('/manager/reviews', '301');
	}

	/*
	 * approve_pending function
	 *
	 * approve a review and redirect to reviews pending list
	 */

	function approve_pending($review_id)
	{
		debug('manager/review page | approve_pending function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// approve the review
		debug('approve the pending review');
		$this->Review_model->reviewApproval($review_id, 1);
		// redirect to reviews pending list
		debug('redirecting to "manager/reviews/pending"');
		redirect('/manager/reviews/pending', '301');
	}

	/*
	 * unapprove function
	 *
	 * unapprove a review and redirect to reviews list
	 */

	function unapprove($review_id)
	{
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// unapprove the review
		$this->Review_model->reviewApproval($review_id, 0);
		// redirect to reviews list
		redirect('/manager/reviews', '301');
	}

	/*
	 * delete_pending function
	 *
	 * display delete confirmation page
	 */

	function delete_pending($id)
	{
		debug('manager/review page | delete_pending function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load the review
		$data['review'] = $this->Review_model->getReviewById($id);
		if ($data['review']) {
			// review exists... show delete confirmation page
			debug('loading "manager/review/delete_pending" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/review/delete_pending', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// redirect to reviews pending list
			debug('review not found - redirecting to "manager/reviews/pending"');
			redirect('/manager/reviews/pending', '301');
		}
	}

	/*
	 * deleted_pending function
	 *
	 * delete the pending review after confirmation
	 */

	function deleted_pending($id)
	{
		debug('manager/review page | deleted_pending function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load the review
		$data['review'] = $this->Review_model->getReviewById($id);
		if ($data['review']) {
			// review exists
			debug('loaded the review');
			// delete all review_features for this review
			debug('delete all review features for the review');
			$this->Review_feature_model->deleteReviewFeaturesByReviewId($id);
			// delete all review_ratings for this review
			debug('delete all review ratings for the review');
			$this->Review_rating_model->deleteReviewRatingsByReviewId($id);
			// delete all comments for this review
			debug('delete all comments for the review');
			$this->Comment_model->deleteCommentsByReviewId($id);
			// delete the review
			debug('delete the review');
			$this->Review_model->deleteReview($id);
			// delete image and thumbnails for this review
			if ($data['review']->image_name) {
				$review_thumb_path = './uploads/images/' . $data['review']->image_name . '_review_thumb.' . $data['review']->image_extension;
				$search_thumb_path = './uploads/images/' . $data['review']->image_name . '_search_thumb.' . $data['review']->image_extension;
				$image_path = './uploads/images/' . $data['review']->image_name . '.' . $data['review']->image_extension;
				debug('delete review image and thumb nail images');
				if (file_exists($review_thumb_path)) {
					@unlink($review_thumb_path);
				}
				if (file_exists($search_thumb_path)) {
					@unlink($search_thumb_path);
				}
				if (file_exists($image_path)) {
					@unlink($image_path);
				}
			}
		}
		// redirect to reviews pending list
		debug('redirecting to "manager/reviews/pending"');
		redirect('/manager/reviews/pending', '301');
	}

}

/* End of file review.php */
/* Location: ./application/controllers/manager/review.php */
