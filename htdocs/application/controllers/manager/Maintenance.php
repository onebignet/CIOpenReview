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
 * Theme_settings management controller class
 *
 * Allows manager to edit theme settings
 *
 * @package        CIOpenReview
 * @subpackage          manager
 * @category            controller
 * @author        CIOpenReview.com
 * @link        http://CIOpenReview.com
 */
class Maintenance extends CI_Controller
{

	/*
	 * Maintenance controller class constructor
	 */

	function maintenance()
	{
		parent::__construct();
		$this->load->dbutil();
		$this->load->model('Maintenance_model');
		// load all settings into an array
		$this->setting = $this->Setting_model->get_every_setting();
	}

	/*
	 * index function
	 *
	 * display 'maintenance/maintenance' view,
	 */

	//delete session data
	//delete log files
	//delete cached files

	function index()
	{

		function rrmdir($path)
		{
			return is_file($path) ?
				@unlink($path) :
				array_map('rrmdir', glob($path . '/*')) == @rmdir($path);
		}

		debug('manager/maintenance page | index function');
		// check user is logged in with manager level permissions
		$this->secure->allow_managers($this->session);
		$data['message'] = '';
		// check for submitted form
		// update sitemap
		if ($this->input->post('update_sitemap')) {
			debug('update sitemap option was submitted');
			redirect('/manager/sitemap', 301);
			exit;
		}
		// session delete option
		if ($this->input->post('session_submit')) {
			debug('delete session data option was submitted');
			$this->Maintenance_model->delete_sessions($this->session->userdata('session_id'));
			$data['message'] = lang('manager_maintenance_session_deleted');
			debug('session records deleted');
		}
		// log delete option
		if ($this->input->post('log_submit')) {
			debug('delete log files option was submitted');
			rrmdir(APPPATH . 'logs');
			$data['message'] = lang('manager_maintenance_log_deleted');
			debug('log files deleted');
		}
		// cache delete option
		if ($this->input->post('cache_submit')) {
			debug('delete cache files option was submitted');
			rrmdir(APPPATH . 'cache');
			$data['message'] = lang('manager_maintenance_cache_deleted');
			debug('cache files deleted');
		}
		// database repair option
		if ($this->input->post('repair_submit')) {
			debug('repair database option was submitted');
			$this->dbutil->optimize_table('article');
			$this->dbutil->optimize_table('captcha');
			$this->dbutil->optimize_table('category');
			$this->dbutil->optimize_table('category_default_feature');
			$this->dbutil->optimize_table('category_default_rating');
			$this->dbutil->optimize_table('comment');
			$this->dbutil->optimize_table('feature');
			$this->dbutil->optimize_table('page');
			$this->dbutil->optimize_table('rating');
			$this->dbutil->optimize_table('review');
			$this->dbutil->optimize_table('review_feature');
			$this->dbutil->optimize_table('review_rating');
			$this->dbutil->optimize_table('session');
			$this->dbutil->optimize_table('setting');
			$this->dbutil->optimize_table('ad');
			$this->dbutil->optimize_table('tags');
			$this->dbutil->optimize_table('user');
			$data['message'] = lang('manager_maintenance_repaired');
			debug('database repaired and optimized');
		}
		if ($this->input->post('db_update_submit')) {
			debug('check and update database options was submitted');
			$this->load->database();
			$query = $this->db->query("SHOW COLUMNS FROM `ad` WHERE field = 'text'");
			if ($query->num_rows() == 1) {
				$row = $query->row();
				if (strtolower($row->type) == 'text') {
					$data['message'] = lang('manager_maintenance_db_not_updated');
					debug('no need to update database');
				} else {
					$data['message'] = lang('manager_maintenance_db_updated');
					$query = $this->db->query("ALTER TABLE `ad` CHANGE `text` `text` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL");
					debug('database has been updated');
				}
			}
		}
		debug('loading "manager/maintenance/maintenance" view');
		$sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/maintenance/maintenance', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
		$this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
	}

}

/* End of file maintenance.php */
/* Location: ./application/controllers/manager/maintenance.php */