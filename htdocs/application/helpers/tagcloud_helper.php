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
function get_cloud_size($minsize, $maxsize, $min, $max, $actual)
{
	// given a minimum and maximum font size, a minimum and maximum tag count and the actual tag count, returns font size for use in tag cloud
	$sizerange = $maxsize - $minsize;
	$range = $max - $min;
	$position = $actual - $min;
	if ($range > 0) {
		$percent = $position / $range;
	} else {
		$percent = 0.5;
	}
	$size = $minsize + ($sizerange * $percent);
	return $size;
}

/* End of file tagcloud_helper.php */
/* Location: ./application/helpers/tagcloud_helper.php */