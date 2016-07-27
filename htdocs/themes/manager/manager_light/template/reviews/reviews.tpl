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
<div class="box">

    <div class="box-header">
        <h3 class="box-title">{{= lang('manager_reviews_title') }}</h3>
        {{= anchor('manager/review/add',lang('manager_reviews_add_review'), array('class' => 'btn btn-success', 'style' => 'margin-left: 20px;')) }}

        <div class="box-tools">
            {{= lang('manager_page') }}{{= $pagination }}
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table">
            <tbody>
            <tr>
                <th class="col-md-6">Review Name</th>
                <th>Update</th>
                <th>Actions</th>
            </tr>
            {{ foreach($allreviews as $result): }}
            <tr>
                <td>{{= anchor('review/show/'.$result->seo_title, character_limiter($result->title, 50),'target="_blank"') }}</td>
                <td>
                    {{= anchor('manager/review_features/show/'.$result->id, lang('manager_review_list_features'), array('class' => 'btn btn-default')) }}
                    {{= anchor('manager/review_ratings/show/'.$result->id, lang('manager_review_list_ratings'), array('class' => 'btn btn-default')) }}
                    {{= anchor('manager/comments/show/'.$result->id, lang('manager_review_list_comments'), array('class' => 'btn btn-default')) }}
                </td>
                <td>
                    {{= anchor('manager/review/edit/'.$result->id, lang('manager_review_list_edit'), array('class' => 'btn btn-default')) }}
                    {{= anchor('manager/review/delete/'.$result->id, lang('manager_review_list_delete'), array('class' => 'btn btn-danger')) }}
                    {{ if ($result->approved > 0): }}
                    {{= anchor('manager/review/unapprove/'.$result->id, lang('manager_reviews_not_approve'), array('class' => 'btn btn-warning')) }}
                    {{ else: }}
                    {{= anchor('manager/review/approve/'.$result->id, lang('manager_reviews_approve'), array('class' => 'btn btn-success')) }}
                    {{ endif }}
                </td>
            </tr>
            {{ endforeach }}
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
