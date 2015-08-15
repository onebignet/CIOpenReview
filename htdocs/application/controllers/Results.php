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
 * Results controller class
 *
 * Displays a list of review search results or results from a category
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
 */
class Results extends CI_Controller
{

	/*
	 * Results controller class constructor
	 */

	function Results()
	{
		parent::__construct();
		$this->load->model('Review_model');
		$this->load->model('Category_model');
		$this->load->model('Ad_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * category function
	 *
	 * display a list of reviews in a category
	 */

	function category($category_seo_name)
	{
		debug('results page | category function');
		// set meta_keywords and meta_description
		$data['meta_keywords'] = lang('results_category_meta_keywords') . ',' . $category_seo_name;
		$data['meta_description'] = lang('results_category_meta_description') . ' ' . $category_seo_name;

		$data['meta_description'] = $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . lang('results_category_meta_description') . ' ' . $category_seo_name;

		// load data for view
		$data['search_index'] = 0;
		$data['featured_count'] = $this->setting['featured_count'];
		$approval_required = $this->setting['review_approval'];
		$data['featured'] = $this->Review_model->getFeaturedReviews($data['featured_count'], 0, $approval_required);
		$data['featured_minimum'] = $this->setting['featured_minimum'];
		$data['featured_reviews'] = $this->setting['featured_section_search'] == 1 ? count($data['featured']) : 0;
		$data['categories'] = $this->Category_model->getAllCategories(0);
		$data['show_search'] = $this->setting['search_sidebar'];
		$data['show_recent'] = $this->setting['recent_review_sidebar'];
		if ($data['show_recent'] == 1) {
			$data['recent'] = $this->Review_model->getLatestReviews($this->setting['number_of_reviews_sidebar'], 0, $approval_required);
		} else {
			$data['recent'] = FALSE;
		}
		$data['show_categories'] = $this->setting['categories_sidebar'];
		$data['sidebar_ads'] = $this->Ad_model->getAds($this->setting['max_ads_search_sidebar'], 3);
		$data['list_ads'] = $this->Ad_model->getAds($this->setting['max_ads_results_lists'], 1);
		if ($data['list_ads']) {
			foreach ($data['list_ads'] as $ad) {
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
		$approval_required = $this->setting['review_approval'];
		$data['results'] = $this->Review_model->getReviewsByCategorySeoName($category_seo_name, $this->setting['perpage_site_category'], $this->uri->segment(4), $approval_required);
		$data['keywords'] = '';
		// set page title
		$data['page_title'] = $this->setting['site_name'] . lang('results_title_in_this_category');
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
		$data['category_name'] = $this->Category_model->getNameFromSeoName($category_seo_name);
		if ($data['results']) {
			debug('category results loaded for category "' . $data['category_name'] . '"');
			// there are some results
			// set up config for pagination
			$config['base_url'] = base_url() . 'results/category/' . $category_seo_name;
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$approval_required = $this->setting['review_approval'];
			$total = $this->Review_model->countCategoryReviews($category_seo_name, $approval_required);
			$data['result_count'] = $total;
			$data['result_singular_plural'] = $data['result_count'] != 1 ? lang('results_count_plural') : lang('results_count_singular');
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_site_category'];
			$config['uri_segment'] = 4;

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
			debug('loading "results/category_results_content" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/results/category_results_content',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		} else {
			// no results
			debug('no results found');
			if ($data['category_name']) {
				// found category so show the 'results/category_no_results' page
				debug('loading "results/category_no_results" view');
				$sections = array(
					'content' => 'site/' . $this->setting['current_theme'] . '/template/results/category_no_results',
					'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
				);
				$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
			} else {
				// category not found so show the 'results/category_does_not_exist' page
				$data['category_seo_name'] = $category_seo_name;
				debug('category name "' . $category_seo_name . '" not found... loading "results/category_does_not_exist" view');
				$sections = array(
					'content' => 'site/' . $this->setting['current_theme'] . '/template/results/category_does_not_exist',
					'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
				);
				$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
			}
		}
	}

	/*
	 * search function
	 *
	 * display a list of reviews based on keyword search results
	 */

	function search()
	{
		debug('results page | search function');
		// load data for view
		$data['search_index'] = 0;
		$data['featured_count'] = $this->setting['featured_count'];
		$approval_required = $this->setting['review_approval'];
		$data['featured'] = $this->Review_model->getFeaturedReviews($data['featured_count'], 0, $approval_required);
		$data['featured_minimum'] = $this->setting['featured_minimum'];
		$data['featured_reviews'] = $this->setting['featured_section_search'] == 1 ? count($data['featured']) : 0;
		if (trim($this->input->post('keyword')) !== '') {
			$this->session->set_userdata('keyword', trim($this->input->post('keyword')));
		}
		$keyword = $this->session->userdata('keyword');
		// set meta_keywords and meta_description
		$data['meta_keywords'] = lang('results_search_meta_keywords') . ',' . $keyword;
		$data['meta_description'] = $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . lang('results_search_meta_description') . ' ' . $keyword;
		$data['sidebar_ads'] = $this->Ad_model->getAds($this->setting['max_ads_search_sidebar'], 3);
		$data['list_ads'] = $this->Ad_model->getAds($this->setting['max_ads_results_lists'], 1);
		if ($data['list_ads']) {
			foreach ($data['list_ads'] as $ad) {
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
		$approval_required = $this->setting['review_approval'];
		$data['results'] = $this->Review_model->getReviewsByKeyword($keyword, $this->setting['perpage_site_search'], $this->uri->segment(4), $approval_required);
		$data['keywords'] = $keyword;
		$data['page_title'] = $this->setting['site_name'] . lang('results_title_search');
		if ($data['results']) {
			debug('search results loaded for keyword "' . $data['keywords'] . '"');
			// there are some results
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
			$approval_required = $this->setting['review_approval'];
			$total = $this->Review_model->countKeywordReviews($keyword, $approval_required);
			$data['result_count'] = $total;
			$data['result_singular_plural'] = $data['result_count'] != 1 ? lang('results_count_plural') : lang('results_count_singular');
			// set up config for pagination
			$config['base_url'] = base_url() . 'results/search/' . $keyword;
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_site_search'];
			$config['uri_segment'] = 4;

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
			// show a page of search results
			debug('loading "results/search_results_content" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/results/search_results_content',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		} else {
			// no results so show 'results/search_no_results' page
			debug('no results found');
			debug('loading "results/search_no_results" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/results/search_no_results',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			if (trim($keyword) !== '') {
				$data['message'] = lang('results_empty_2_for') . '"' . $keyword . '"';
			} else {
				$data['message'] = lang('results_empty_2_no_keywords');
			}
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		}
	}

}

/* End of file results.php */
/* Location: ./application/controllers/results.php */