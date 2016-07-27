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
<script type="text/javascript">
        tinymce.init({
            mode: "exact",
            elements: "text",
            theme: "modern",
            plugins: [
                "advlist autolink lists link image charmap preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code",
                "insertdatetime media nonbreaking table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern imagetools"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor emoticons",
        });




</script>
{{ if(isset($message)): }}
<div class="callout callout-warning">
    <p>{{= $message }}</p>
</div>
{{ endif }}

<form id="form" class="myform" name="form" method="post" enctype="multipart/form-data"
      enctype="multipart/form-data" action="{{= base_url() . 'manager/ad/edit/' . $ad->id }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_ad_edit_title') }}</h3>
            <p class="help-block">{{= lang('manager_ad_edit_title_info') }}</p>
            <p>&nbsp;</p>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_ad_form_name') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('name', $ad->name) }}"
                           name="name"
                           id="name">
                    {{= form_error('name') }}
                    <p class="help-block">{{= lang('manager_ad_form_name_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_ad_form_text') }}</label>
                    <textarea cols="40" rows="10" class="form-control" name="text"
                              id="text">{{= set_value('text',$ad->text) }}</textarea>
                    {{= form_error('text') }}
                    <p class="help-block">{{= lang('manager_ad_form_text_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_ad_form_link') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('link', $ad->link) }}"
                           name="link"
                           id="link">
                    {{= form_error('link') }}
                    <p class="help-block">{{= lang('manager_ad_form_link_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <label>{{= lang('manager_ad_form_image_upload') }}</label>

                    <span class="label label-danger">{{= $upload_error }}</span>
                    <p class="help-block">{{= lang('manager_ad_form_image_upload_info') }}</p>
                </div>
                <div class="col-md-3">
                    <input type="file" name="userfile" size="20"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_ad_form_image_url') }}</label>
                    <input class="form-control" type="text"
                           value="{{= set_value('remote_image_url', $ad->remote_image_url) }}"
                           name="remote_image_url"
                           id="remote_image_url">
                    {{= form_error('remote_image_url') }}
                    <p class="help-block">{{= lang('manager_ad_form_image_url_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_ad_form_image_width') }}</label>
                            <p class="help-block">{{= lang('manager_ad_form_image_width_info') }}</p>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('image_width', $ad->image_width) }}"
                                   name="image_width"
                                   id="image_width">
                        </div>
                        {{= form_error('image_width') }}
                    </div>
                    <div class="row form-row">
                        <div class="col-md-10">
                            <label>{{= lang('manager_ad_form_image_height') }}</label>
                            <p class="help-block">{{= lang('manager_ad_form_image_height_info') }}</p>
                        </div>
                        <div class="col-xs-2">
                            <input class="form-control" type="text"
                                   value="{{= set_value('image_height', $ad->image_height) }}"
                                   name="image_height"
                                   id="image_height">
                        </div>
                        {{= form_error('image_height') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-checkbox">
                        <label><input name="visible_in_sidebar" id="approved"
                                      type="checkbox" {{= $visible_in_sidebar }}> {{= lang('manager_ad_form_visible_in_sidebar') }}
                        </label>
                        <p class="help-block">{{= lang('manager_ad_form_visible_in_sidebar_info') }}</p>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="visible_in_lists" id="approved"
                                      type="checkbox" {{= $visible_in_lists }}> {{= lang('manager_ad_form_visible_in_lists') }}
                        </label>
                        <p class="help-block">{{= lang('manager_ad_form_visible_in_lists_info') }}</p>
                    </div>
                    <div class="form-checkbox">
                        <label><input name="visible_on_review_page" id="approved"
                                      type="checkbox" {{= $visible_on_review_page }}> {{= lang('manager_ad_form_visible_on_review_page') }}
                        </label>
                        <p class="help-block">{{= lang('manager_ad_form_visible_on_review_page_info') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_ad_edit_preview') }}</h3>
            <p class="help-block">{{= lang('manager_ad_edit_preview_info') }}</p>


            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box box-info">
                        <div class="box-body with-border">
                            <p>&nbsp;</p>
                            {{ if ($ad->image !== ''): }}
                            {{= anchor($ad->link, $ad->image) }}
                            {{ endif }}
                            {{ if ($ad->text !== ''): }}
                            {{= character_limiter($ad->text) }}
                            {{ endif }}
                            <p>&nbsp;</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="ad_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_ad_form_submit_button') }}"/>
        </div>
    </div>
</form>