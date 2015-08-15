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
 * Recommends controller class
 *
 * Allows use of seo friendly links that hide the affiliate link
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
 */
class Recommends extends CI_Controller
{

	/*
	 * Recommends controller class constructor
	 */

	function Recommends()
	{
		parent::__construct();
		$this->load->model('Review_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * this function
	 *
	 * get the review and redirect to the external link
	 */

	function this($seo_title)
	{
		debug('recommends page | this function');
		// load the review
		$review = $this->Review_model->getReviewBySeoTitle($seo_title);
		// get the link to redirect to
		$link = $review->link;
		$this->Review_model->addClick($review->id);
		if ($link !== '') {
			// redirect to link
			debug('got link... redirecting to "' . $link . '"');
			redirect($link, 'location', '301');
		} else {
			// if there is no link, redirect to home page of site
			debug('no link... redirecting to home page');
			redirect(base_url(), 'location', '301');
		}
	}

}

/* End of file recommends.php */
/* Location: ./application/controllers/recommends.php */