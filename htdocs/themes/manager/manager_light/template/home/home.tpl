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
<p>{{= lang('manager_welcome') }}    {{= lang('manager_version_running') }}
</p>
{{ if ($action_required): }}
<div class="header_row_action">
    <h3>{{= lang('action_required') }}</h3>
</div>
{{ if ($reviews_to_approve > 0): }}
<div class="callout callout-info">
    <p>{{= anchor('manager/reviews/pending', $reviews_to_approve." ".lang('manager_home_reviews_to_approve')) }}</p>
</div>
{{ endif }}
{{ if ($comments_to_approve > 0): }}
<div class="callout callout-info">
    <p>{{= anchor('manager/comments/pending', $comments_to_approve." ".lang('manager_home_comments_to_approve')) }}</p>
</div>
{{ endif }}
{{ endif }}
<div class="row">
    {{ if ($topreviews): }}
    <div class="col-md-4">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{= lang('manager_home_top_reviews') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th class="col-md-1">Views</th>
                        <th>Review</th>
                    </tr>
                    {{ foreach ($topreviews as $result): }}
                    <tr>
                        <td>{{= $result->views }}</td>
                        <td>{{= anchor('review/show/' . $result->seo_title, character_limiter($result->title, 50), 'target="_blank"') }}</td>
                    </tr>

                    {{ endforeach }}
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    {{ endif }}
    {{ if ($topclicks): }}
    <div class="col-md-8">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{= lang('manager_home_top_clicks') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th class="col-md-1">Clicks</th>
                        <th class="col-md-2">Review</th>
                        <th>Link</th>
                    </tr>
                    {{ foreach ($topclicks as $result): }}
                    <div class="manager_row">
                        <tr>
                            <td>{{= $result->clicks }}</td>
                            <td>{{= anchor('review/show/' . $result->seo_title, character_limiter($result->title, 50), 'target="_blank"') }}</td>
                            {{ if ($result->link !== ''): }}
                            <td>{{= character_limiter($result->link, 80) }}</td>
                            {{ else: }}
                            <td><p class="label label-danger">Link is missing!</p></td>
                            {{ endif }}
                        </tr>
                    </div>
                    {{ endforeach }}
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    {{ endif }}
</div>
