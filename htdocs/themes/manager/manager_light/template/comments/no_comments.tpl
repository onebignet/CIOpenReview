{{
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
* @link        http://ciopenreview.com
*/
// ------------------------------------------------------------------------
//
}}
<div id="content">
    <div class="header_row">{{= lang('manager_comments_title').'"'.$review->title.'"' }}</div>
    <p class="nav_links"><b>{{= anchor('manager/comment/add/'.$review->id,lang('manager_comments_add_comment')) }}</b>
    </p>

    <p class="nav_links">
        <b>{{= anchor('manager/review/edit/'.$review->id,lang('manager_comments_back_to_review')) }}</b></p>

    <p class="break">&nbsp;</p>

    <p class="break">&nbsp;</p>

    <p>{{= lang('manager_comments_no_comments') }}</p>

    <div class="break"><p>&nbsp;</p></div>
    <div class="break"><p>&nbsp;</p></div>
</div>