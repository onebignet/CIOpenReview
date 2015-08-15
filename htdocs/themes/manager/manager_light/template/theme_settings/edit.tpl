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
        <div class="header_row">{{= lang('manager_edit_theme_settings_title') }}</div>
        <p>&nbsp;</p>
        {{ if(isset($message)): }}
        <p>&nbsp;</p>

        <h3>{{= $message }}</h3>

        <p>&nbsp;</p>
        {{ endif }}
        <p>&nbsp;</p>

        <form id="form" class="myform" name="form" method="post" enctype="multipart/form-data"
              action="{{= base_url() . 'manager/theme_settings/edit' }}">
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_theme') }}
                        <span class="small">{{= lang('manager_theme_settings_form_theme_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    {{= form_dropdown('theme', $themes, $selected_theme) }}
                    {{= form_error('theme') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_manager_theme') }}
                        <span class="small">{{= lang('manager_theme_settings_form_manager_theme_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    {{= form_dropdown('manager_theme', $manager_themes, $selected_manager_theme) }}
                    {{= form_error('manager_theme') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_logo_upload') }}
                        <span class="small">{{= lang('manager_theme_settings_form_logo_upload_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="file" name="userfile" size="20"/>
                    <span class="error">{{= $upload_error }}</span>
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_logo_preview') }}
                    </label>
                </div>
                <div class="formright">
                    <img src="{{= $current_logo }}" alt="">
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_max_upload_width') }}</label>
                    <span class="small">{{= lang('manager_theme_settings_form_max_upload_width_info') }}</span>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_upload_width" id="max_upload_width" size="2"
                           value="{{= set_value('max_upload_width', $max_upload_width) }}"/>
                    {{= form_error('max_upload_width') }}
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_max_upload_height') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_upload_height" id="max_upload_height" size="2"
                           value="{{= set_value('max_upload_height', $max_upload_height) }}"/>
                    {{= form_error('max_upload_height') }}
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_max_upload_filesize') }}</label>
                    <span class="small">{{= lang('manager_theme_settings_form_max_upload_filesize_info') }}</span>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="max_upload_filesize" id="max_upload_filesize" size="2"
                           value="{{= set_value('max_upload_filesize', $max_upload_filesize) }}"/>
                    {{= form_error('max_upload_filesize') }}
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_review_thumbnail_width') }}</label>
                    <span class="small">{{= lang('manager_theme_settings_form_review_thumbnail_width_info') }}</span>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="review_thumb_max_width" id="review_thumb_max_width" size="2"
                           value="{{= set_value('review_thumb_max_width', $review_thumb_max_width) }}"/>
                    {{= form_error('review_thumb_max_width') }}
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_review_thumbnail_height') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="review_thumb_max_height" id="review_thumb_max_height"
                           size="2" value="{{= set_value('review_thumb_max_height', $review_thumb_max_height) }}"/>
                    {{= form_error('review_thumb_max_height') }}
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_search_thumbnail_width') }}</label>
                    <span class="small">{{= lang('manager_theme_settings_form_search_thumbnail_width_info') }}</span>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="search_thumb_max_width" id="search_thumb_max_width" size="2"
                           value="{{= set_value('search_thumb_max_width', $search_thumb_max_width) }}"/>
                    {{= form_error('search_thumb_max_width') }}
                </div>
            </div>

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_theme_settings_form_search_thumbnail_height') }}</label>
                </div>
                <div class="formright">
                    <input class="short" type="text" name="search_thumb_max_height" id="search_thumb_max_height"
                           size="2" value="{{= set_value('search_thumb_max_height', $search_thumb_max_height) }}"/>
                    {{= form_error('search_thumb_max_height') }}
                </div>
            </div>
            <input type="submit" name="settings_submit" id="button"
                   value="{{= lang('manager_theme_settings_form_submit_button') }}"/>
        </form>
    </div>
</div>