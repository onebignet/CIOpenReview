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
 * Reviews listing controller class
 *
 * Displays a list of all reviews, and a list of pending reviews, paginated
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Reviews extends CI_Controller
{

	/*
	 * Articles controller class constructor
	 */

	function reviews()
	{
		parent::__construct();
		$this->load->model('Review_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function (default)
	 *
	 * display list of reviews paginated
	 */

	function index()
	{
		debug('manager/reviews page | index function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load a page of reviews into an array for displaying in the view
		$data['allreviews'] = $this->Review_model->get_all_reviews($this->setting['perpage_manager_reviews'], $this->uri->segment(4));
		if ($data['allreviews']) {
			debug('loaded reviews');
			// set up config data for pagination
			$config['base_url'] = base_url() . 'manager/reviews/index';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Review_model->count_reviews();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_manager_reviews'];
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// show the reviews page
			debug('loading "manager/reviews" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/reviews/reviews', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no data... show the 'no reviews' page
			debug('no reviews found - loading "manager/no_reviews" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/reviews/no_reviews', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

	function pending()
	{
		debug('manager/reviews page | pending function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load a page of pending reviews into an array for displaying in the view
		$data['pendingreviews'] = $this->Review_model->get_reviews_pending($this->setting['perpage_manager_reviews_pending'], $this->uri->segment(4));
		if ($data['pendingreviews']) {
			debug('loaded pending reviews');
			// set up config data for pagination
			$config['base_url'] = base_url() . 'manager/reviews/pending';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Review_model->count_reviews_pending();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_manager_reviews_pending'];
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// show the pending reviews page
			debug('loading "manager/reviews/pending" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/reviews/reviews_pending', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no data... show the 'no pending reviews' page
			debug('no pending review found - loading "manager/reviews/no_reviews_pending" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/reviews/no_reviews_pending', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

}

/* End of file reviews.php */
/* Location: ./application/controllers/manager/reviews.php */