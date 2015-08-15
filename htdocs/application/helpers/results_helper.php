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
function highlight_keywords($content, $keywords)
{
	// highlights keywords in a block of text
	// used to highlight words in search results
	if (!is_array($keywords)) {
		$keywords = explode(' ', $keywords);
	}
	foreach ($keywords as $keyword) {
		if (trim($keyword) !== '') {
			$content = preg_replace('/\b(' . preg_quote($keyword) . ')\b/i', '<span class="highlight">\1</span>', $content);
		}
	}
	return $content;
}

function snip($section_count, $content, $keywords, $length)
{
	// shortens text without breaking whole words
	$keywords = explode(' ', $keywords);
	$sections = array();
	$end = strrpos(substr($content, 0, $length), ' ');
	$sections[] = highlight_keywords(trim(substr($content, 0, $end)), $keywords);
	$next = $length;
	foreach (range(0, $section_count) as $count) {
		if ($next > strlen($content)) {
			break;
		}
		$begin = strlen($content);
		foreach ($keywords as $term) {
			$begin = min($begin, stripos($content, $term, $next));
		}
		$begin = strrpos(substr($content, 0, $begin - 11), ' ');
		$section = trim(substr($content, $begin, $length + 10));
		$char_positions = array();
		$chars = '" $ % & * ( ) ; < > ! : ,';
		$chars = explode(' ', $chars);
		$position = strrpos($section, ' ');
		$char_positions[] = $position;
		foreach ($chars as $char) {
			$position = strrpos($section, $char);
			if ($position) {
				$char_positions[] = $position;
			}
		}
		$last_char = 0;
		foreach (range(0, count($char_positions) - 1) as $index) {
			if ($char_positions[$index] > $last_char) {
				$last_char = $char_positions[$index];
			}
			if ($char_positions[$index] == $last_char - 1) {
				$last_char--;
			}
		}
		$section = substr($section, 0, $last_char);
		$next = $begin + $length;
		$sections[] = highlight_keywords($section, $keywords);
	}
	return implode(' &hellip; ', $sections);
}

/* End of file results_helper.php */
/* Location: ./application/helpers/results_helper.php */