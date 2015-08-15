{{
/**
* CIOpenReview
*
* An Open Source Review Site Script
*
* @package        CIOpenReview
* @subpackage          manager
* @author        CIOpenReview.com
* @copyright           Copyright (c) 2015 MindBrite.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
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
    <div class="header_row">{{= lang('manager_reviews_title') }}</div>
    <p class="nav_links"><b>{{= anchor('manager/review/add',lang('manager_reviews_add_review')) }}</b></p>

    <div class="pagenav">{{= lang('manager_page') }}{{= $pagination }}</div>
    <div class="break"></div>
    {{ foreach($allreviews as $result): }}
    <div class="manager_row">
        <p class="manager_left_2">{{= anchor('review/show/'.$result->seo_title, character_limiter($result->title, 50),'target="_blank"') }}
        </p>

        <p class="manager_narrow">
            {{ if ($result->approved > 0): }}
            <span class="approved"></span>
            {{ else: }}
            <span class="pending"></span>
            {{ endif }}
        </p>

        <p class="manager_narrow">{{= anchor('manager/review/edit/'.$result->id, lang('manager_review_list_edit')) }}</p>

        <p class="manager_narrow">{{= anchor('manager/review/delete/'.$result->id, lang('manager_review_list_delete'),'id="darkblue"') }}</p>
        {{ if ($result->approved > 0): }}
        <p class="manager_narrow">{{= anchor('manager/review/unapprove/'.$result->id, lang('manager_reviews_not_approve'),' id="darkblue"') }}</p>
        {{ else: }}
        <p class="manager_narrow">{{= anchor('manager/review/approve/'.$result->id, lang('manager_reviews_approve'),' id="darkblue"') }}</p>
        {{ endif }}
        <p class="manager_narrow">{{= anchor('manager/review_features/show/'.$result->id, lang('manager_review_list_features')) }}</p>

        <p class="manager_narrow">{{= anchor('manager/review_ratings/show/'.$result->id, lang('manager_review_list_ratings')) }}</p>

        <p class="manager_narrow">{{= anchor('manager/comments/show/'.$result->id, lang('manager_review_list_comments')) }}</p>
    </div>
    {{ endforeach }}
    <div class="pagenav">{{= lang('manager_page') }}{{= $pagination }}</div>
</div>