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
 * Rss controller class
 *
 * Outputs RSS feed with latest reviews
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
 */
class Rss extends CI_Controller
{

	/*
	 * Rss controller class constructor
	 */

	function rss()
	{
		parent::__construct();
		$this->load->model('Review_model');
		$this->load->model('Article_model');
		$this->load->helper('xml');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function (default)
	 *
	 * output rss feed
	 */

	function index()
	{
		debug('rss page | index function');
		// get data for rss feed
		$data['encoding'] = 'utf-8';
		$data['title'] = $this->setting['site_name'];
		$data['link'] = base_url() . '/rss';
		$data['description'] = lang('rss_description');
		$data['language'] = 'en';
		$data['creator'] = $this->setting['site_name'];
		$data['owner'] = $this->setting['site_email'];
		$approval_required = $this->setting['review_approval'];
		$data['reviews'] = $this->Review_model->get_latest_reviews(200, 0, $approval_required);
		$data['articles'] = $this->Article_model->get_all_articles(200);
		header('Content-Type: application/rss+xml');
		debug('loading "rss/rss_template" view');
		$sections = array('rss_content' => 'site/' . $this->setting['current_theme'] . '/template/rss/rss');
		$this->template->load('site/' . $this->setting['current_theme'] . '/template/rss/rss_template', $sections, $data);
	}

}

/* End of file rss.php */
/* Location: ./application/controllers/rss.php */