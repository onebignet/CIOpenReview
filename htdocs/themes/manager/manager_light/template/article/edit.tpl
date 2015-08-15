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
        <div class="header_row">{{= lang('manager_article_edit_title') }}</div>
        <p class="manager_right_link">
            <strong>-> {{= anchor('manager/articles', lang('manager_article_manage_back_to_articles')) }}</strong></p>

        <p>&nbsp;</p>

        <p class="manager_right_link">
            <strong>-> {{= anchor('article/show/'.$article->seo_title, lang('manager_article_preview'), 'target="_blank"') }}</strong>
        </p>

        <p>&nbsp;</p>

        <p>&nbsp;</p>

        <p>&nbsp;</p>

        <form id="form" class="myform" name="form" method="post"
              action="{{= base_url() . 'manager/article/edit/' . $article->id }}">
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_article_form_title') }}
                        <span class="small">{{= lang('manager_article_form_title_info') }}</span>

                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="title" id="title"
                           value="{{= set_value('title',$article->title) }}"/>
                    {{= form_error('title') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_article_form_description') }}
                        <span class="small">{{= lang('manager_article_form_description_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <textarea cols="40" rows="10" class="long" name="description"
                              id="description">{{= set_value('description',$article->description) }}</textarea>
                    {{= form_error('description') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_article_form_link_text') }}
                        <span class="small">{{= lang('manager_article_form_link_text_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="link_text" id="link_text"
                           value="{{= set_value('link_text',$article->link_text) }}"/>
                    {{= form_error('link_text') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_article_form_link_url') }}
                        <span class="small">{{= lang('manager_article_form_link_url_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="link_url" id="link_url"
                           value="{{= set_value('link_url',$article->link_url) }}"/>
                    {{= form_error('link_url') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_article_form_meta_keywords') }}
                        <span class="small">{{= lang('manager_article_form_meta_keywords_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="meta_keywords" id="meta_keywords"
                           value="{{= set_value('meta_keywords',$article->meta_keywords) }}"/>
                    {{= form_error('meta_keywords') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_article_form_meta_description') }}
                        <span class="small">{{= lang('manager_article_form_meta_description_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="meta_description" id="meta_description"
                           value="{{= set_value('meta_description',$article->meta_description) }}"/>
                    {{= form_error('meta_description') }}
                </div>
            </div>
            <input type="submit" name="article_submit" id="button"
                   value="{{= lang('manager_article_form_submit_button') }}"/>
        </form>
    </div>
</div>