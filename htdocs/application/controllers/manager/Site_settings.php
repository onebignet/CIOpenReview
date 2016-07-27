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
 * Site_settings management controller class
 *
 * Allows manager to edit site settings
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Site_settings extends CI_Controller
{

	/*
	 * Site_settings controller class constructor
	 */

	function site_settings()
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
	 * display 'site_settings/edit' view, validate form data and update the database
	 */

	function edit()
	{
		debug('manager/site_settings page | edit function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// prepare data for the view
		$data['site_name'] = $this->setting['site_name'];
		$data['site_email'] = $this->setting['site_email'];
		$data['site_summary_title'] = $this->setting['site_summary_title'];
		$data['site_summary_text'] = $this->setting['site_summary_text'];
		$data['debug'] = $this->setting['debug'] > 0 ? 'CHECKED' : '';
		$data['show_visitor_rating'] = $this->setting['show_visitor_rating'] > 0 ? 'CHECKED' : '';
		$data['captcha_verification'] = $this->setting['captcha_verification'] > 0 ? 'CHECKED' : '';
		$data['thumbnail_is_link'] = $this->setting['thumbnail_is_link'] > 0 ? 'CHECKED' : '';
		$data['featured_section_home'] = $this->setting['featured_section_home'] > 0 ? 'CHECKED' : '';
		$data['featured_section_review'] = $this->setting['featured_section_review'] > 0 ? 'CHECKED' : '';
		$data['featured_section_article'] = $this->setting['featured_section_article'] > 0 ? 'CHECKED' : '';
		$data['featured_section_search'] = $this->setting['featured_section_search'] > 0 ? 'CHECKED' : '';
		$data['featured_count'] = $this->setting['featured_count'];
		$data['featured_minimum'] = $this->setting['featured_minimum'];
		$data['search_sidebar'] = $this->setting['search_sidebar'] > 0 ? 'CHECKED' : '';
		$data['recent_review_sidebar'] = $this->setting['recent_review_sidebar'] > 0 ? 'CHECKED' : '';
		$data['number_of_reviews_sidebar'] = $this->setting['number_of_reviews_sidebar'];
		$data['tag_cloud_sidebar'] = $this->setting['tag_cloud_sidebar'] > 0 ? 'CHECKED' : '';
		$data['categories_sidebar'] = $this->setting['categories_sidebar'] > 0 ? 'CHECKED' : '';
		$data['max_ads_home_sidebar'] = $this->setting['max_ads_home_sidebar'];
		$data['max_ads_review_sidebar'] = $this->setting['max_ads_review_sidebar'];
		$data['max_ads_article_sidebar'] = $this->setting['max_ads_article_sidebar'];
		$data['max_ads_search_sidebar'] = $this->setting['max_ads_search_sidebar'];
		$data['max_ads_custom_page_sidebar'] = $this->setting['max_ads_custom_page_sidebar'];
		$data['max_ads_home_lists'] = $this->setting['max_ads_home_lists'];
		$data['max_ads_articles_lists'] = $this->setting['max_ads_articles_lists'];
		$data['max_ads_results_lists'] = $this->setting['max_ads_results_lists'];
		$data['review_approval'] = $this->setting['review_approval'] > 0 ? 'CHECKED' : '';
		$data['review_auto'] = $this->setting['review_auto'] > 0 ? 'CHECKED' : '';
		$data['comment_approval'] = $this->setting['comment_approval'] > 0 ? 'CHECKED' : '';
		$data['comment_auto'] = $this->setting['comment_auto'] > 0 ? 'CHECKED' : '';
		$data['perpage_site_home'] = $this->setting['perpage_site_home'];
		$data['perpage_site_search'] = $this->setting['perpage_site_search'];
		$data['perpage_site_category'] = $this->setting['perpage_site_category'];
		$data['perpage_site_articles'] = $this->setting['perpage_site_articles'];
		$data['perpage_manager_reviews'] = $this->setting['perpage_manager_reviews'];
		$data['perpage_manager_reviews_pending'] = $this->setting['perpage_manager_reviews_pending'];
		$data['perpage_manager_comments'] = $this->setting['perpage_manager_comments'];
		$data['perpage_manager_comments_pending'] = $this->setting['perpage_manager_comments_pending'];
		$data['perpage_manager_categories'] = $this->setting['perpage_manager_categories'];
		$data['perpage_manager_features'] = $this->setting['perpage_manager_features'];
		$data['perpage_manager_ratings'] = $this->setting['perpage_manager_ratings'];
		$data['perpage_manager_articles'] = $this->setting['perpage_manager_articles'];
		$data['perpage_manager_custom_pages'] = $this->setting['perpage_manager_custom_pages'];
		$data['perpage_manager_ads'] = $this->setting['perpage_manager_ads'];
		$data['perpage_manager_users'] = $this->setting['perpage_manager_users'];
		debug('loaded all settings data');
		// check form was submitted
		if ($this->input->post('settings_submit')) {
			debug('form was submitted');
			// set up form validation config
			$config = array(
				array(
					'field' => 'site_name',
					'label' => lang('manager_site_settings_form_validation_site_name'),
					'rules' => 'trim|required|min_length[5]|max_length[255]'
				),
				array(
					'field' => 'site_email',
					'label' => lang('manager_site_settings_form_validation_site_email'),
					'rules' => 'trim|required|valid_email|min_length[5]|max_length[255]'
				),
				array(
					'field' => 'site_summary_title',
					'label' => lang('manager_site_settings_form_validation_site_summary_title'),
					'rules' => 'trim|max_length[255]'
				),
				array(
					'field' => 'site_summary_text',
					'label' => lang('manager_site_settings_form_validation_site_summary_text'),
					'rules' => 'trim|max_length[2048]'
				),
				array(
					'field' => 'featured_count',
					'label' => lang('manager_site_settings_form_validation_featured_count'),
					'rules' => 'trim|required|max_length[2]|numeric'
				),
				array(
					'field' => 'featured_minimum',
					'label' => lang('manager_site_settings_form_validation_featured_minimum'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'number_of_reviews_sidebar',
					'label' => lang('manager_site_settings_form_validation_number_of_reviews_sidebar'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'max_ads_home_sidebar',
					'label' => lang('manager_site_settings_form_validation_max_ads_home_sidebar'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'max_ads_review_sidebar',
					'label' => lang('manager_site_settings_form_validation_max_ads_review_sidebar'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'max_ads_article_sidebar',
					'label' => lang('manager_site_settings_form_validation_max_ads_article_sidebar'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'max_ads_search_sidebar',
					'label' => lang('manager_site_settings_form_validation_max_ads_search_sidebar'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'max_ads_custom_page_sidebar',
					'label' => lang('manager_site_settings_form_validation_max_ads_custom_page_sidebar'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'max_ads_home_lists',
					'label' => lang('manager_site_settings_form_validation_max_ads_home_lists'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'max_ads_articles_lists',
					'label' => lang('manager_site_settings_form_validation_max_ads_articles_lists'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'max_ads_results_lists',
					'label' => lang('manager_site_settings_form_validation_max_ads_results_lists'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_site_home',
					'label' => lang('manager_site_settings_form_validation_perpage_site_home'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_site_search',
					'label' => lang('manager_site_settings_form_validation_perpage_site_search'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_site_category',
					'label' => lang('manager_site_settings_form_validation_perpage_site_category'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_site_articles',
					'label' => lang('manager_site_settings_form_validation_perpage_site_articles'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_reviews',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_reviews'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_reviews_pending',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_reviews_pending'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_comments',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_comments'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_comments_pending',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_comments_pending'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_categories',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_categories'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_features',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_features'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_ratings',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_ratings'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_articles',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_articles'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_custom_pages',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_custom_pages'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_ads',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_ads'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				),
				array(
					'field' => 'perpage_manager_users',
					'label' => lang('manager_site_settings_form_validation_perpage_manager_users'),
					'rules' => 'trim|required|callback__more_than_zero|max_length[2]|numeric'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="label label-danger">', '</span>');
			$this->form_validation->set_rules($config);
			$this->form_validation->set_message('_more_than_zero', lang('manager_more_than_zero'));
			// validate the form data
			debug('validate form data');
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['message'] = lang('manager_site_settings_edited_message_fail');
				debug('loading "manager/site_settings/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/site_settings/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				debug('update settings');
				// update settings with form values
				$this->Setting_model->update_setting('site_name', $this->input->post('site_name'));
				$this->Setting_model->update_setting('site_email', $this->input->post('site_email'));
				$this->Setting_model->update_setting('site_summary_title', $this->input->post('site_summary_title'));
				$this->Setting_model->update_setting('site_summary_text', $this->input->post('site_summary_text'));
				$this->Setting_model->update_setting('debug', isset($_POST['debug']) ? 1 : 0);
				$this->Setting_model->update_setting('show_visitor_rating', isset($_POST['show_visitor_rating']) ? 1 : 0);
				$this->Setting_model->update_setting('captcha_verification', isset($_POST['captcha_verification']) ? 1 : 0);
				$this->Setting_model->update_setting('thumbnail_is_link', isset($_POST['thumbnail_is_link']) ? 1 : 0);
				$this->Setting_model->update_setting('featured_section_home', isset($_POST['featured_section_home']) ? 1 : 0);
				$this->Setting_model->update_setting('featured_section_review', isset($_POST['featured_section_review']) ? 1 : 0);
				$this->Setting_model->update_setting('featured_section_article', isset($_POST['featured_section_article']) ? 1 : 0);
				$this->Setting_model->update_setting('featured_section_search', isset($_POST['featured_section_search']) ? 1 : 0);
				$this->Setting_model->update_setting('featured_count', $this->input->post('featured_count'));
				$this->Setting_model->update_setting('featured_minimum', $this->input->post('featured_minimum'));
				$this->Setting_model->update_setting('search_sidebar', isset($_POST['search_sidebar']) ? 1 : 0);
				$this->Setting_model->update_setting('recent_review_sidebar', isset($_POST['recent_review_sidebar']) ? 1 : 0);
				$this->Setting_model->update_setting('number_of_reviews_sidebar', $this->input->post('number_of_reviews_sidebar'));
				$this->Setting_model->update_setting('tag_cloud_sidebar', isset($_POST['tag_cloud_sidebar']) ? 1 : 0);
				$this->Setting_model->update_setting('categories_sidebar', isset($_POST['categories_sidebar']) ? 1 : 0);
				$this->Setting_model->update_setting('max_ads_home_sidebar', $this->input->post('max_ads_home_sidebar'));
				$this->Setting_model->update_setting('max_ads_review_sidebar', $this->input->post('max_ads_review_sidebar'));
				$this->Setting_model->update_setting('max_ads_article_sidebar', $this->input->post('max_ads_article_sidebar'));
				$this->Setting_model->update_setting('max_ads_search_sidebar', $this->input->post('max_ads_search_sidebar'));
				$this->Setting_model->update_setting('max_ads_custom_page_sidebar', $this->input->post('max_ads_custom_page_sidebar'));
				$this->Setting_model->update_setting('max_ads_home_lists', $this->input->post('max_ads_home_lists'));
				$this->Setting_model->update_setting('max_ads_articles_lists', $this->input->post('max_ads_articles_lists'));
				$this->Setting_model->update_setting('max_ads_results_lists', $this->input->post('max_ads_results_lists'));
				$this->Setting_model->update_setting('review_approval', isset($_POST['review_approval']) ? 1 : 0);
				$this->Setting_model->update_setting('review_auto', isset($_POST['review_auto']) ? 1 : 0);
				$this->Setting_model->update_setting('comment_approval', isset($_POST['comment_approval']) ? 1 : 0);
				$this->Setting_model->update_setting('comment_auto', isset($_POST['comment_auto']) ? 1 : 0);
				$this->Setting_model->update_setting('perpage_site_home', $this->input->post('perpage_site_home'));
				$this->Setting_model->update_setting('perpage_site_search', $this->input->post('perpage_site_search'));
				$this->Setting_model->update_setting('perpage_site_category', $this->input->post('perpage_site_category'));
				$this->Setting_model->update_setting('perpage_site_articles', $this->input->post('perpage_site_articles'));
				$this->Setting_model->update_setting('perpage_manager_reviews', $this->input->post('perpage_manager_reviews'));
				$this->Setting_model->update_setting('perpage_manager_reviews_pending', $this->input->post('perpage_manager_reviews_pending'));
				$this->Setting_model->update_setting('perpage_manager_comments', $this->input->post('perpage_manager_comments'));
				$this->Setting_model->update_setting('perpage_manager_comments_pending', $this->input->post('perpage_manager_comments_pending'));
				$this->Setting_model->update_setting('perpage_manager_categories', $this->input->post('perpage_manager_categories'));
				$this->Setting_model->update_setting('perpage_manager_features', $this->input->post('perpage_manager_features'));
				$this->Setting_model->update_setting('perpage_manager_ratings', $this->input->post('perpage_manager_ratings'));
				$this->Setting_model->update_setting('perpage_manager_articles', $this->input->post('perpage_manager_articles'));
				$this->Setting_model->update_setting('perpage_manager_custom_pages', $this->input->post('perpage_manager_custom_pages'));
				$this->Setting_model->update_setting('perpage_manager_ads', $this->input->post('perpage_manager_ads'));
				$this->Setting_model->update_setting('perpage_manager_users', $this->input->post('perpage_manager_users'));
				debug('reload the settings');
				// update setting array with updated values
				$this->setting = $this->Setting_model->get_every_setting();
				// prepare data to display in the view
				$data['site_name'] = $this->setting['site_name'];
				$data['site_email'] = $this->setting['site_email'];
				$data['site_summary_title'] = $this->setting['site_summary_title'];
				$data['site_summary_text'] = $this->setting['site_summary_text'];
				$data['debug'] = $this->setting['debug'] > 0 ? 'CHECKED' : '';
				$data['show_visitor_rating'] = $this->setting['show_visitor_rating'] > 0 ? 'CHECKED' : '';
				$data['captcha_verification'] = $this->setting['captcha_verification'] > 0 ? 'CHECKED' : '';
				$data['thumbnail_is_link'] = $this->setting['thumbnail_is_link'] > 0 ? 'CHECKED' : '';
				$data['featured_section_home'] = $this->setting['featured_section_home'] > 0 ? 'CHECKED' : '';
				$data['featured_section_review'] = $this->setting['featured_section_review'] > 0 ? 'CHECKED' : '';
				$data['featured_section_article'] = $this->setting['featured_section_article'] > 0 ? 'CHECKED' : '';
				$data['featured_section_search'] = $this->setting['featured_section_search'] > 0 ? 'CHECKED' : '';
				$data['featured_count'] = $this->setting['featured_count'];
				$data['featured_minimum'] = $this->setting['featured_minimum'];
				$data['search_sidebar'] = $this->setting['search_sidebar'] > 0 ? 'CHECKED' : '';
				$data['recent_review_sidebar'] = $this->setting['recent_review_sidebar'] > 0 ? 'CHECKED' : '';
				$data['number_of_reviews_sidebar'] = $this->setting['number_of_reviews_sidebar'];
				$data['tag_cloud_sidebar'] = $this->setting['tag_cloud_sidebar'] > 0 ? 'CHECKED' : '';
				$data['categories_sidebar'] = $this->setting['categories_sidebar'] > 0 ? 'CHECKED' : '';
				$data['max_ads_home_sidebar'] = $this->setting['max_ads_home_sidebar'];
				$data['max_ads_review_sidebar'] = $this->setting['max_ads_review_sidebar'];
				$data['max_ads_article_sidebar'] = $this->setting['max_ads_article_sidebar'];
				$data['max_ads_search_sidebar'] = $this->setting['max_ads_search_sidebar'];
				$data['max_ads_custom_page_sidebar'] = $this->setting['max_ads_custom_page_sidebar'];
				$data['max_ads_home_lists'] = $this->setting['max_ads_home_lists'];
				$data['max_ads_articles_lists'] = $this->setting['max_ads_articles_lists'];
				$data['max_ads_results_lists'] = $this->setting['max_ads_results_lists'];
				$data['review_approval'] = $this->setting['review_approval'] > 0 ? 'CHECKED' : '';
				$data['review_auto'] = $this->setting['review_auto'] > 0 ? 'CHECKED' : '';
				$data['comment_approval'] = $this->setting['comment_approval'] > 0 ? 'CHECKED' : '';
				$data['comment_auto'] = $this->setting['comment_auto'] > 0 ? 'CHECKED' : '';
				$data['perpage_site_home'] = $this->setting['perpage_site_home'];
				$data['perpage_site_search'] = $this->setting['perpage_site_search'];
				$data['perpage_site_category'] = $this->setting['perpage_site_category'];
				$data['perpage_site_articles'] = $this->setting['perpage_site_articles'];
				$data['perpage_manager_reviews'] = $this->setting['perpage_manager_reviews'];
				$data['perpage_manager_reviews_pending'] = $this->setting['perpage_manager_reviews_pending'];
				$data['perpage_manager_comments'] = $this->setting['perpage_manager_comments'];
				$data['perpage_manager_comments_pending'] = $this->setting['perpage_manager_comments_pending'];
				$data['perpage_manager_categories'] = $this->setting['perpage_manager_categories'];
				$data['perpage_manager_features'] = $this->setting['perpage_manager_features'];
				$data['perpage_manager_ratings'] = $this->setting['perpage_manager_ratings'];
				$data['perpage_manager_articles'] = $this->setting['perpage_manager_articles'];
				$data['perpage_manager_custom_pages'] = $this->setting['perpage_manager_custom_pages'];
				$data['perpage_manager_ads'] = $this->setting['perpage_manager_ads'];
				$data['perpage_manager_users'] = $this->setting['perpage_manager_users'];
				// reload the form
				$data['message'] = lang('manager_site_settings_edited_message');
				debug('loading "manager/site_settings/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/site_settings/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			// form not submitted so just show the form
			debug('form not submitted - loading "manager/site_settings/edit" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/site_settings/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
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

/* End of file site_settings.php */
/* Location: ./application/controllers/manager/site_settings.php */