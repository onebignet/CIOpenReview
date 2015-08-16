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
 * Article controller class
 *
 * Displays an article
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://ciopenreview.com
 */
class Article extends CI_Controller
{

	/*
	 * Article controller class constructor
	 */

	function article()
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
	 * show function
	 *
	 * display an article
	 */

	function show($requested_article_title)
	{
		// load data for view
		debug('article page | show function');
		$data['article'] = $this->Article_model->get_article_by_seo_title($requested_article_title);
		$data['featured_count'] = $this->setting['featured_count'];
		$approval_required = $this->setting['review_approval'];
		$data['featured'] = $this->Review_model->get_featured_reviews($data['featured_count'], 0, $approval_required);
		$data['featured_minimum'] = $this->setting['featured_minimum'];
		$data['featured_reviews'] = $this->setting['featured_section_article'] == 1 ? count($data['featured']) : 0;
		$data['sidebar_ads'] = $this->Ad_model->get_ads($this->setting['max_ads_article_sidebar'], 3);
		$data['article_ads'] = $this->Ad_model->get_ads(1, 2);
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
		if ($data['article']) {
			debug('found article with title "' . $requested_article_title . '"');
			// article exists
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
			// set page_title, meta_keywords and meta_description
			$data['page_title'] = $this->setting['site_name'] . ' - ' . lang('article_page_title_article') . ' - ' . $data['article']->title;
			if (trim($data['article']->meta_keywords) !== '') {
				$data['meta_keywords'] = trim($data['article']->meta_keywords);
			} else {
				$data['meta_keywords'] = str_replace('"', '', $data['article']->title);
			}
			if (trim($data['article']->meta_description) !== '') {
				$data['meta_description'] = trim($data['article']->meta_description);
			} else {
				$data['meta_description'] = str_replace('"', '', character_limiter(strip_tags($data['article']->description, 155)));
			}
			// display the article page
			debug('loading the "article/article_content" view');
			$sections = array(
				'content' => 'site/' . $this->setting['current_theme'] . '/template/article/article_content',
				'sidebar' => 'site/' . $this->setting['current_theme'] . '/template/sidebar'
			);
			$this->template->load('site/' . $this->setting['current_theme'] . '/template/template', $sections, $data);
		} else {
			// article does not exist so show 404 not found page
			debug('article with title "' . $requested_article_title . '" not found');
			show_404();
		}
	}

}

/* End of file article.php */
/* Location: ./application/controllers/article.php */