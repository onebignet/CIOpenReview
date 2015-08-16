<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
//
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

function debug($message)
{
	$CI = &get_instance();
	$CI->load->model('Setting_model');
	if ($CI->Setting_model->get_setting_by_name('debug') == 1) {
		$visitor_ip_address = $_SERVER['REMOTE_ADDR'];
		add_to_log("DEBUG", $message);
		log_message("error", $visitor_ip_address . ':' . $message);

	}


}

function user_log($message)
{
	add_to_log("USER", $message);
}

function admin_log($message)
{
	add_to_log("ADMIN", $message);
}

function add_to_log($type, $message)
{
	$CI = &get_instance();
	$CI->load->database();
	$visitor_ip_address = $_SERVER['REMOTE_ADDR'];
	$insert = array(
		'type' => $type,
		'user' => $CI->session->userdata('name') . " (Level " . $CI->session->userdata('level') . ")",
		'ip' => $visitor_ip_address,
		'message' => $message
	);
	$CI->db->set('time', 'NOW()', FALSE);
	$CI->db->insert('logs', $insert);
}



/* End of file log_helper.php */
/* Location: ./application/helpers/log_helper.php */