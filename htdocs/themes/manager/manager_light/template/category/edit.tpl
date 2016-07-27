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
<div class="callout callout-warning" xmlns="http://www.w3.org/1999/html">
    <p>{{= $message }}</p>
</div>
{{ endif }}
<form id="form" class="myform" name="form" method="post"
      action="{{= base_url() . 'manager/category/edit/' . $category->id }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_category_edit_title') }}</h3>
            <p>&nbsp;</p>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_category_form_name') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('name', $category->name) }}"
                           name="name"
                           id="name">
                    {{= form_error('name') }}
                    <p class="help-block">{{= lang('manager_category_form_name_info') }}</p>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="category_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_category_form_submit_button') }}"/>
        </div>
    </div>
</form>

{{ if($features): }}
<form id="form" class="myform" name="form" method="post"
      action="{{= base_url() . 'manager/category/edit/' . $category->id }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_category_edit_list_features') }}</h3>
            <p>&nbsp;</p>
            <div class="row">
                {{ if($categorydefaultfeatures): }}
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-md-10">Feature Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{ foreach ($categorydefaultfeatures as $result): }}
                        <tr>
                            <td>{{= character_limiter($result->name, 50) }}</td>
                            <td>{{= anchor('manager/category/delete_default_feature/'.$result->id, lang('manager_category_default_feature_list_delete'),array('class' => 'btn btn-primary btn-danger')) }}</td>
                        </tr>
                        {{ endforeach }}
                        </tbody>
                    </table>
                </div>
                {{ endif }}
            </div>
        </div>
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-9">
                    <label>{{= lang('manager_category_default_feature_feature') }}</label>
                    <p class="help-block">{{= lang('manager_category_default_feature_feature_info') }}</p>
                    {{= form_error('feature_id') }}
                </div>
                <div class="col-md-3">
                    {{= form_dropdown('feature_id', $features, $selected_feature,array('class' => 'form-control')) }}
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="category_default_feature_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_category_default_feature_submit') }}"/>
        </div>
    </div>
</form>
{{ endif }}

{{ if($ratings): }}
<form id="form" class="myform" name="form" method="post"
      action="{{= base_url() . 'manager/category/edit/' . $category->id }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_category_edit_list_ratings') }}</h3>
            <p>&nbsp;</p>
            <div class="row">
                {{ if($categorydefaultratings): }}
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-md-10">Rating Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{ foreach ($categorydefaultratings as $result): }}
                        <tr>
                            <td>{{= character_limiter($result->name, 50) }}</td>
                            <td>{{= anchor('manager/category/delete_default_rating/'.$result->id, lang('manager_category_default_rating_list_delete'),array('class' => 'btn btn-primary btn-danger')) }}</td>
                        </tr>
                        {{ endforeach }}
                        </tbody>
                    </table>
                </div>
                {{ endif }}
            </div>
        </div>
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-9">
                    <label>{{= lang('manager_category_default_rating_rating') }}</label>
                    <p class="help-block">{{= lang('manager_category_default_rating_rating_info') }}</p>
                    {{= form_error('rating_id') }}
                </div>
                <div class="col-md-3">
                    {{= form_dropdown('rating_id', $ratings, $selected_rating,array('class' => 'form-control')) }}
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="category_default_rating_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_category_default_rating_submit') }}"/>
        </div>
    </div>
</form>
{{ endif }}