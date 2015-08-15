<?php

/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript
 *
 * @package        CIOpenReview
 * @subpackage          manager
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
 * Ratings listing controller class
 *
 * Displays a list of ratings
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Ratings extends CI_Controller
{

	/*
	 * Ratings controller class constructor
	 */

	function Ratings()
	{
		parent::__construct();
		$this->load->model('Rating_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * index function (default)
	 *
	 * display list of ratings paginated
	 */

	function index()
	{
		debug('manager/ratings page | index function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load a page of ratings into an array for displaying in the view
		$data['allratings'] = $this->Rating_model->getAllratings($this->setting['perpage_manager_ratings'], $this->uri->segment(4));
		if ($data['allratings']) {
			debug('loaded ratings');
			// set up config data for pagination
			$config['base_url'] = base_url() . 'manager/ratings/index';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Rating_model->countRatings();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_manager_ratings'];
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// show the ratings page
			debug('loading "manager/ratings" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ratings/ratings', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no data... show the 'no ratings' page
			$data[] = '';
			debug('no ratings found - load "manager/no_ratings" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ratings/no_ratings', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

}

/* End of file ratings.php */
/* Location: ./application/controllers/manager/ratings.php */