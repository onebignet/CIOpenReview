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
        <h3 class="box-title">{{= lang('manager_review_ratings_title').'"'.$review->title.'"' }}</h3>
        {{= anchor('manager/review_rating/add/'.$review->id,lang('manager_review_ratings_add_rating'), array('class' => 'btn btn-success', 'style' => 'margin-left: 20px;')) }}

        <div class="pull-right">
            {{= anchor('manager/review/edit/'.$review->id,lang('manager_review_features_back_to_review'), array('class' => 'btn btn-default')) }}
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table">
            <tbody>
            <tr>
                <th class="col-md-10">Rating</th>
                <th>Value</th>
                <th>Actions</th>
            </tr>
            <tr>
                <td colspan="3">
                    {{= lang('manager_review_ratings_no_review_ratings') }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>