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
        <h3 class="box-title">{{= lang('manager_category_delete_title').'&#8220;'.$category->name.'&#8221;' }}</h3>
        <div class="box-tools">
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <label>{{= lang('manager_category_delete_warning') }}</label>
            </div>
        </div>
        <div class="row">
            <form id="form" class="myform" name="form" method="post"
                  action="{{= base_url() . 'manager/category/delete/'.$category->id }}">
                <div class="col-md-9">
                    <label>{{= lang('manager_review_form_category') }}</label>
                    <p class="help-block">{{= lang('manager_category_move_name_info') }}</p>
                    {{= form_error('category_id') }}
                </div>
                <div class="col-md-3">
                    {{= form_dropdown('category_id', $categories, $selected,array('class' => 'form-control')) }}
                </div>
        </div>
    </div>
    <div class="box-footer">
        <input type="submit" name="category_delete_submit" id="button" class="btn btn-primary btn-danger"
               value="{{= lang('manager_category_delete_submit_button') }}"/>
    </div>
    <!-- /.box-body -->
</div>