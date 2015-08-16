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
 * User management controller class
 *
 * Allows manager to add, edit or delete a user
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class User extends CI_Controller
{

	/*
	 * User controller class constructor
	 */

	function user()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->helper('form');
		$this->load->helper('security');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * add function
	 *
	 * display 'user/add' view, validate form data and add new user to the database
	 */

	function add()
	{
		debug('manager/user page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// create '$user' variable for use in the view
		$user->name = '';
		$user->password = '';
		$user->email = '';
		$user->level = '';
		$data['user'] = $user;
		// check form data was submitted
		if ($this->input->post('user_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_user_form_validation_name'),
					'rules' => 'trim|alpha_numeric|required|min_length[4]|max_length[15]'
				),
				array(
					'field' => 'password',
					'label' => lang('manager_user_form_validation_password'),
					'rules' => 'alpha_numeric|required|min_length[6]|max_length[15]'
				),
				array(
					'field' => 'email',
					'label' => lang('manager_user_form_validation_email'),
					'rules' => 'trim|required|valid_email|min_length[5]|max_length[255]'
				),
				array(
					'field' => 'level',
					'label' => lang('manager_user_form_validation_meta_level'),
					'rules' => 'numeric|required'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload user with error message(s)
				$data['message'] = lang('manager_user_form_fail');
				debug('loading "user/add" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// check username does not exist
				$user_exists = $this->User_model->get_user_by_name(trim($this->input->post('name')));
				if (!$user_exists) {
					// check email does not exist
					$email_exists = $this->User_model->manager_email_exists($this->input->post('email'));
					if (!$email_exists) {
						// prepare data for adding to database
						$name = $this->input->post('name');
						$password = $this->input->post('password');
						$email = $this->input->post('email');
						$level = $this->input->post('level');
						// add the user
						$new_user_id = $this->User_model->add_user($name, $password, $email, $level);
						$data['message'] = lang('manager_user_add_success');
						// clear form validation data
						$this->form_validation->clear_fields();
						// display the form
						debug('loading "manager/user/add" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					} else {
						// email address already exists - reload page and display error
						$data['email_error'] = lang('manager_user_form_email_exists');
						debug('email address already exists - loading "manager/user/add" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					}
				} else {
					// username already exists, reload page with error message
					$data['name_error'] = lang('manager_user_form_username_exists');
					debug('username already exists - loading "manager/user/add" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			}
		} else {
			// form was not submitted so just show the form
			debug('form not submitted - loading "manager/user/add" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	/*
	 * edit function
	 *
	 * display 'user/edit' view, validate form data and modify user
	 */

	function edit($id)
	{
		debug('manager/user page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// check if this is the only user at manager level
		// if so we prevent the user editing the user level
		$data['last_manager'] = $this->User_model->count_users_for_level(10) < 2 ? TRUE : FALSE;
		if ($this->input->post('user_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('manager_user_form_validation_name'),
					'rules' => 'trim|alpha_numeric|required|min_length[4]|max_length[15]'
				),
				array(
					'field' => 'password',
					'label' => lang('manager_user_form_validation_password'),
					'rules' => 'alpha_numeric|required|min_length[6]|max_length[15]'
				),
				array(
					'field' => 'email',
					'label' => lang('manager_user_form_validation_email'),
					'rules' => 'trim|required|valid_email|min_length[5]|max_length[255]'
				),
				array(
					'field' => 'level',
					'label' => lang('manager_user_form_validation_meta_level'),
					'rules' => 'numeric|required'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload user with error message(s)
				debug('loading "manager/user/edit" view');
				$data['user'] = $this->User_model->get_user_by_id($id);
				$data['message'] = lang('manager_user_form_fail');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// check username does not exist... note we provide a user id when editing
				// this tells the getUserByName function to ignore the user's current username
				$user_exists = $this->User_model->get_user_by_name(trim($this->input->post('name')), $id);
				if (!$user_exists) {
					// check email does not exist... again providing a user id to ignore this user's current email address
					$email_exists = $this->User_model->manager_email_exists($this->input->post('email'), $id);
					if (!$email_exists) {
						// prepare data for adding to database
						$name = $this->input->post('name');
						$email = $this->input->post('email');
						$password = $this->input->post('password');
						$level = $this->input->post('level');
						debug('update the user');
						// update the user
						$this->User_model->update_user($id, $name, $password, $email, $level);
						if ($id == $this->session->userdata('id')) {
							// if we are editing the currently logged in user
							// update the session username so we can show it on the page
							$this->session->set_userdata('name', $name);
						}
						if (($id == $this->session->userdata['id']) && ($level < 10)) {
							// if the user has edited the user level to less than manager level
							// and this is the user currently logged in, log the user out
							debug('currently logged in manager has changed own user level to lower than manager level! - logging out');
							redirect('/manager/logout', 301);
						} else {
							// currently logged in user has not lost manager level so show the 'user/edited' page
							debug('loading "manager/user/edited" view');
							$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
							$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
						}
					} else {
						// email already exists
						$user = $this->User_model->get_user_by_id($id);
						// 'PASSWORDSAME' tells the user model that the password has not been edited
						$user->password = 'PASSWORDSAME';
						$data['user'] = $user;
						$data['email_error'] = lang('manager_user_form_email_exists');
						// reload the page with error message
						debug('email address already exists - loading "manager/user/edit" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					}
				} else {
					// username already exists
					$user = $this->User_model->get_user_by_id($id);
					$user->password = 'PASSWORDSAME';
					$data['user'] = $user;
					$data['name_error'] = lang('manager_user_form_username_exists');
					// reload the page with error message
					debug('username address already exists - loading "manager/user/edit" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			}
		} else {
			// form not submitted, load the data and show the form
			$user = $this->User_model->get_user_by_id($id);
			$user->password = 'PASSWORDSAME';
			debug('form not submitted');
			$data['user'] = $user;
			if ($data['user']) {
				debug('loading "manager/user/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				// user not found, redirect to users list
				debug('user not found - redirecting to "manager/users"');
				redirect('/manager/users', 301);
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
		debug('manager/user page | delete function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the user from the database
		$data['user'] = $this->User_model->get_user_by_id($id);
		if ($data['user']) {
			debug('loaded user');
			// user exists... show confirmation page
			debug('loading "manager/user/delete" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/user/delete', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no user data so redirect back to users list
			debug('user not found - redirecting to "manager/users"');
			redirect('/manager/users', '301');
		}
	}

	/*
	 * deleted function
	 *
	 * delete the user after confirmation
	 */

	function deleted($id)
	{
		debug('manager/user page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the user from the database
		$data['user'] = $this->User_model->get_user_by_id($id);
		if ($data['user']) {
			debug('loaded user');
			// delete the user
			debug('delete the user');
			$this->User_model->delete_user($id);
		}
		// redirect back to users list
		debug('redirect to "manager/users"');
		redirect('/manager/users', '301');
	}

}

/* End of file user.php */
/* Location: ./application/controllers/manager/user.php */