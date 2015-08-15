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
 * Ads listing controller class
 *
 * Displays a list of ads
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Ads extends CI_Controller
{

	/*
	 * Ads controller class constructor
	 */

	function Ads()
	{
		parent::__construct();
		$this->load->model('Ad_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->getEverySetting();
	}

	/*
	 * index function (default)
	 *
	 * display list of ads paginated
	 */

	function index()
	{
		debug('manager/ads page | index function');
		// check user is logged in with manager level permissions
		$this->secure->allowManagers($this->session);
		// load a page of ads into an array for displaying in the view
		$data['allads'] = $this->Ad_model->getManagerAds($this->setting['perpage_manager_ads'], $this->uri->segment(4));
		if ($data['allads']) {
			debug('loaded ads');
			// set up config data for pagination
			$config['base_url'] = base_url() . 'manager/ads/index';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->Ad_model->countAds();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_manager_ads'];
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// show the ads page
			debug('loading "manager/ads" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ads/ads', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no data... show the 'no ads' page
			$data[] = '';
			debug('no ads found - loading "manager/no_ads" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/ads/no_ads', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

}

/* End of file ads.php */
/* Location: ./application/controllers/manager/ads.php */