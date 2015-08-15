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

}}
<div id="content">
    <div class="header_row">
        <p>{{= lang('open_review_script_manager') }}</p>
    </div>
    <div class="break"><p>&nbsp;</p></div>
    <p>{{= lang('manager_welcome') }}    {{= lang('manager_version_running') }}
    </p>
    {{ if ($action_required): }}
    <div class="break"><p>&nbsp;</p></div>
    <div class="header_row_action">
        <p>{{= lang('action_required') }}</p>
    </div>
    {{ if ($reviews_to_approve > 0): }}
    <div class="manager_row">
        <p>{{= anchor('manager/reviews/pending', lang('manager_home_reviews_to_approve')) }}
            :{{= $reviews_to_approve }}</p>
    </div>
    {{ endif }}
    {{ if ($comments_to_approve > 0): }}
    <div class="manager_row">
        <p>{{= anchor('manager/comments/pending', lang('manager_home_comments_to_approve')) }}
            :{{= $comments_to_approve }}</p>
    </div>
    {{ endif }}
    {{ endif }}
    <div class="break"><p>&nbsp;</p></div>
    {{ if ($topreviews): }}
    <div class="header_row">{{= lang('manager_home_top_reviews') }}</div>
    {{ foreach ($topreviews as $result): }}
    <div class="manager_row">
        <p>({{= $result->views }}
            )&nbsp;{{= anchor('review/show/' . $result->seo_title, character_limiter($result->title, 50), 'target="_blank"') }}
        </p>
    </div>
    {{ endforeach }}
    <div class="break"><p>&nbsp;</p></div>
    {{ endif }}
    {{ if ($topclicks): }}
    <div class="header_row">{{= lang('manager_home_top_clicks') }}</div>
    {{ foreach ($topclicks as $result): }}
    <div class="manager_row">
        <p>({{= $result->clicks }}
            )&nbsp;{{= anchor('review/show/' . $result->seo_title, character_limiter($result->title, 50), 'target="_blank"') }}
            {{ if ($result->link !== ''): }}
            &nbsp;({{= character_limiter($result->link, 80) }})
            {{ endif }}
        </p>
    </div>
    {{ endforeach }}
    <div class="break"><p>&nbsp;</p></div>
    {{ endif }}
</div>