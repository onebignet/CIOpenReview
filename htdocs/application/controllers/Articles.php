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
 * @license        CIOpenReview is free software licensed under the GNU General Public License version 2 - This file is part of CIOpenReview - free software licensed under the GNU General Public License version 2
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
 * Articles controller class
 *
 * Displays a list of articles
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
 */
class Articles extends CI_Controller
{

	/*
	 * Articles controller class constructor
	 */

	function articles()
	{
		parent::__construct();
		$this->load->model('Article_model');
		$this->load->model('Ad_model');
		$this->load->model('Review_model');
		$this->load->model('Category_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function
	 *
	 * display a list of articles
	 */

	function index()
	{
		debug('articles page | index function');
		// set page_title, meta_keywords and meta_description
		$data['meta_description'] = lang('articles_meta_description') . ' - ' . $this->setting['site_name'] . ' - ' . strip_tags($this->setting['site_summary_title']) . ' - ' . strip_tags($this->setting['site_summary_text']);
		$data['meta_keywords'] = lang('articles_meta_keywords');
		$data['page_title'] = $this->setting['site_name'] . ' - ' . lang('article_page_title_articles');
		// load data for view
		$data['featured_count'] = $this->setting['featured_count'];
		$approval_required = $this->setting['review_approval'];
		$data['featured'] = $this->Review_model->get_featured_reviews($data['featured_count'], 0, $approval_required);
		$data['featured_minimum'] = $this->setting['featured_minimum'];
		$data['featured_reviews'] = $this->setting['featured_section_article'] == 1 ? count($data['featured']) : 0;
		$data['sidebar_ads'] = $this->Ad_model->get_ads($this->setting['max_ads_article_sidebar'], 3);
		$data['article_ads'] = $this->Ad_model->get_ads($this->setting['max_ads_articles_lists'], 2);
		$data['show_recent'] = $this->setting['recent_review_sidebar'];
		$data['show_search'] = $this->setting['search_sidebar'];
		$approval_required = $this->setting['review_approval'];
		if ($data['show_recent'] == 1) {
			$data['recent'] = $this->Review_model->get_latest_reviews($this->setting['number_of_reviews_sidebar'], 0, $approval_required);
		} else {
			$data['recent'] = FALSE;
		}
		$data['categories'] = $this->Category_model->get_all_categories(0);
		$data['show_categories'] = $this->setting['categories_sidebar'];
		$data['allarticles'] = $this->Article_model->get_all_articles($this->setting['perpage_site_articles'], $this->uri->segment(3));
		if ($data['allarticles']) {
			debug('loaded list of articles');
			// got list of articles
			if ($this->setting['tag_cloud_sidebar'] > 0) {
				//Prepare Tag Cloud
				$tagcloud = $this->Review_model->get_tag_cloud_array();
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
			$config['base_url'] = base_url() . 'articles/index';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Article_model->count_articles();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_site_articles'];
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
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// display the list of articles
			debug('loading "articles/article_content" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/articles/articles_content',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		} else {
			// no data so display 'articles/no_articles' view
			debug('no articles found');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/articles/no_articles',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		}
	}

}

/* End of file articles.php */
/* Location: ./application/controllers/articles.php */