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
 * Sitemap controller class
 *
 * Creates a sitemap
 *
 * @package        CIOpenReview
 * @subpackage          site
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Sitemap extends CI_Controller
{

	/*
	 * Sitemap controller class constructor
	 */

	function Sitemap()
	{
		parent::__construct();
		$this->load->model('Review_model');
		$this->load->model('Article_model');
		$this->load->model('Page_model');
		$this->load->model('Category_model');
		$this->load->library('sitemaps');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	function index()
	{
		debug('sitemap page | index function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		$data[] = '';
		$approval_required = $this->setting['review_approval'];
		$reviews = $this->Review_model->getLatestReviews(0, 0, $approval_required);
		$articles = $this->Article_model->getAllArticles();
		$pages = $this->Page_model->getAllPages();
		$categories = $this->Category_model->getAllCategories();
		if ($reviews OR $articles) {
			foreach ($reviews AS $review) {
				$item = array(
					'loc' => site_url('review/show/' . $review->seo_title),
					'lastmod' => date('c', strtotime($review->last_modified)),
					'changefreq' => 'hourly',
					'priority' => '0.8'
				);
				$this->sitemaps->add_item($item);
			}
			foreach ($articles AS $article) {
				$item = array(
					'loc' => site_url('article/show/' . $article->seo_title),
					'lastmod' => date('c', strtotime($article->last_modified)),
					'changefreq' => 'hourly',
					'priority' => '0.8'
				);
				$this->sitemaps->add_item($item);
			}
			foreach ($pages AS $page) {
				$item = array(
					'loc' => site_url('page/show/' . $page->seo_name),
					'lastmod' => date('c', strtotime($page->last_modified)),
					'changefreq' => 'hourly',
					'priority' => '0.8'
				);
				$this->sitemaps->add_item($item);
			}
			foreach ($categories AS $category) {
				$item = array(
					'loc' => site_url('results/category/' . $category->seo_name),
					'lastmod' => date('c', strtotime($category->last_modified)),
					'changefreq' => 'weekly',
					'priority' => '0.8'
				);
				$this->sitemaps->add_item($item);
			}
			$item = array(
				'loc' => site_url('manager/'),
				'lastmod' => date('c', time()),
				'changefreq' => 'yearly',
				'priority' => '0.8'
			);
			$this->sitemaps->add_item($item);
			$file_name = $this->sitemaps->build('sitemap.xml');
			$reponses = $this->sitemaps->ping(site_url($file_name));
			debug('sitemap complete - display message');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sitemap/sitemap', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sitemap/no_sitemap', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

}

/* End of file sitemap.php */
/* Location: ./application/controllers/sitemap.php */