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
 * Home controller class
 *
 * Displays the site home page
 * This is the default controller
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
 */
class Home extends CI_Controller
{

	/*
	 * Home controller class constructor
	 */

	function Home()
	{
		parent::__construct();

		//define('VIEW_EXT', '.tpl');

		$this->load->model('Review_model');
		$this->load->model('Category_model');
		$this->load->model('Ad_model');
		$this->load->model('Comment_model');
		$this->load->helper('directory');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * index function
	 *
	 * displays the site home page
	 */

	function index()
	{
		debug('home page | index function');
		// load data for view
		$data['meta_description'] = lang('home_meta_description') . ' - ' . $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . strip_tags($this->setting['site_summary_text']);
		$data['meta_keywords'] = lang('home_meta_keywords');
		$data['featured_count'] = $this->setting['featured_count'];
		$approval_required = $this->setting['review_approval'];
		$data['featured'] = $this->Review_model->getFeaturedReviews($data['featured_count'], 0, $approval_required);
		$data['featured_minimum'] = $this->setting['featured_minimum'];
		$data['featured_reviews'] = $this->setting['featured_section_home'] == 1 ? count($data['featured']) : 0;
		$data['latest'] = $this->Review_model->getLatestReviews($this->setting['perpage_site_home'], $this->uri->segment(3));
		if ($data['latest']) {
			foreach ($data['latest'] as $key => $review) {
				$data['latest'][$key]->rating_image = $this->Comment_model->GetVisitorRatingForReviewById($data['latest'][$key]->id);
			}
		}
		$data['categories'] = $this->Category_model->getAllCategories(0);
		$data['show_categories'] = $this->setting['categories_sidebar'];
		$data['show_search'] = $this->setting['search_sidebar'];
		$data['show_recent'] = $this->setting['recent_review_sidebar'];
		$approval_required = $this->setting['review_approval'];
		if ($data['show_recent'] == 1) {
			$data['recent'] = $this->Review_model->getLatestReviews($this->setting['number_of_reviews_sidebar'], 0, $approval_required);
		} else {
			$data['recent'] = FALSE;
		}
		$data['site_summary_title'] = $this->setting['site_summary_title'];
		$data['site_summary_text'] = $this->setting['site_summary_text'];
		$data['sidebar_ads'] = $this->Ad_model->getAds($this->setting['max_ads_home_sidebar'], 3);
		$data['list_ads'] = $this->Ad_model->getAds($this->setting['max_ads_home_lists'], 1);
		if ($data['sidebar_ads']) {
			foreach ($data['sidebar_ads'] as $ad) {
				if (trim($ad->local_image_name !== '')) {
					$ad->image = '<img src="' . base_url() . 'uploads/ads/images/' . $ad->local_image_name . '" width="' . (($ad->image_width > 0) ? $ad->image_width : 100) . '" height="' . (($ad->image_height > 0) ? $ad->image_height : 80) . '"/>';
				} else {
					if (trim($ad->remote_image_url !== '')) {
						$ad->image = '<img src="' . $ad->remote_image_url . '" width="' . (($ad->image_width > 0) ? $ad->image_width : 100) . '" height="' . (($ad->image_height > 0) ? $ad->image_height : 80) . '"/>';
					} else {
						$ad->image = '';
					}
				}
			}
		}
		$data['keywords'] = '';
		$data['page_title'] = lang('title_welcome_to') . $this->setting['site_name'];
		if ($data['latest']) {
			debug('loaded latest reviews');
			// there is at least one review to display in the latest section
			if ($this->setting['tag_cloud_sidebar'] > 0) {
				//Prepare Tag Cloud
				$tagcloud = $this->Review_model->getTagCloudArray();
				if ($tagcloud !== FALSE) {
					$data['tagcloud'] = $tagcloud;
					foreach ($data['tagcloud'] as $key => $value) {
						$tagcount[$key] = $value[0];
					}
					$data['cloudmax'] = max($tagcount);
					$data['cloudmin'] = min($tagcount);
				}
			}
			// set up config for pagination
			$config['base_url'] = base_url() . 'home/index';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Review_model->countReviews();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_site_home'];
			$config['uri_segment'] = 3;

			$config['full_tag_open'] = '<ul class="pagination pagination-sm pagination-centered">';
			$config['full_tag_close'] = '</ul><!--pagination-->';

			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';

			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';

			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';

			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';

			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';

			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';

			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			// show the home page with a page of the latest reviews in a list
			debug('loading "home/home_content" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/home/home_content',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		} else {
			// there are no reviews to display
			debug('no reviews found');
			$data[] = '';
			debug('loading "home/home_content" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/home/home_content',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		}
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */