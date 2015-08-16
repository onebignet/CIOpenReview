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
 * Features listing controller class
 *
 * Displays a list of features
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Features extends CI_Controller
{

	/*
	 * Features controller class constructor
	 */

	function features()
	{
		parent::__construct();
		$this->load->model('Feature_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function (default)
	 *
	 * display list of features paginated
	 */

	function index()
	{
		debug('manager/features | index function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load a page of features into an array for displaying in the view
		$data['allfeatures'] = $this->Feature_model->get_all_features($this->setting['perpage_manager_features'], $this->uri->segment(4));
		if ($data['allfeatures']) {
			debug('loaded features');
			// set up config data for pagination
			$config['base_url'] = base_url() . 'manager/features/index';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Feature_model->count_features();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_manager_features'];
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			$data['pagination'] = $this->pagination->create_links();
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// show the features page
			debug('loading "manager/features" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/features/features', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no data... show the 'no features' page
			$data[] = '';
			debug('no features found - loading "manager/no_features" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/features/no_features', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

}

/* End of file features.php */
/* Location: ./application/controllers/manager/features.php */