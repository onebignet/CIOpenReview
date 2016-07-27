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
 * Forgot_login controller class
 *
 * Sends an email with a link to the reset password page
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Forgot_login extends CI_Controller
{

	/*
	 * Forgot_login controller class constructor
	 */

	function forgot_login()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('User_model');
		$this->load->library('email');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function (default)
	 *
	 * display forgot login form
	 */

	function index()
	{
		debug('manager/forgot_login page | index function');
		// check manager is not already logged in
		if (!$this->secure->is_manager_logged_in($this->session)) {
			debug('manager is not already logged in');
			// check form was submitted
			if ($this->input->post('login_forgot_submit')) {
				// set up form validation config
				debug('form was submitted');
				$config = array(
					array(
						'field' => 'login_email',
						'label' => lang('manager_login_forgot_validation_email'),
						'rules' => 'trim|required'
					)
				);
				$this->form_validation->set_error_delimiters('<br><span class="label label-danger">', '</span>');
				$this->form_validation->set_rules($config);
				debug('validate form data');
				// validate the form data
				if ($this->form_validation->run() === FALSE) {
					// validation failed - reload page with error message(s)
					debug('validation failed - loading "manager/forgot_login" view');
					$data['message'] = '';
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/login/forgot_login');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					// validation successful
					debug('validation successful');
					// check email address belongs to a manager-level user
					$user_id = $this->User_model->manager_email_exists($this->input->post('login_email'));
					if ($user_id) {
						debug('manager\'s email address exists');
						// store a temporary key in the user record
						$temporary_key = $this->User_model->store_temporary_key($user_id);
						// create the email message
						$user = $this->User_model->get_user_by_id($user_id);
						$email_message = lang('manager_login_forgot_email_message_1a') . $this->setting['site_name'] . "\n\n";
						$email_message .= lang('manager_login_forgot_email_message_1b') . $user->name . "\n\n";
						$email_message .= lang('manager_login_forgot_email_message_1c') . "\n\n";
						$email_message .= lang('manager_login_forgot_email_message_1d') . "\n\n";
						// include a link with the key so we can identify the user when they confirm the password reset request
						$email_message .= base_url() . 'manager/reset_password/do_reset/' . urlencode($temporary_key);
						$this->email->from($this->setting['site_email']);
						$this->email->to($this->input->post('login_email'));
						$this->email->subject(lang('manager_login_forgot_password_reset_subject') . $this->setting['site_name']);
						$this->email->message($email_message);
						// send the email
						debug('send the email to the user');
						if ($this->email->send()) {
							// email sent... display the 'login sent' page
							$data[] = '';
							debug('loading "manager/login_sent" view');
							$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/login/login_sent');
							$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
						} else {
							debug('email not sent - show error');
							show_error(lang('error_sending_email'));
							exit;
						}
					} else {
						// if email address not found reload the page with a message informing the user
						$data['message'] = lang('manager_login_forgot_no_user');
						debug('email address not found - loading "manager/forgot_login" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/login/forgot_login');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					}
				}
			} else {
				// form not submitted so just show the form
				$data['message'] = '';
				debug('form not submitted - loading "manager/forgot_login" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/login/forgot_login');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// manager is already logged in so redirect to manager home page
			debug('manager is already logged in - redirecting to "manager/home"');
			redirect('/manager/home', 301);
		}
	}

}

/* End of file forgot_login.php */
/* Location: ./application/controllers/manager/forgot_login.php */