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
 * Reset_password controller class
 *
 * Allows user to reset password
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Reset_password extends CI_Controller
{

	/*
	 * Forgot_login controller class constructor
	 */

	function reset_password()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('email');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * do_reset function
	 *
	 * check the provided key, reset the password and send an email to the user
	 */

	function do_reset($key)
	{
		debug('manager/reset_password page | do_reset function');
		// check key was provided
		if ($key !== '') {
			// use key to find the user's email address
			$user_email = $this->User_model->get_email_from_key($key);
			if ($user_email) {
				debug('found user\'s email address using key');
				// create a new password for the user
				$new_password = $this->User_model->reset_password($key);
				if ($new_password) {
					debug('created new password');
					// send email to the user with the new password
					$email_message = lang('manager_login_forgot_email_message_2a') . "\n\n";
					$email_message .= $new_password . "\n\n";
					$email_message .= lang('manager_login_forgot_email_message_2b') . ' ' . base_url() . 'manager/login';
					$this->email->from($this->setting['site_email']);
					$this->email->to($user_email);
					$this->email->subject(lang('manager_login_forgot_new_password_subject'));
					$this->email->message($email_message);
					debug('sending email message to user');
					if ($this->email->send()) {
						// email sent... display the 'password reset' page
						$data[] = '';
						debug('loading "manager/password_reset" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/login/password_reset');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					} else {
						debug('error sending email (server error)');
						show_error(lang('error_sending_email'));
						exit;
					}
				} else {
					// problem creating password - redirect to
					debug('problem creating new password - show error');
					show_error(lang('error_creating_password'));
					exit;
				}
			} else {
				// email not found - redirect to log in page
				debug('email addresss not found - redirecting to "manager/home"');
				redirect('/manager/home', '301');
			}
		} else {
			// no key - redirect to log in page
			debug('no key provided - redirecting to "manager/home"');
			redirect('/manager/home', '301');
		}
	}

}

/* End of file reset_password.php */
/* Location: ./application/controllers/manager/reset_password.php */