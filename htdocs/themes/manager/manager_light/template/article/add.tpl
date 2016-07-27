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

<form id="form" class="myform" name="form" method="post" action="{{= base_url() . 'manager/article/add' }}">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{= lang('manager_article_add_title') }}</h3>
            <p>&nbsp;</p>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_article_form_title') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('title', $title) }}"
                           name="title"
                           id="title">
                    {{= form_error('title') }}
                    <p class="help-block">{{= lang('manager_article_form_title_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_article_form_description') }}</label>
                    <textarea cols="40" rows="10" class="long" name="description"
                              id="description">{{= set_value('description',$article->description) }}</textarea>
                    {{= form_error('description') }}
                    <p class="help-block">{{= lang('manager_article_form_description_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_article_form_link_text') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('link_text', $article->link_text) }}"
                           name="link_text"
                           id="link_text">
                    {{= form_error('link_text') }}
                    <p class="help-block">{{= lang('manager_article_form_link_text_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_article_form_link_url') }}</label>
                    <input class="form-control" type="text" value="{{= set_value('link_url', $article->link_url) }}"
                           name="link_url"
                           id="link_url">
                    {{= form_error('link_url') }}
                    <p class="help-block">{{= lang('manager_article_form_link_url_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_article_form_meta_keywords') }}</label>
                    <input class="form-control" type="text"
                           value="{{= set_value('meta_keywords', $article->meta_keywords) }}"
                           name="meta_keywords"
                           id="meta_keywords">
                    {{= form_error('meta_keywords') }}
                    <p class="help-block">{{= lang('manager_article_form_meta_keywords_info') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>{{= lang('manager_article_form_meta_description') }}</label>
                    <input class="form-control" type="text"
                           value="{{= set_value('meta_description', $article->meta_description) }}"
                           name="meta_description"
                           id="meta_description">
                    {{= form_error('meta_description') }}
                    <p class="help-block">{{= lang('manager_article_form_meta_description_info') }}</p>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" name="article_submit" id="button" class="btn btn-primary btn-success"
                   value="{{= lang('manager_article_form_submit_button') }}"/>
        </div>
    </div>
</form>
