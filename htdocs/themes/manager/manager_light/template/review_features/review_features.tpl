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
    <div class="header_row">{{= lang('manager_review_features_title').'"'.$review->title.'"' }}</div>
    <p class="nav_links">
        <b>{{= anchor('manager/review_feature/add/'.$review->id,lang('manager_review_features_add_feature')) }}</b></p>

    <p class="nav_links">
        <b>{{= anchor('manager/review/edit/'.$review->id,lang('manager_review_features_back_to_review')) }}</b></p>

    <p class="break">&nbsp;</p>
    {{ if ($this->session->flashdata('message')): }}
    <p>&nbsp;</p>

    <h3>{{= $this->session->flashdata('message') }}</h3>
    {{ endif }}
    <p class="break">&nbsp;</p>

    <div class="header_row">{{= lang('manager_review_feature_edit_title') }}</div>
    <form id="form" class="myform" name="form" method="post"
          action="{{= base_url() . 'manager/review_features/edit/' . $review->id }}">
        {{ foreach ($allreviewfeatures as $index=>$result): }}
        <p>
                        <span>
                              {{= form_dropdown('feature_id'.$index, $features, $result->feature_id, 'class="dropdown_left"') }}
                            {{= form_error('feature_id'.$index) }}
                        </span>
                        <span>
                              <input class="input_right" type="text" name="value{{= $index }}" id="value{{= $index }}"
                                     value="{{= set_value('value', $result->value) }}"/>
                            {{= form_error('value'.$index) }}
                        </span>
            <span class="right_link"">{{= anchor('manager/review_feature/delete/'.$result->id,lang('manager_review_features_list_delete'),'id="darkblue"') }}</span>
            <input type="hidden" name="review_feature_id{{= $index }}" value="{{= $result->review_feature_id }}"/>
        </p>
        {{ endforeach }}
        <input type="hidden" name="feature_count" value="{{= count($allreviewfeatures) }}"/>

        <div class="break"><p>&nbsp;</p></div>
        <input type="hidden" name="review_features_submit" value="1">
        <input type="submit" name="review_features_submit" style="width:300px" id="button"
               value="{{= lang('manager_review_features_form_submit_button') }}"/>
    </form>
</div>