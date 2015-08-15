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
        <div class="header_row">{{= lang('manager_category_edit_title') }}</div>
        <p>&nbsp;</p>

        <form id="form" class="myform" name="form" method="post"
              action="{{= base_url() . 'manager/category/edit/' . $category->id }}">

            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_category_form_name') }}
                        <span class="small">{{= lang('manager_category_form_name_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input class="strong" type="text" name="name" id="name"
                           value="{{= set_value('name', $category->name) }}"/>
                    {{= form_error('name') }}
                </div>
            </div>
            <p>&nbsp;</p>
            <input type="submit" name="category_submit" id="button"
                   value="{{= lang('manager_category_form_submit_button') }}"/>
        </form>
    </div>
    <p class="break">&nbsp;</p>
    {{ if($features): }}
    {{ if($categorydefaultfeatures): }}
    <h2>{{= lang('manager_category_edit_list_features') }}</h2>

    <p class="break">&nbsp;</p>
    {{ foreach ($categorydefaultfeatures as $result): }}
    <div class="manager_row">
        <p class="manager_left">{{= character_limiter($result->name, 50) }}</p>

        <p class="manager_narrow">{{= anchor('manager/category/delete_default_feature/'.$result->id, lang('manager_category_default_feature_list_delete'),'id="darkblue"') }}</p>
    </div>
    {{ endforeach }}
    {{ endif }}
    <div class="myform">
        <p>&nbsp;</p>

        <h2>{{= lang('manager_category_add_default_feature_for_category') }}</h2>

        <form id="form" class="myform" name="form" method="post"
              action="{{= base_url() . 'manager/category/edit/' . $category->id }}">
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_category_default_feature_feature') }}
                        <span class="small">{{= lang('manager_category_default_feature_feature_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    {{= form_dropdown('feature_id', $features) }}
                    {{= form_error('feature_id') }}
                </div>
                <p>&nbsp;</p>

                <p>&nbsp;</p>

                <p>&nbsp;</p>
                <input type="submit" name="category_default_feature_submit" id="button" style="width:300px;"
                       value="{{= lang('manager_category_default_feature_submit') }}"/>
            </div>
        </form>
    </div>
    {{ endif }}
    <p class="break">&nbsp;</p>
    {{ if($ratings): }}
    {{ if($categorydefaultratings): }}
    <h2>{{= lang('manager_category_edit_list_ratings') }}</h2>

    <p class="break">&nbsp;</p>
    {{ foreach ($categorydefaultratings as $result): }}
    <div class="manager_row">
        <p class="manager_left">{{= character_limiter($result->name, 50) }}</p>

        <p class="manager_narrow">{{= anchor('manager/category/delete_default_rating/'.$result->id, lang('manager_category_default_rating_list_delete'),'id="darkblue"') }}</p>
    </div>
    {{ endforeach }}
    {{ endif }}
    <div class="myform">
        <p>&nbsp;</p>

        <h2>{{= lang('manager_category_add_default_rating_for_category') }}</h2>

        <form id="form" class="myform" name="form" method="post"
              action="{{= base_url() . 'manager/category/edit/' . $category->id }}">
            <div class="formblock">
                <div class="formleft">
                    <label>{{= lang('manager_category_default_rating_rating') }}
                        <span class="small">{{= lang('manager_category_default_rating_rating_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    {{= form_dropdown('rating_id', $ratings) }}
                    {{= form_error('rating_id') }}
                </div>
                <p>&nbsp;</p>

                <p>&nbsp;</p>

                <p>&nbsp;</p>
                <input type="submit" name="category_default_rating_submit" id="button" style="width:300px;"
                       value="{{= lang('manager_category_default_rating_submit') }}"/>
            </div>
        </form>
    </div>
    {{ endif }}
</div>