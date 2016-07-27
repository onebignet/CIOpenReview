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
 * Home controller class
 *
 * Displays the manager home page
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Home extends CI_Controller
{

	/*
	 * Home controller class constructor
	 */

	function home()
	{
		parent::__construct();
		$this->load->model('Review_model');
		$this->load->model('Comment_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function (default)
	 *
	 * display manager home page
	 */

	function index()
	{
		debug('manager/home page | index function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load data for information on home page
		$data['reviews_to_approve'] = $this->setting['review_approval'] == 1 ? $this->Review_model->count_reviews_pending() : 0;
		$data['comments_to_approve'] = $this->setting['comment_approval'] == 1 ? $this->Comment_model->count_comments_pending() : 0;
		$data['action_required'] = ($data['reviews_to_approve'] OR $data['comments_to_approve']) ? TRUE : FALSE;
		$data['topreviews'] = $this->Review_model->most_viewed();
		$data['topclicks'] = $this->Review_model->most_clicks();

		//Manager Page header data
		$data['page_header'] = lang('open_review_script_manager');
		$data['page_description'] = lang('open_review_script_manager');
		$data['current_page'] = lang('manager_menu_home');

		debug('loaded data for home page');
		// display home page
		debug('loading "manager/home" view');
		$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/home/home', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
		$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
	}

}

/* End of file home.php */
/* Location: ./application/controllers/manager/home.php */