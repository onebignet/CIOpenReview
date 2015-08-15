<?php

/**
 * CIOpenReview
 *
 * An Open Source Review Site Script based on OpenReviewScript
 * This file is based on the original CodeIgniter date_helper.php file
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
/**
 * Timespan
 *
 * Returns a span of seconds in this format:
 *    10 days 14 hours 36 minutes 47 seconds
 *
 * @access    public
 * @param    integer    a number of seconds
 * @param    integer    Unix timestamp
 * @return    integer
 */
function timespan($seconds = 1, $time = '')
{
	$CI = &get_instance();
	$CI->lang->load('date');

	if (!is_numeric($seconds)) {
		$seconds = 1;
	}

	if (!is_numeric($time)) {
		$time = time();
	}

	if ($time <= $seconds) {
		$seconds = 1;
	} else {
		$seconds = $time - $seconds;
	}

	$str = '';
	$years = floor($seconds / 31536000);

	if ($years > 0) {
		$str .= $years . ' ' . $CI->lang->line((($years > 1) ? 'date_years' : 'date_year')) . ', ';
	}

	$seconds -= $years * 31536000;
	$months = floor($seconds / 2628000);

	if ($years > 0 OR $months > 0) {
		if ($months > 0) {
			$str .= $months . ' ' . $CI->lang->line((($months > 1) ? 'date_months' : 'date_month')) . ', ';
		}

		$seconds -= $months * 2628000;
	}

	$weeks = floor($seconds / 604800);

	if ($years > 0 OR $months > 0 OR $weeks > 0) {
		if ($weeks > 0) {
			$str .= $weeks . ' ' . $CI->lang->line((($weeks > 1) ? 'date_weeks' : 'date_week')) . ', ';
		}

		$seconds -= $weeks * 604800;
	}

	$days = floor($seconds / 86400);

	if ($months > 0 OR $weeks > 0 OR $days > 0) {
		if (($days > 0) && ($months < 1)) {
			$str .= $days . ' ' . $CI->lang->line((($days > 1) ? 'date_days' : 'date_day')) . ', ';
		}

		$seconds -= $days * 86400;
	}

	$hours = floor($seconds / 3600);

	if ($days > 0 OR $hours > 0) {
		if (($hours > 0) && ($days < 1)) {
			$str .= $hours . ' ' . $CI->lang->line((($hours > 1) ? 'date_hours' : 'date_hour')) . ', ';
		}

		$seconds -= $hours * 3600;
	}

	$minutes = floor($seconds / 60);

	if ($days > 0 OR $hours > 0 OR $minutes > 0) {
		if (($minutes > 0) && ($days < 1)) {
			$str .= $minutes . ' ' . $CI->lang->line((($minutes > 1) ? 'date_minutes' : 'date_minute')) . ', ';
		}

		$seconds -= $minutes * 60;
	}

	if ($str == '') {
		$str .= $seconds . ' ' . $CI->lang->line((($seconds > 1) ? 'date_seconds' : 'date_second')) . ', ';
	}
	return substr(trim($str), 0, -1);
}
/* End of file MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */