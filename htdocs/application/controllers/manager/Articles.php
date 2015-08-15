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
 * Articles listing controller class
 *
 * Displays a list of articles
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Articles extends CI_Controller
{

	/*
	 * Articles controller class constructor
	 */

	function Articles()
	{
		parent::__construct();
		$this->load->model('Article_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * index function (default)
	 *
	 * display list of articles paginated
	 */

	function index()
	{
		debug('manager/articles page | index function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load a page of articles into an array for displaying in the view
		$data['allarticles'] = $this->Article_model->getAllArticles($this->setting['perpage_manager_articles'], $this->uri->segment(4));
		if ($data['allarticles']) {
			debug('loaded articles');
			// set up config data for pagination
			$config['base_url'] = base_url() . 'manager/articles/index';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Article_model->countArticles();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_manager_articles'];
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// show the articles page
			debug('loading "manager/articles" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/articles/articles', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no data... show the 'no articles' page
			debug('loading "manager/no_articles" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/articles/no_articles', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

}

/* End of file articles.php */
/* Location: ./application/controllers/manager/articles.php */