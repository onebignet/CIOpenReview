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
 * Users listing controller class
 *
 * Displays a list of users
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Users extends CI_Controller
{

	/*
	 * Users controller class constructor
	 */

	function users()
	{
		parent::__construct();
		$this->load->model('User_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function (default)
	 *
	 * display list of users paginated
	 */

	function index()
	{
		debug('manager/users page | index function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		// load a page of users into an array for displaying in the view
		$data['allusers'] = $this->User_model->get_all_users($this->setting['perpage_manager_users'], $this->uri->segment(4));
		if ($data['allusers']) {
			debug('loaded users');
			// set up config data for pagination
			$data['enough_managers_to_delete'] = $this->User_model->count_users_for_level(10) > 1 ? TRUE : FALSE;
			$config['base_url'] = base_url() . 'manager/users/index';
			$config['next_link'] = lang('results_next');
			$config['prev_link'] = lang('results_previous');
			$total = $this->User_model->count_users();
			$config['total_rows'] = $total;
			$config['per_page'] = $this->setting['perpage_manager_users'];
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			if (trim($data['pagination'] === '')) {
				$data['pagination'] = '&nbsp;<strong>1</strong>';
			}
			// show the users page
			debug('loading "manager/users" page');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/users/users', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		} else {
			// no data... show the 'no users' page
			// note: this should never actually happen!!
			debug('no users found - this is bad! - loading "/manager/no_users" view');
			$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/users/no_users', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
			$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
		}
	}

}

/* End of file users.php */
/* Location: ./application/controllers/manager/users.php */