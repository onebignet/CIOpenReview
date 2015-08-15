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
<div id="content">
    <div class="myform">
        <div class="header_row">{{= lang('manager_review_rating_edit_title') }}</div>
        <p>&nbsp;</p>

        <p class="manager_right_link">{{= lang('manager_review_ratings_go_back') }}
            <strong>"{{= anchor('manager/review_ratings/show/' . $review->id, $review->title) }}"</strong></p>

        <p>&nbsp;</p>

        <p>&nbsp;</p>

        <form id="form" class="myform" name="form" method="post"
              action="{{= base_url() . 'manager/review_rating/edit/' . $review_rating->id }}">

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_ratings_form_rating') }}
                        <span class="small">{{= lang('manager_review_ratings_form_rating_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    {{= form_dropdown('rating_id', $ratings, $selected_rating) }}
                    {{= form_error('rating_id') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_ratings_form_value') }}
                        <span class="small">{{= lang('manager_review_ratings_form_value_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    {{= form_dropdown('value_id', $values, $selected_value) }}
                    {{= form_error('value_id') }}
                </div>
            </div>
            <input type="submit" name="review_rating_submit" id="button"
                   value="{{= lang('manager_review_ratings_form_submit_button') }}"/>
        </form>
    </div>
</div>