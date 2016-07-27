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
        <h3 class="box-title">{{= lang('manager_comments_pending_title') }}</h3>
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
            {{ foreach ($pendingcomments as $result): }}
            <tr>
                <td>
                    <blockquote>&#8220;{{= character_limiter($result->quotation, 512) }}&#8221;
                        <small>{{= lang('manager_comments_list_review') }}{{= anchor('review/show/'.$result->seo_title, '&#8220;'.$result->title.'&#8221;',array('class' => '')) }}</small>
                    </blockquote>
                </td>
                <td>
                    {{= anchor('manager/comment/delete_pending/'.$result->id, lang('manager_comments_list_delete'), array('class' => 'btn btn-danger')) }}
                    {{= anchor('manager/comment/approve_pending/'.$result->id, lang('manager_comments_approve'), array('class' => 'btn btn-primary btn-success')) }}
                </td>
            </tr>
            {{ endforeach }}
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>