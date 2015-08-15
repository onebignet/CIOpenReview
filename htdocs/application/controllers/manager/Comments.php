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
 * Comments listing controller class
 *
 * Displays a list of comments for a particular review and a list of pending comments
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Comments extends CI_Controller
{

	/*
	 * Comments controller class constructor
	 */

	function Comments()
	{
		parent::__construct();
		$this->load->model('Comment_model');
		$this->load->model('Review_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * show function (default)
	 *
	 * display list of comments for a review
	 */

	function show($review_id)
	{
		debug('manager/comments page | show function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		if ($review_id) {
			$review = $this->Review_model->getReviewById($review_id);
			if ($review) {
				// load a page of comments into an array for displaying in the view
				$data['allcomments'] = $this->Comment_model->getCommentsForReviewById($review_id, $this->setting['perpage_manager_comments'], $this->uri->segment(5));
				if ($data['allcomments']) {
					debug('comments loaded');
					// set up config data for pagination
					$config['base_url'] = base_url() . 'manager/comments/show/' . $review_id;
					$config['next_link'] = lang('results_next');
					$config['prev_link'] = lang('results_previous');
					$total = $this->Comment_model->countCommentsForReviewById($review_id);
					$config['total_rows'] = $total;
					$config['per_page'] = $this->setting['perpage_manager_comments'];
					$config['uri_segment'] = 5;
					$this->pagination->initialize($config);
					$data['pagination'] = $this->pagination->create_links();
					if (trim($data['pagination'] === '')) {
						$data['pagination'] = '&nbsp;<strong>1</strong>';
					}
					$data['review'] = $review;
					// show the comments page
					debug('loading "manager/comments" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comments/comments', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				} else {
					// no data... show the 'no comments' page
					$data['review'] = $review;
					debug('loading "manager/no_comments" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comments/no_comments', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			} else {
				// no review data... redirect to manager home page
				debug('review not found - redirecting to "manager/home"');
				redirect('/manager/home', '301');
			}
		} else {
			// no review id in url... redirect to manager home page
			debug('review id not provided - redirecting to "manager/home"');
			redirect('/manager/home', '301');
		}
	}

	/*
	 * pending function
	 *
	 * display list of pending comments for all reviews
	 */

	function pending()
	{
		debug('manager/comments page | pending function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load a page of pending comments into an array for displaying in the view
		$data['pendingcomments'] = $this->Comment_model->getCommentsPending($this->setting['perpage_manager_comments_pending'], $this->uri->segment(5));
		if ($data['pendingcomments']) {
			debug('loaded pending comments');
// set up config data for pagination
			$config['base_url'] = base_url() . 'manager/comments/pending';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Comment_model->countCommentsPending();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_manager_comments_pending'];
			$config['uri_segment'] = 5;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// show the pending comments page
			debug('loading "manager/comments_pending" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comments/comments_pending', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no data... show the 'no pending comments' page
			debug('loading "manager/no_comments_pending" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comments/no_comments_pending', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

}

/* End of file comments.php */
/* Location: ./application/controllers/manager/comments.php */