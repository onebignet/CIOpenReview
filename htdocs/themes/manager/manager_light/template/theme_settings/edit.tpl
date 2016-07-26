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
<form id="form" class="myform" name="form" method="post" enctype="multipart/form-data"
      action="{{= base_url() . 'manager/theme_settings/edit' }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_edit_theme_settings_title') }}</h3>
            <p>&nbsp;</p>
            <div class="row">
                <div class="col-md-9">
                    <label>{{= lang('manager_theme_settings_form_theme') }}</label>
                    <p class="help-block">{{= lang('manager_theme_settings_form_theme_info') }}</p>
                    {{= form_error('theme') }}
                </div>
                <div class="col-md-3">
                    {{= form_dropdown('theme', $themes, $selected_theme, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <label> {{= lang('manager_theme_settings_form_manager_theme') }}</label>
                    <p class="help-block">{{= lang('manager_theme_settings_form_manager_theme_info') }}</p>
                    {{= form_error('manager_theme') }}
                </div>
                <div class="col-md-3">
                    {{= form_dropdown('theme', $manager_themes, $selected_manager_theme, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <label>{{= lang('manager_theme_settings_form_logo_upload') }}</label>
                    <p class="help-block">{{= lang('manager_theme_settings_form_logo_upload_info') }}</p>
                    {{= $upload_error }}
                </div>
                <div class="col-md-3">
                    <input type="file" name="userfile" size="20"/>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-md-9">
                    <label>{{= lang('manager_theme_settings_form_logo_preview') }}</label>
                    {{= $upload_error }}
                </div>
                <div class="col-md-3">
                    <img src="{{= $current_logo }}" alt="">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_theme_settings_form_max_upload_width') }}</label>
                            <p class="help-block">{{= lang('manager_site_settings_form_review_approval_info') }}</p>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_upload_width', $max_upload_width) }}"
                                   name="max_upload_width"
                                   id="max_upload_width">
                        </div>
                        {{= form_error('max_upload_width') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_theme_settings_form_max_upload_height') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_upload_height', $max_upload_height) }}"
                                   name="max_upload_height"
                                   id="max_upload_height">
                        </div>
                        {{= form_error('max_upload_height') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_theme_settings_form_max_upload_filesize') }}</label>
                            <p class="help-block">{{= lang('manager_theme_settings_form_max_upload_filesize_info') }}</p>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('max_upload_filesize', $max_upload_filesize) }}"
                                   name="max_upload_filesize"
                                   id="max_upload_filesize">
                        </div>
                        {{= form_error('max_upload_filesize') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_theme_settings_form_review_thumbnail_width') }}</label>
                            <p class="help-block">{{= lang('manager_theme_settings_form_review_thumbnail_width_info') }}</p>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('review_thumb_max_width', $review_thumb_max_width) }}"
                                   name="review_thumb_max_width"
                                   id="review_thumb_max_width">
                        </div>
                        {{= form_error('review_thumb_max_width') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_theme_settings_form_review_thumbnail_height') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('review_thumb_max_height', $review_thumb_max_height) }}"
                                   name="review_thumb_max_height"
                                   id="review_thumb_max_height">
                        </div>
                        {{= form_error('review_thumb_max_height') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_theme_settings_form_search_thumbnail_width') }}</label>
                            <p class="help-block">{{= lang('manager_theme_settings_form_search_thumbnail_width_info') }}</p>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('search_thumb_max_width', $search_thumb_max_width) }}"
                                   name="search_thumb_max_width"
                                   id="search_thumb_max_width">
                        </div>
                        {{= form_error('search_thumb_max_width') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_theme_settings_form_search_thumbnail_height') }}</label>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('search_thumb_max_height', $search_thumb_max_height) }}"
                                   name="search_thumb_max_height"
                                   id="search_thumb_max_height">
                        </div>
                        {{= form_error('search_thumb_max_height') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="settings_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_theme_settings_form_submit_button') }}"/>
        </div>
    </div>
</form>


</form>
