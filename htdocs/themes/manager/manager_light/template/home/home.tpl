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
<h3 class="row">
    <h3 class="col-md-12">
        <h1>{{= lang('open_review_script_manager') }}</h1>
        <p>{{= lang('manager_welcome') }}    {{= lang('manager_version_running') }}
        </p>
        {{ if ($action_required): }}
        <div class="header_row_action">
            <h3>{{= lang('action_required') }}</h3>
        </div>
        {{ if ($reviews_to_approve > 0): }}
        <div class="alert alert-info" role="alert">
            <p>{{= anchor('manager/reviews/pending', $reviews_to_approve." ".lang('manager_home_reviews_to_approve')) }}</p>
        </div>
        {{ endif }}
        {{ if ($comments_to_approve > 0): }}
        <div class="alert alert-info" role="alert">
            <p>{{= anchor('manager/comments/pending', $comments_to_approve." ".lang('manager_home_comments_to_approve')) }}</p>
        </div>
        {{ endif }}
        {{ endif }}
        {{ if ($topreviews): }}
        <h4>{{= lang('manager_home_top_reviews') }}</h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="col-md-1">Views</th>
                <th>Review</th>
            </tr>
            </thead>
            <tbody>

            {{ foreach ($topreviews as $result): }}
            <tr>
                <td>{{= $result->views }}</td>
                <td>{{= anchor('review/show/' . $result->seo_title, character_limiter($result->title, 50), 'target="_blank"') }}</td>
            </tr>

            {{ endforeach }}
            </tbody>
        </table>
        {{ endif }}
        {{ if ($topclicks): }}
        <h4>{{= lang('manager_home_top_clicks') }}</h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="col-md-1">Clicks</th>
                <th class="col-md-2">Review</th>
                <th>Link</th>
            </tr>
            </thead>
            <tbody>
            {{ foreach ($topclicks as $result): }}
            <div class="manager_row">
                <tr>
                    <td>{{= $result->clicks }}</td>
                    <td>{{= anchor('review/show/' . $result->seo_title, character_limiter($result->title, 50), 'target="_blank"') }}</td>
                    {{ if ($result->link !== ''): }}
                    <td>{{= character_limiter($result->link, 80) }}</td>
                    {{ endif }}
                </tr>
            </div>
            {{ endforeach }}
            </tbody>
        </table>
        {{ endif }}
        </div>
        </div>