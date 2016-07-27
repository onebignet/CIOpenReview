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
{{ if(isset($message)): }}
<div class="callout callout-warning">
    <p>{{= $message }}</p>
</div>
{{ endif }}
<form id="form" class="myform" name="form" method="post"
      action="{{= base_url() . 'manager/review_rating/add/' . $review->id }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_review_ratings_add_title') . '"' . $review->title . '"' }}</h3>
            <div class="pull-right">
                {{= anchor('manager/review_ratings/show/'.$review->id, lang('manager_review_ratings_go_back').' "'.$review->title.'"', array('class' => 'btn btn-default')) }}
            </div>
            <p>&nbsp;</p>
            <div class="row">
                <div class="col-md-9">
                    <label>{{= lang('manager_review_ratings_form_rating') }}</label>
                    <p class="help-block">{{= lang('manager_review_ratings_form_rating_info') }}</p>
                    {{= form_error('rating_id') }}
                </div>
                <div class="col-md-3">
                    {{= form_dropdown('rating_id', $ratings, $selected_rating, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <label>{{= lang('manager_review_ratings_form_value') }}</label>
                    <p class="help-block">{{= lang('manager_review_ratings_form_value_info') }}</p>
                    {{= form_error('value_id') }}
                </div>
                <div class="col-md-3">
                    {{= form_dropdown('value_id', $values, $selected_value, array('class' => 'form-control')) }}
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="review_rating_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_review_ratings_form_submit_button') }}"/>
        </div>
    </div>
</form>