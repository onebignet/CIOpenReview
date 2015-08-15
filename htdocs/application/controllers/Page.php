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
 * Page controller class
 *
 * Displays a custom page
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
 */
class Page extends CI_Controller
{

	/*
	 * Pages controller class constructor
	 */

	function Page()
	{
		parent::__construct();
		$this->load->model('Page_model');
		$this->load->model('Ad_model');
		$this->load->model('Review_model');
		$this->load->model('Category_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * show function
	 *
	 * display a custom page
	 */

	function show($requested_page_name)
	{
		debug('custom page page | show function');
		// load data for view
		$data['sidebar_ads'] = $this->Ad_model->getAds($this->setting['max_ads_custom_page_sidebar'], 3);
		$data['page_ads'] = $this->Ad_model->getAds(1, 2);
		$data['show_search'] = $this->setting['search_sidebar'];
		$data['show_recent'] = $this->setting['recent_review_sidebar'];
		$approval_required = $this->setting['review_approval'];
		if ($data['show_recent'] == 1) {
			$data['recent'] = $this->Review_model->getLatestReviews($this->setting['number_of_reviews_sidebar'], 0, $approval_required);
		} else {
			$data['recent'] = FALSE;
		}
		$data['categories'] = $this->Category_model->getAllCategories(0);
		$data['show_categories'] = $this->setting['categories_sidebar'];
		$data['page'] = $this->Page_model->getPageBySeoName($requested_page_name);
		if ($data['page']) {
			debug('loaded page with name "' . $requested_page_name . '"');
			$data['page_title'] = $this->setting['site_name'] . ' - Page - ' . $data['page']->name;
			if (trim($data['page']->meta_keywords) !== '') {
				$data['meta_keywords'] = trim($data['page']->meta_keywords);
			} else {
				$data['meta_keywords'] = str_replace('"', '', $data['page']->name);
			}
			if (trim($data['page']->meta_description) !== '') {
				$data['meta_description'] = $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . trim($data['page']->meta_description);
			} else {
				$data['meta_description'] = $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . str_replace('"', '', character_limiter(strip_tags($data['page']->content, 155)));
			}
			// show the custom page
			debug('loading "page/page_content" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/page/page_content',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		} else {
			// page not found so show not found page
			debug('page not found - show 404 not found page');
			show_404();
		}
	}

}

/* End of file page.php */
/* Location: ./application/controllers/page.php */
