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
 * Contact controller class
 *
 * Displays a form that a visitor uses to send an email to the site email address
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
 */
class Contact extends CI_Controller
{

	/*
	 * Contact controller class constructor
	 */

	function contact()
	{
		parent::__construct();
		$this->load->library('email');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('Review_model');
		$this->load->model('Ad_model');
		$this->load->model('Category_model');
		$this->load->helper('captcha');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function
	 *
	 * display/process the contact form
	 */

	function index()
	{
		debug('contact page | index function');
		// set page_title, meta_keywords and meta_description
		$data['meta_keywords'] = lang('contact_page_meta_keywords');
		$data['meta_description'] = lang('contact_page_meta_description') . ' - ' . $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . strip_tags($this->setting['site_summary_text']);

		$data['page_title'] = $this->setting['site_name'] . ' - ' . lang('contact_page_title');
		// load data for view
		$data['sidebar_ads'] = $this->Ad_model->get_ads($this->setting['max_ads_home_sidebar'], 3);
		$data['show_recent'] = $this->setting['recent_review_sidebar'];
		$data['show_search'] = $this->setting['search_sidebar'];
		$data['categories'] = $this->Category_model->get_all_categories(0);
		$data['show_categories'] = $this->setting['categories_sidebar'];
		$data['captcha_verification'] = $this->setting['captcha_verification'];
		$data['keywords'] = '';
		$approval_required = $this->setting['review_approval'];
		if ($data['show_recent'] == 1) {
			$data['recent'] = $this->Review_model->get_latest_reviews($this->setting['number_of_reviews_sidebar'], 0, $approval_required);
		} else {
			$data['recent'] = FALSE;
		}
		if ($this->setting['tag_cloud_sidebar'] > 0) {
			//Prepare Tag Cloud
			$tagcloud = $this->Review_model->get_tag_cloud_array();
			if ($tagcloud !== FALSE) {
				$data['tagcloud'] = $tagcloud;
				foreach ($data['tagcloud'] as $key => $value) {
					$tagcount[$key] = $value[0];
				}
				$data['cloudmax'] = max($tagcount);
				$data['cloudmin'] = min($tagcount);
			}
		}


		// set up config for captcha images
		$vals = array(
			'img_path' => './uploads/captcha/',
			'img_url' => site_url() . 'uploads/captcha/'
		);
		$cap = create_captcha($vals);
		$cap_data = array(
			'captcha_time' => $cap['time'],
			'ip_address' => $this->input->ip_address(),
			'word' => $cap['word']
		);
		// insert temporary captcha data
		$query = $this->db->insert_string('captcha', $cap_data);
		$this->db->query($query);
		$data['captcha_image'] = $cap['image'];

		// check if form was submitted
		if ($this->input->post('contact_submit')) {
			// set up form validation config
			debug('form was submitted');
			$config = array(
				array(
					'field' => 'name',
					'label' => lang('contact_page_form_name'),
					'rules' => 'min_length[2]|required'
				),
				array(
					'field' => 'email',
					'label' => lang('contact_page_form_email'),
					'rules' => 'valid_email|required'
				),
				array(
					'field' => 'message',
					'label' => lang('contact_page_form_message'),
					'rules' => 'min_length[20]|required'
				),
				array(
					'field' => 'captcha',
					'label' => lang('contact_page_form_captcha'),
					'rules' => 'required'
				)
			);
			$this->form_validation->set_error_delimiters('<br><div class="text-error">', '</div>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				// validation failed - reload page with error message(s)
				debug('form validation failed');
				$data[] = '';
				debug('loading "contact/form" view');
				$sections = array(
					'content' => 'site/' . $this->setting['current_theme'] . '/template/contact/form',
					'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
				);
				$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
			} else {
				// validation successful
				// prepare email data
				$captcha = trim($this->input->post('captcha'));

				debug('check captcha');
				// set captcha data to expire after 5 minutes
				$expiration = time() - 300;
				// delete 'old' captcha data -  if we didn't do this the captcha table would keep growing
				$this->db->query('DELETE FROM captcha WHERE captcha_time < ' . $expiration);
				$sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
				$binds = array(
					$captcha,
					$this->input->ip_address(),
					$expiration
				);
				$query = $this->db->query($sql, $binds);
				$row = $query->row();
				$captcha_success = $row->count > 0;
				if ($captcha_success) {
					// captcha code was entered successfully
					// delete this captcha item from the database
					$this->db->query("DELETE FROM captcha WHERE word = '" . $captcha . "'");
					$email_message = "Email: " . $this->input->post('email') . "\n\n";
					$email_message .= "Message: " . $this->input->post('message');
					$this->email->from($this->input->post('email'), 'Contact Form At ' . $this->setting['site_name']);
					$this->email->to($this->setting['site_email']);
					$this->email->subject(lang('contact_email_subject') . $this->input->post('name'));
					$this->email->message($email_message);
					// send the email
					debug('sending contact email');
					$this->email->send();
					// show the 'contact/sent' page
					$data['name'] = $this->input->post('name');
					debug('loading "contact/sent" view');
					$sections = array(
						'content' => 'site/' . $this->setting['current_theme'] . '/template/contact/sent',
						'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
					);
				} else {
					// user failed to enter correct captcha code - reload page with error message(s)
					debug('captcha verification failed');
					$data['error_message'] = lang('contact_page_captcha_failed');
					debug('loading "contact/form" view');
					$sections = array(
						'content' => 'site/' . $this->setting['current_theme'] . '/template/contact/form',
						'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
					);
				}
				$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
			}
		} else {
			// form not submitted so just show the page
			debug('form not submitted');
			$data['error_message'] = '';
			debug('loading "contact/form" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/contact/form',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		}
	}

}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */