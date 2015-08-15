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
 * Review controller class
 *
 * Displays a review
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
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
		$this->load->model('Review_feature_model');
		$this->load->model('Review_rating_model');
		$this->load->model('Category_model');
		$this->load->model('Comment_model');
		$this->load->model('Ad_model');
		$this->load->library('form_validation');
		$this->load->helper('captcha');
		$this->load->helper('Date');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * show function
	 *
	 * displays a review
	 */

	function show($requested_review_title)
	{
		debug('review page | show function');
		//load data for view
		$data['featured_count'] = $this->setting['featured_count'];
		$approval_required = $this->setting['review_approval'];
		$data['featured'] = $this->Review_model->getFeaturedReviews($data['featured_count'], 0, $approval_required);
		$data['featured_minimum'] = $this->setting['featured_minimum'];
		$data['featured_reviews'] = $this->setting['featured_section_review'] == 1 ? count($data['featured']) : 0;
		$data['show_search'] = $this->setting['search_sidebar'];
		$data['show_recent'] = $this->setting['recent_review_sidebar'];
		$data['show_visitor_rating'] = $this->setting['show_visitor_rating'];
		$data['captcha_verification'] = $this->setting['captcha_verification'];
		$approval_required = $this->setting['review_approval'];
		if ($data['show_recent'] == 1) {
			$data['recent'] = $this->Review_model->getLatestReviews($this->setting['number_of_reviews_sidebar'], 0, $approval_required);
		} else {
			$data['recent'] = FALSE;
		}
		// load the review
		$data['review'] = $this->Review_model->getReviewBySeoTitle($requested_review_title);
		// check if a manager level user is logged in
		$managerLoggedIn = $this->secure->isManagerLoggedIn($this->session);
		$data['sidebar_ads'] = $this->Ad_model->getAds($this->setting['max_ads_review_sidebar'], 3);
		$data['categories'] = $this->Category_model->getAllCategories(0);
		$data['show_categories'] = $this->setting['categories_sidebar'];
		if ($this->setting['tag_cloud_sidebar'] > 0) {
			// prepare Tag Cloud
			$data['tagcloud'] = $this->Review_model->getTagCloudArray();
			foreach ($data['tagcloud'] as $key => $value) {
				$tagcount[$key] = $value[0];
			}
			$data['cloudmax'] = max($tagcount);
			$data['cloudmin'] = min($tagcount);
		}
		$data['keywords'] = '';
		if ($data['review']) {
			debug('loaded review');
			// review exists
			if (($data['review']->approved > 0) OR ($managerLoggedIn) OR ($approval_required == 0)) {
				// the review has been approved OR the user is a manager level user current logged in
				debug('review is approved (or manager is logged in)');
				$data['ratings'] = $this->Review_rating_model->getReviewRatingsForReviewById($data['review']->id);
				$data['review_ads'] = $this->Ad_model->getAds(1, 2);
				$data['features'] = $this->Review_feature_model->getReviewFeaturesForReviewById($data['review']->id);
				$data['features_count'] = !$data['features'] ? 0 : count($data['features']);
				$data['lightbox'] = $this->setting['thumbnail_is_link'] == 0;
				$data['social_bookmarks'] = // create the Social bookmark bar
				$data['keywords'] = '';
				// if this review is not approved but the user is a manager
				// show a message warning the manager that the review is not visible to visitors yet
				$data['message'] = (($managerLoggedIn) && ($data['review']->approved == 0) && ($approval_required == 1)) ? lang('review_visible_because_manager') : '';
				// set page title, meta keywords and description
				$data['page_title'] = $this->setting['site_name'] . ' - ' . lang('page_title_review') . ' - ' . $data['review']->title;
				if (trim($data['review']->meta_keywords) !== '') {
					$data['meta_keywords'] = trim($data['review']->meta_keywords);
				} else {
					$data['meta_keywords'] = str_replace('"', '', $data['review']->tags);
				}
				if (trim($data['review']->meta_description) !== '') {
					$data['meta_description'] = $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . trim($data['review']->meta_description);
				} else {
					$data['meta_description'] = $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . str_replace('"', '', character_limiter(strip_tags($data['review']->description, 155)));
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
				// count the 'page view' for this review
				$this->Review_model->addView($data['review']->id);
				// show the review page
				debug('loading "review/review_content" view');
				$sections = array(
					'content' => 'site/' . $this->setting['current_theme'] . '/template/review/review_content',
					'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
				);
				$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
			} else {
				// review is not approved and the user is not a manager
				debug('review is not approved and user is not a manager');
				$data['title'] = $data['review']->title;
				$data['page_title'] = $this->setting['site_name'] . ' - Review - ' . $data['review']->title;
				$data['meta_keywords'] = str_replace('"', '', $this->setting['site_name']);
				$data['meta_description'] = str_replace('"', '', $data['review']->title);
				// show the review pending page
				debug('loading "review/review_pending" view');
				$sections = array(
					'content' => 'site/' . $this->setting['current_theme'] . '/template/review/review_pending',
					'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
				);
				$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
			}
		} else {
			// review not found so show not found page
			debug('review not found - show 404 not found page');
//	    show_404();
		}
	}

	/*
	 * display_comments function
	 *
	 * displays comments for a review
	 * this is used with jquery in the review_content view
	 */

	function display_comments($id)
	{
		debug('review page | display_comments function');
		// check if comment approval is required
		if ($this->setting['comment_approval'] == 1) {
			// load only approved comments for the review
			$data['comments'] = $this->Comment_model->getApprovedCommentsForReviewById($id);
		} else {
			// load all comments for the review
			$data['comments'] = $this->Comment_model->getCommentsForReviewById($id);
		}
		$data['show_visitor_rating'] = $this->setting['show_visitor_rating'];
		$data['comments_count'] = !$data['comments'] ? 0 : count($data['comments']);
		$data['visitor_rating_image'] = $this->Comment_model->GetVisitorRatingForReviewById($id);
		// show the review comments
		debug('loading "review/review_comments" view');
		$this->load->view('site/' . $this->setting['current_theme'] . '/template/review/review_comments', $data);
	}

	/*
	 * comment_submit function
	 *
	 * displays comments for a review
	 * this is used with jquery in the review_content view
	 */

	function comment_submit($id)
	{
		debug('review page | comment_submit function');
		// prepare data for
		$comment->quotation = '';
		$comment->source = '';
		$comment->site_link = '';
		$data['comment'] = $comment;
		// check review id has been provided
		if ($id) {
			// load the review
			$review = $this->Review_model->getReviewById($id);
			if ($review) {
				// review exists
				if ($this->input->post('comment_submitted') == 1) {
					debug('comment submitted');
					// form data submitted
					$data['show_visitor_rating'] = $this->setting['show_visitor_rating'];
					$quotation = trim($this->input->post('comment'));
					$source = trim($this->input->post('name'));
					$rating = $this->input->post('rating');
					$captcha = trim($this->input->post('captcha'));
					$captcha_required = $this->setting['captcha_verification'];
					$auto_approve = $this->setting['comment_auto'];
					$comment_approval = $this->setting['comment_approval'];
					$approval_status = ($comment_approval == 0) OR ($auto_approve == 1) ? 1 : 0;
					if (($quotation !== '') && ($source !== '') && (($captcha !== '') OR ($captcha_required == 0))) {
						if (!$this->Comment_model->doesCommentExist($quotation, $source)) {
							// comment is not a duplicate... prevents user submitting the same comment more than once
							if ($captcha_required == 1) {
								debug('check captcha');
								// comments form is using captcha
								// set captcha data to expire after 5 minutes
								$expiration = time() - 300;
								// delete 'old' captcha data -  if we didn't do this the captcha table would keep growing
								$this->db->query('DELETE FROM captcha WHERE captcha_time < ' . $expiration);
								//
								$sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
								$binds = array(
									$captcha,
									$this->input->ip_address(),
									$expiration
								);
								$query = $this->db->query($sql, $binds);
								$row = $query->row();
								$captcha_success = $row->count > 0;
								if ($row->count > 0) {
									// captcha code was entered successfully
									// add the comment
									$addComment = $this->Comment_model->addComment($id, $quotation, $source, '', $approval_status, $rating);
									if ($approval_status == 0) {
										// if not approved tell user their comment will appear when manager has approved it
										debug('comment not approved yet - display message');
										$data['message'] = lang('review_comment_submitted_pending');
									} else {
										// comment is approved... show message to user
										debug('comment approved - display message');
										$data['message'] = lang('review_comment_submitted_success');
									}
									// delete this captcha item from the database
									$this->db->query("DELETE FROM captcha WHERE word = '" . $captcha . "'");
									// hide the form
									$this->load->view('site/' . $this->setting['current_theme'] . '/template/review/hide_form', $data);
								} else {
									// user failed to enter correct captcha code
									$data['message'] = lang('review_comment_captcha_fail');
								}
							} else {
								// captcha not required so just add the comment
								debug('captcha not required');
								$addComment = $this->Comment_model->addComment($id, $quotation, $source, '', $approval_status, $rating);
								if ($approval_status == 0) {
									// if not approved tell user their comment will appear when manager has approved it
									debug('comment not approved yet - display message');
									$data['message'] = lang('review_comment_submitted_pending');
								} else {
									// comment is approved... show message to user
									debug('comment approved - display message');
									$data['message'] = lang('review_comment_submitted_success');
								}
								// hide the form
								$this->load->view('site/' . $this->setting['current_theme'] . '/template/review/hide_form', $data);
							}
						} else {
							// exact same comment already posted by same user... display a message
							debug('same comment already posted by same user - display message');
							$data['message'] = lang('review_comment_exists_fail');
						}
					} else {
						// form not completed... display a message
						debug('form not completed - display message');
						$data['message'] = lang('review_comment_incomplete_fail');
					}
				}
			}
		}
		// reload comments
		if ($this->setting['comment_approval'] == 1) {
			debug('reload approved comments');
			$data['comments'] = $this->Comment_model->getApprovedCommentsForReviewById($id);
		} else {
			debug('reload comments');
			$data['comments'] = $this->Comment_model->getCommentsForReviewById($id);
		}
		$data['comments_count'] = !$data['comments'] ? 0 : count($data['comments']);
		// get visitor rating for this review
		$data['visitor_rating_image'] = $this->Comment_model->GetVisitorRatingForReviewById($id);
		// show comments
		debug('show comments');
		$this->load->view('site/' . $this->setting['current_theme'] . '/template/review/review_comments', $data);
	}

}

/* End of file review.php */
/* Location: ./application/controllers/review.php */
