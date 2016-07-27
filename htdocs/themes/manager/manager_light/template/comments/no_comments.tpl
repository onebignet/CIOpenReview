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
<div class="box">

    <div class="box-header">
        <h3 class="box-title">{{= lang('manager_comments_title').'"'.$review->title.'"' }}</h3>
        {{= anchor('manager/comment/add/'.$review->id,lang('manager_comments_add_comment'), array('class' => 'btn btn-success', 'style' => 'margin-left: 20px;')) }}

        <div class="pull-right">
            {{= anchor('manager/review/edit/'.$review->id,lang('manager_comments_back_to_review'), array('class' => 'btn btn-default')) }}
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table">
            <tbody>
            <tr>
                <th class="col-md-10">Comment</th>
                <th>Actions</th>
            </tr>
            <tr>
                <td colspan="2">
                    {{= lang('manager_comments_no_comments') }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>