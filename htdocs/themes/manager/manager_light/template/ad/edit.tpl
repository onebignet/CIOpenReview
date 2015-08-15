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
        <div class="header_row">{{= lang('manager_ad_edit_title') }}</div>
        <h3>{{= lang('manager_ad_edit_title_info') }}</h3>

        <p>&nbsp;</p>

        <form id="form" class="myform" name="form" method="post" enctype="multipart/form-data"
              enctype="multipart/form-data" action="{{= base_url() . 'manager/ad/edit/' . $ad->id }}">
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_name') }}
                        <span class="small">{{= lang('manager_ad_form_name_info') }}</span>

                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="name" id="name"
                           value="{{= set_value('name', $ad->name) }}"/>
                    {{= form_error('name') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_text') }}
                        <span class="small">{{= lang('manager_ad_form_text_info') }}</span>

                    </label>
                </div>
                <div class="formright">
                    <textarea cols="40" rows="8" name="text" class="strong"
                              id="text">{{= set_value('text', $ad->text) }}</textarea>
                    {{= form_error('text') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_link') }}
                        <span class="small">{{= lang('manager_ad_form_link_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="link" id="link"
                           value="{{= set_value('link', $ad->link) }}"/>
                    {{= form_error('link') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_image_upload') }}
                        <span class="small">{{= lang('manager_ad_form_image_upload_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="file" name="userfile" size="20"/>
                    <span class="error">{{= $upload_error }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_image_url') }}
                        <span class="small">{{= lang('manager_ad_form_image_url_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="text" name="remote_image_url" id="remote_image_url"
                           value="{{= set_value('remote_image_url', $ad->remote_image_url) }}"/>
                    <span class="error">{{= $url_error }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_image_width') }}
                        <span class="small">{{= lang('manager_ad_form_image_width_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="text" name="image_width" id="image_width"
                           value="{{= set_value('image_width', $ad->image_width) }}"/>
                    <span class="error">{{= $image_width_error }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_image_height') }}
                        <span class="small">{{= lang('manager_ad_form_image_height_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="text" name="image_height" id="image_height"
                           value="{{= set_value('image_height', $ad->image_height) }}"/>
                    <span class="error">{{= $image_height_error }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_visible_in_sidebar') }}
                        <span class="small">{{= lang('manager_ad_form_visible_in_sidebar_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="visible_in_sidebar" id="approved" type="checkbox" {{= $visible_in_sidebar }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_visible_in_lists') }}
                        <span class="small">{{= lang('manager_ad_form_visible_in_lists_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="visible_in_lists" id="approved" type="checkbox" {{= $visible_in_lists }}>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_ad_form_visible_on_review_page') }}
                        <span class="small">{{= lang('manager_ad_form_visible_on_review_page_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input name="visible_on_review_page" id="approved" type="checkbox" {{= $visible_on_review_page }}>
                </div>
            </div>
    </div>
    <div class="block">
        <div class="header_row">{{= lang('manager_ad_edit_preview') }}</div>
        <h3>{{= lang('manager_ad_edit_preview_info') }}</h3>

        <div class="ad_block" style="height:{{= $ad->image_height+50 }}px;">
            {{ if ($ad->image !== ''): }}
            <div class="ad_image">{{= anchor($ad->link, $ad->image) }}</div>
            {{ endif }}
            {{ if ($ad->text !== ''): }}
            <div class="ad_text">{{= character_limiter($ad->text) }}</div>
            {{ endif }}
        </div>
    </div>
    <div><p>&nbsp;</p>
        <input type="submit" name="ad_submit" id="button" value="{{= lang('manager_ad_form_submit_button') }}"/>
    </div>
    </form>
</div>