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
        <div class="header_row">{{= lang('manager_page_add_title') }}</div>
        <p>&nbsp;</p>
        {{ if(isset($message)): }}
        <p>&nbsp;</p>

        <h3>{{= $message }}</h3>

        <p>&nbsp;</p>
        {{ endif }}
        <form id="form" class="myform" name="form" method="post" action="{{= base_url() . 'manager/page/add' }}">
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_page_form_name') }}
                        <span class="small">{{= lang('manager_page_form_name_info') }}</span>

                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="name" id="name"
                           value="{{= set_value('name',$page->name) }}"/>
                    {{= form_error('name') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_page_form_content') }}
                        <span class="small">{{= lang('manager_page_form_content_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <textarea cols="40" rows="20" class="long" name="content"
                              id="pagecontent">{{= set_value('content',$page->content) }}</textarea>
                    {{= form_error('content') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_page_form_meta_keywords') }}
                        <span class="small">{{= lang('manager_page_form_meta_keywords_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="meta_keywords" id="meta_keywords"
                           value="{{= set_value('meta_keywords',$page->meta_keywords) }}"/>
                    {{= form_error('meta_keywords') }}
                </div>
            </div>
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_page_form_meta_description') }}
                        <span class="small">{{= lang('manager_page_form_meta_description_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="meta_description" id="meta_description"
                           value="{{= set_value('meta_description',$page->meta_description) }}"/>
                    {{= form_error('meta_description') }}
                </div>
            </div>
            <input type="submit" name="page_submit" id="button" value="{{= lang('manager_page_form_submit_button') }}"/>
        </form>
    </div>
</div>