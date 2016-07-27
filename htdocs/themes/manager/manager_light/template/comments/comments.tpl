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

<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{= lang('manager_comments_title').'"'.$review->title.'"' }}</h3>
        {{= anchor('manager/comment/add/'.$review->id, lang('manager_comments_add_comment'), array('class' => 'btn btn-success', 'style' => 'margin-left: 20px;')) }}
        <div class="box-tools">
            {{= lang('manager_page') }}{{= $pagination }}
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table">
            <tbody>
            <tr>
                <th class="col-md-9">Comment</th>
                <th>Actions</th>
            </tr>
            {{ foreach ($allcomments as $result): }}
            <tr>
                <td>{{= character_limiter($result->quotation, 50) }}</td>
                <td>
                    {{= anchor('manager/comment/edit/'.$result->id, lang('manager_comments_list_edit'), array('class' => 'btn btn-default')) }}
                    {{= anchor('manager/comment/deleted/'.$result->id, lang('manager_comments_list_delete'), array('class' => 'btn btn-danger')) }}
                    {{ if ($result->approved > 0): }}
                    {{= anchor('manager/comment/unapprove/'.$result->id, lang('manager_comments_not_approve'), array('class' => 'btn btn-primary btn-success')) }}
                    {{ else: }}
                    {{= anchor('manager/comment/approve/'.$result->id, lang('manager_comments_approve'), array('class' => 'btn btn-primary btn-warning')) }}
                    {{ endif }}
                </td>
            </tr>
            {{ endforeach }}
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
