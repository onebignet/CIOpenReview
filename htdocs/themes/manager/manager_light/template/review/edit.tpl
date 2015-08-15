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
        <div class="header_row">{{= lang('manager_review_edit_title') }}</div>
        {{ if(isset($message)): }}
        <p>&nbsp;</p>

        <h3>{{= $message }}</h3>
        {{ endif }}
        <p>&nbsp;</p>

        <p class="manager_right_link">
            <strong>-> {{= anchor('manager/reviews', lang('manager_review_manage_back_to_reviews')) }}</strong></p>

        <p>&nbsp;</p>

        <p class="manager_right_link">
            <strong>-> {{= anchor('review/show/'.$review->seo_title, lang('manager_review_preview'), 'target="_blank"') }}</strong>
        </p>

        <p>&nbsp;</p>

        <p class="manager_right_link">
            <strong>-> {{= anchor('manager/comments/show/'.$review->id, lang('manager_review_manage_comments')) }}</strong>
        </p>

        <p>&nbsp;</p>

        <p class="manager_right_link">
            <strong>-> {{= anchor('manager/review_ratings/show/'.$review->id, lang('manager_review_manage_ratings')) }}</strong>
        </p>

        <p>&nbsp;</p>

        <p class="manager_right_link">
            <strong>-> {{= anchor('manager/review_features/show/'.$review->id, lang('manager_review_manage_features')) }}</strong>
        </p>

        <p>&nbsp;</p>

        <p>&nbsp;</p>

        <form id="form" accept-charset="UTF-8" class="myform" name="form" method="post" enctype="multipart/form-data"
              lang="ru" action="{{= base_url() . 'manager/review/edit/' . $review->id }}">
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_title') }}
                        <span class="small">{{= lang('manager_review_form_title_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="title" id="title"
                           value="{{= set_value('title', $review->title) }}"/>
                    {{= form_error('title') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_description') }}
                        <span class="small">{{= lang('manager_review_form_description_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <textarea cols="40" rows="10" class="long" name="description"
                              id="description">{{= set_value('description', $review->description) }}</textarea>
                    {{= form_error('description') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_category') }}
                        <span class="small">{{= lang('manager_review_form_category_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    {{= form_dropdown('category_id', $categories, $selected_category) }}
                    {{= form_error('category_id') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_featured') }}
                        <span class="small">{{= lang('manager_review_form_featured_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="featured" id="featured" type="checkbox" {{= $featured }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_tags') }}
                        <span class="small">{{= lang('manager_review_form_tags_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="text" name="tags" id="tags" value="{{= set_value('tags', $review->tags) }}"/>
                    {{= form_error('tags') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_current_image') }}
                        <span class="small">{{= lang('manager_review_form_current_image_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <img src="{{= $current_image }}"/>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_image_upload') }}
                        <span class="small">{{= lang('manager_review_form_image_upload_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="file" name="userfile" size="20"/>
                    <span class="error">{{= $upload_error }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_image_url') }}
                        <span class="small">{{= lang('manager_review_form_image_url_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="text" name="image_url" id="image_url"/>
                    <span class="error">{{= $grab_error }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_vendor') }}
                        <span class="small">{{= lang('manager_review_form_vendor_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="vendor" id="vendor"
                           value="{{= set_value('vendor',$review->vendor) }}"/>
                    {{= form_error('vendor') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_link') }}
                        <span class="small">{{= lang('manager_review_form_link_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="link" id="link"
                           value="{{= set_value('link', $review->link) }}"/>
                    {{= form_error('link') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_meta_keywords') }}
                        <span class="small">{{= lang('manager_review_form_meta_keywords_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="meta_keywords" id="titmeta_keywordsle"
                           value="{{= set_value('meta_keywords', $review->meta_keywords) }}"/>
                    {{= form_error('meta_keywords') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_review_form_meta_description') }}
                        <span class="small">{{= lang('manager_review_form_meta_description_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="meta_description" id="meta_description"
                           value="{{= set_value('meta_description', $review->meta_description) }}"/>
                    {{= form_error('meta_description') }}
                </div>
            </div>
            <input type="submit" name="review_submit" id="button"
                   value="{{= lang('manager_review_form_submit_button') }}"/>
        </form>
    </div>
</div>
