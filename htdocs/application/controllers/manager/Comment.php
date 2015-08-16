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
 * Comment management controller class
 *
 * Add, edit, approve, un-approve or delete a comment
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Comment extends CI_Controller
{

	/*
	 * Comment controller class constructor
	 */

	function comment()
	{
		parent::__construct();
		$this->load->model('Comment_model');
		$this->load->model('Review_model');
		$this->load->library('form_validation');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * add function
	 *
	 * display 'comment/add' view, validate form data and add new comment to the database
	 */

	function add($review_id)
	{
		debug('manager/comment page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// create '$comment' variable for use in the view
		$comment->quotation = '';
		$comment->source = '';
		$data['comment'] = $comment;
		// check review exists
		if ($review_id) {
			$review = $this->Review_model->get_review_by_id($review_id);
			if ($review) {
				// check form data was submitted
				if ($this->input->post('comment_submit')) {
					debug('form was submitted');
					// set up form validation config
					$config = array(
						array(
							'field' => 'quotation',
							'label' => lang('manager_comment_form_validation_quotation'),
							'rules' => 'trim|required|min_length[2]|max_length[512]'
						),
						array(
							'field' => 'source',
							'label' => lang('manager_comment_form_validation_source'),
							'rules' => 'trim|required|min_length[2]|max_length[512]'
						)
					);
					$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
					$this->form_validation->set_rules($config);
					// validate the form data
					if ($this->form_validation->run() === FALSE) {
						debug('form validation failed');
						// validation failed - reload page with error message(s)
						$data['review'] = $review;
						debug('loading "manager/comment/add" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comment/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					} else {
						debug('validation successful');
						// validation successful
						// prepare data for adding to database
						$quotation = $this->input->post('quotation');
						$source = $this->input->post('source');
						$approved = isset($_POST['approved']) ? 1 : 0;
						// add the comment
						debug('add the comment');
						$add_comment = $this->Comment_model->add_comment($review_id, $quotation, $source, '', $approved, 0);
						$data['review'] = $review;
						$data['message'] = lang('manager_comment_add_success');
						// clear form validation data
						$this->form_validation->clear_fields();
						// display the form
						debug('loading "manager/comment/add" view');
						$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comment/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
						$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
					}
				} else {
					// form not submitted so just show the form
					$data['review'] = $review;
					debug('form not submitted - loading "manager/comment/add" view');
					$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comment/add', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
					$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
				}
			} else {
				// review does not exist so redirect to manager home page
				debug('review not found - redirecting to "manager/home"');
				redirect('/manager/home', '301');
			}
		} else {
			// no review provided so redirect to manager home page
			debug('review id not provided - redirecting to "manager/home"');
			redirect('/manager/home', '301');
		}
	}

	/*
	 * edit function
	 *
	 * display 'comment/edit' view, validate form data and modify category
	 */

	function edit($id)
	{
		debug('manager/comment page | add function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// create '$comment' variable for use in the view
		$data['comment'] = $this->Comment_model->get_comment_by_id($id);
		if ($data['comment']) {
			// get the review for this comment
			$data['review'] = $this->Review_model->get_review_for_comment_id($id);
			// if the comment is approved prepare 'checked' variable
			if ($data['comment']->approved > 0) {
				$data['checked'] = 'CHECKED';
			} else {
				$data['checked'] = '';
			}
		}
		// check the form was submitted
		if ($this->input->post('comment_submit')) {
			debug('form was submitted');
			// set up form validation config
			$config = array(
				array(
					'field' => 'quotation',
					'label' => lang('manager_comment_form_validation_quotation'),
					'rules' => 'trim|required|min_length[2]|max_length[512]'
				),
				array(
					'field' => 'source',
					'label' => lang('manager_comment_form_validation_source'),
					'rules' => 'trim|required|min_length[2]|max_length[512]'
				)
			);
			$this->form_validation->set_error_delimiters('<br><span class="error">', '</span>');
			$this->form_validation->set_rules($config);
			// validate the form data
			if ($this->form_validation->run() === FALSE) {
				debug('form validation failed');
				// validation failed - reload page with error message(s)
				$data['comment'] = $this->Comment_model->get_comment_by_id($id);
				$data['review'] = $this->Review_model->get_review_for_comment_id($id);
				debug('loading "manager/comment/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comment/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('validation successful');
				// validation successful
				// prepare data for updating the database
				$quotation = $this->input->post('quotation');
				$source = $this->input->post('source');
				$approved = isset($_POST['approved']) ? 1 : 0;
				// update the comment
				debug('update the comment');
				$update_comment = $this->Comment_model->update_comment($id, $quotation, $source, '', $approved);
				$data['review'] = $this->Review_model->get_review_for_comment_id($id);
				debug('loading "manager/comment/edited" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comment/edited', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			}
		} else {
			debug('form not submitted');
			// form not submitted so just show the form
			if ($data['comment']) {
				debug('loading "manager/comment/edit" view');
				$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comment/edit', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
				$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
			} else {
				debug('comment not found - redirecting to "manager/home"');
				// no data for comment so redirect to manager home page
				redirect('/manager/home', 301);
			}
		}
	}

	/*
	 * deleted function
	 *
	 * delete a comment and display 'comment/deleted' view
	 */

	function deleted($id)
	{
		debug('manager/comment page | deleted function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the comment from the database
		$data['comment'] = $this->Comment_model->get_comment_by_id($id);
		if ($data['comment']) {
			debug('loaded comment');
			// comment exists, find the review for this comment
			$data['review'] = $this->Review_model->get_review_for_comment_id($id);
			// delete the comment
			debug('delete comment');
			$this->Comment_model->delete_comment_by_id($id);
			// show the 'comment/deleted' page
			debug('loading "manager/comment/deleted" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/comment/deleted', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no comment data so redirect to reviews list
			debug('comment not found - redirecting to "manager/reviews"');
			redirect('/manager/reviews');
		}
	}

	/*
	 * approve function
	 *
	 * approve a comment and display 'comments/show' view
	 */

	function approve($comment_id)
	{
		debug('manager/comment page | approve function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the comment from the database
		$comment = $this->Comment_model->get_comment_by_id($comment_id);
		if ($comment) {
			debug('loaded the comment');
			// approve the comment
			debug('approve the comment');
			$this->Comment_model->comment_approval($comment_id, 1);
			// get the review for this comment
			$review = $this->Review_model->get_review_for_comment_id($comment_id);
			if ($review) {
				// redirect to list of comments for this review
				debug('redirecting to "manager/comments/show"');
				redirect('/manager/comments/show/' . $review->id, '301');
			}
		}
		// no comment data so redirect to reviews list
		debug('comment not found - redirecting to "manager/reviews"');
		redirect('/manager/reviews');
	}

	/*
	 * unapprove function
	 *
	 * 'unapprove' a comment and display 'comments/show' view
	 */

	function unapprove($comment_id)
	{
		debug('manager/comment page | unapprove function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the comment from the database
		$comment = $this->Comment_model->get_comment_by_id($comment_id);
		if ($comment) {
			debug('loaded the comment');
			// unapprove the comment
			debug('unapprove the comment');
			$this->Comment_model->comment_approval($comment_id, 0);
			// get the review for this comment
			$review = $this->Review_model->get_review_for_comment_id($comment_id);
			if ($review) {
				// redirect to comments list for this review
				debug('redirecting to "manager/comments/show"');
				redirect('/manager/comments/show/' . $review->id, '301');
			}
		}
		// no comment data so redirect to reviews list
		debug('comment not found - redirecting to "manager/reviews"');
		redirect('/manager/reviews');
	}

	/*
	 * approve_pending function
	 *
	 * approve a comment and display 'comments/pending' view
	 */

	function approve_pending($comment_id)
	{
		debug('manager/comment page | approve_pending function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		$comment = $this->Comment_model->get_comment_by_id($comment_id);
		if ($comment) {
			// approve the comment
			debug('approve the comment');
			$this->Comment_model->comment_approval($comment_id, 1);
		}
		// redirect to comments pending list
		debug('redirecting to "manager/comments/pending"');
		redirect('/manager/comments/pending', '301');
	}

	/*
	 * delete_pending function
	 *
	 * delete a comment and display 'comments/pending' view
	 */

	function delete_pending($id)
	{
		debug('manager/comment page | delete_pending function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load the comment from the database
		$comment = $this->Comment_model->get_comment_by_id($id);
		if ($comment) {
			// delete the comment
			debug('delete the comment');
			$this->Comment_model->delete_comment_by_id($id);
		}
		// redirect to comments pending list
		debug('redirecting to "manager/comments/pending"');
		redirect('/manager/comments/pending', '301');
	}

}

/* End of file comment.php */
/* Location: ./application/controllers/manager/comment.php */