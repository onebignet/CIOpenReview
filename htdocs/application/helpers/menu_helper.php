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
 * @link        http://CIOpenReview.com
 */
// ------------------------------------------------------------------------
//
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
function pending_comments_count()
{
	// return number of pending comments
	// used in manager menu
	$CI = &get_instance();
	$CI->load->model('Setting_model');
	if ($CI->Setting_model->getSettingByName('comment_approval') == 1) {
		$CI->load->model('Comment_model');
		return $CI->Comment_model->countCommentsPending();
	} else
		return 0;
}

function pending_reviews_count()
{
	// return number of pending reviews
	// used in manager menu
	$CI = &get_instance();
	$CI->load->model('Setting_model');
	if ($CI->Setting_model->getSettingByName('review_approval') == 1) {
		$CI->load->model('Review_model');
		return $CI->Review_model->countReviewsPending();
	} else
		return 0;
}

/* End of file menu_helper.php */
/* Location: ./application/helpers/menu_helper.php */